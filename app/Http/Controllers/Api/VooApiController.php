<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AeroportoResource;
use App\Http\Resources\ClasseResource;
use App\Http\Resources\VooResource;
use App\Models\Aeroporto;
use App\Models\Bagagem;
use App\Models\Classe;
use App\Models\Passageiro;
use App\Models\Ticket;
use App\Models\Voo;
use Carbon\Carbon;
use Illuminate\Http\Request;

class VooApiController extends Controller
{
    public function index()
    {
        $voos = Voo::with(['aeroportoOrigem.cidade', 'aeroportoDestino.cidade', 'classes.tipoClasse'])->get();
        $aeroportos = Aeroporto::all();

        $voos = VooResource::collection($voos);
        $aeroportos =AeroportoResource::collection($aeroportos);

        if ($voos && $aeroportos) {
            return ['aeroportos' => $aeroportos->resource,'voos' => $voos->resource];
        } else {
            // Lida com a falha da solicitação
            return response()->json(['message' => 'Os dados não foram encontrado'], 404);
        }

    }

    public function voos($id)
    {
        $voo = Voo::with(['ticket.passageiro','ticket.classe.tipoClasse'])->find($id);

        if ($voo) {
            return response()->json([
                'message' => 'Voo encontrado com sucesso!',
                'voo' => $voo,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Voo não encontrado.',
            ], 404);
        }

    }

    public function voosClasse($id)
    {
        $voo = Voo::with(['classes.tipoClasse'])->find($id);

        if ($voo) {
            return response()->json([
                'message' => 'Voo encontrado com sucesso!',
                'voo' => $voo,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Voo não encontrado.',
            ], 404);
        }

    }

    public function create()
    {
        $aeroportos = Aeroporto::all();
        $classes  = Classe::with('tipoClasse')->get();
        $aeroportos =AeroportoResource::collection($aeroportos);
        $classes = ClasseResource::collection($classes);

        return view('pages.voos.cadastrar',compact( 'aeroportos', 'classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'aeroporto_origem_id' => 'required',
            'aeroporto_destino_id' => 'required',
            'data_hora_partida' => 'required',
            'classe_id' => 'required|array',
            'classe_id.*' => 'exists:classes,id'
        ]);

        $numero = Voo::generateUniqueFlightNumber();

        foreach ($request->classe_id as $id){
            $voo = Voo::create([
                'numero' => $numero,
                'aeroporto_origem_id' => $request->aeroporto_origem_id,
                'aeroporto_destino_id' => $request->aeroporto_destino_id,
                'data_hora_partida' => $request->data_hora_partida,
                'classe_id' => $id,
            ]);
        }


        $voo->classes()->sync($request->classe_id);

        return redirect()->route('voos.index')->with('success', 'Voo criado com sucesso!');
    }

    public function update(Request $request)
    {
        try{
        $id = $request->input('id');
        $classe_id = $request->input('classe_id');

        $request->validate([
            'aeroporto_origem_id' => 'required|exists:aeroportos,id',
            'aeroporto_destino_id' => 'required|exists:aeroportos,id',
            'data_hora_partida' => 'required|date_format:Y-m-d\TH:i',
        ]);

        $voo = Voo::findOrFail($id);
            $voo->update([
                'aeroporto_origem_id' => $request->input('aeroporto_origem_id'),
                'aeroporto_destino_id' => $request->input('aeroporto_destino_id'),
                'data_hora_partida' => $request->input('data_hora_partida'),
            ]);

            $voo->classes()->sync($classe_id);

            if ($voo) {
                return response()->json([
                    'message' => 'Voo Atualizado com sucesso!',
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Voo não atualizado.',
                ], 404);
            }

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e,
            ], 404);
        }
    }

    public function destroy($id)
    {

        $voo = Voo::findOrFail($id);
        $voo->delete();

        return redirect()->route('voos.index')->with('success', 'Voo excluído com sucesso!');
    }

    public function searchFlights(Request $request)
    {
        $origem = $request->input('origin');
        $destino = $request->input('destination');
        $dataPartida = $request->input('departure_date');
        $dataRetorno = $request->input('return_date');

        // Query combinada para voos de ida e retorno
        $voos = Voo::with(['aeroportoOrigem.cidade', 'aeroportoDestino.cidade', 'classes.tipoClasse'])
            ->where(function ($query) use ($origem, $destino, $dataPartida) {
                $query->where('aeroporto_origem_id', $origem)
                    ->where('aeroporto_destino_id', $destino)
                    ->whereDate('data_hora_partida', $dataPartida);
            })
            ->orWhere(function ($query) use ($origem, $destino, $dataRetorno) {
                if ($dataRetorno) {
                    $query->where('aeroporto_origem_id', $destino)
                        ->where('aeroporto_destino_id', $origem)
                        ->whereDate('data_hora_partida', $dataRetorno);
                }
            })
            ->get();

        $voosIda = $voos->filter(function ($voo) use ($origem, $destino, $dataPartida) {
            return $voo->aeroporto_origem_id == $origem
                && $voo->aeroporto_destino_id == $destino
                && Carbon::parse($voo->data_hora_partida)->toDateString() == Carbon::parse($dataPartida)->toDateString();
        });

        $voosRetorno = $voos->filter(function ($voo) use ($destino, $origem, $dataRetorno) {
            return $dataRetorno
                && $voo->aeroporto_origem_id == $destino
                && $voo->aeroporto_destino_id == $origem
                && Carbon::parse($voo->data_hora_partida)->toDateString() == Carbon::parse($dataRetorno)->toDateString();
        });

        if ($voosIda->isEmpty()) {
            $voosIda = Voo::with(['aeroportoOrigem.cidade', 'aeroportoDestino.cidade', 'classes.tipoClasse'])
                ->where('aeroporto_origem_id', $origem)
                ->where('aeroporto_destino_id', $destino)
                ->orderBy('data_hora_partida', 'asc')
                ->get();
        }

        if ($voosRetorno->isEmpty()) {
            $voosRetorno = Voo::with(['aeroportoOrigem.cidade', 'aeroportoDestino.cidade', 'classes.tipoClasse'])
                ->where('aeroporto_origem_id', $destino)
                ->where('aeroporto_destino_id', $origem)
                ->orderBy('data_hora_partida', 'asc')
                ->get();
        }

        if ($voosIda) {
            return ['mensagem' => 'Voos encotrados com sucesso!','voosIda' => $voosIda, 'voosRetorno' => $voosRetorno];
        } else {
            // Lida com a falha da solicitação
            return response()->json(['message' => 'Nenhum voo foi encontrado!'], 404);
        }
    }

    public function registerCustomer(Request $request)
    {

        $dados = $request->all();
        $token = $dados['_token'];
        $vooRetorno = $dados['voosRetorno'];

        preg_match('/^(\d+)_(\d+)$/', $dados['voosRetorno'][0], $matches);
        $idVooRetorno = $matches[1]; // Obtém o ID do voo
        $idClasseRetorno = $matches[2]; // Obtém o ID da classe

        array_shift($dados); // Remove o _token

        foreach ($dados as $key => $value) {
            preg_match('/^(\w+)_(\d+)_(\d+)$/', $key, $matches);
            if (count($matches) !== 4) {
                continue;
            }

            $nome = $matches[1];
            $idVoo = $matches[2];
            $idClasse = $matches[3];
            $valor = $value[0];

            if (!isset($passageiros[$idVoo][$idClasse])) {
                $passageiros[$idVoo][$idClasse] = [];
            }

            if (!isset($passageiros[$idVoo][$idClasse][$nome])) {
                $passageiros[$idVoo][$idClasse][$nome] = [];
            }

            $passageiros[$idVoo][$idClasse][$nome][] = $valor;
        }


        foreach ($passageiros as $idVoo => $classes) {
            foreach ($classes as $idClasse => $campos) {
                $numPassageiros = count($campos['nome']);

                for ($i = 0; $i < $numPassageiros; $i++) {
                    $nome = $campos['nome'][$i];
                    $email = $campos['email'][$i];
                    $cpf = $campos['cpf'][$i];
                    $bagagem = $campos['bagagem'][$i]??null;
                    $dt_nasc = \DateTime::createFromFormat('Y-m-d', $campos['nasc'][$i])->format('Y-m-d');
                    $classe = Classe::find($idClasse);
                    $valorPassagem = $bagagem? $this->calcularAcrescimo($classe->valor_assento):$classe->valor_assento;
                    // Verifica se o passageiro já existe
                    $cliente = Passageiro::where('cpf', $cpf)->first();
                    if (!$cliente) {
                        // Cria o passageiro
                        $cliente = Passageiro::create([
                            'nome' => $nome,
                            'email' => $email,
                            'cpf' => $cpf,
                            'data_nascimento' => $dt_nasc,
                        ]);
                    }


                    // Cria a passagem
                    $passagem = Ticket::create([
                        'passageiro_id' => $cliente->id,
                        'voo_id' => $idVoo,
                        'classe_id' => $idClasse,
                        'preco_total' => $valorPassagem,
                    ]);

                    if($idVooRetorno && $vooRetorno){
                        $classeRetorno = Classe::where('id', $idClasseRetorno)->first();
                        $passagem = Ticket::create([
                            'passageiro_id' => $cliente->id,
                            'voo_id' => $idVooRetorno,
                            'classe_id' => $idClasseRetorno,
                            'preco_total' => $classeRetorno->valor_assento,
                        ]);
                    }


                    if($bagagem){
                        $numBagage = Bagagem::generateUniqueBagNumber();

                        Bagagem::create([
                            'ticket_id' => $passagem->id,
                            'numero_identificacao' => $numBagage,
                        ]);
                    }
                }
            }
        }
        return response()->json(['message' => 'Registro realizado com sucesso!'], 200);
    }
    function calcularAcrescimo($valor) {
        $acrescimo = $valor * 1.10;
        return $acrescimo;
    }
}
