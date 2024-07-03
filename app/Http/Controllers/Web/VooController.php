<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\AeroportoResource;
use App\Http\Resources\ClasseResource;
use App\Http\Resources\PassageiroResource;
use App\Http\Resources\VooResource;
use App\Models\Aeroporto;
use App\Models\Classe;
use App\Models\Passageiro;
use App\Models\Ticket;
use App\Models\Voo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class VooController extends Controller
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = env('API_URL');
    }

    public function index()
    {
        $responseVoos = Http::get($this->baseUrl . '/voos');
        if ($responseVoos->successful()) {
            $dados = $responseVoos->json();
            $aeroportos = $dados['aeroportos'];
            $voos = $dados['voos'];

            return view('pages.voos.index', compact('aeroportos', 'voos'));
        } else {
            return view('pages.voos.index')->with('message', 'Erro ao buscar dados dos aeroportos e voos.');
        }
    }
    public function edit($id)
    {
        $voo = Http::post($this->baseUrl . '/voos_classe/' . $id);
        $aeroportos = Http::get($this->baseUrl . '/aeroportos');
        $classes = Http::get($this->baseUrl . '/classes');

        if ($voo->successful()) {
            $dadosVoo = $voo->json();
            $dadosAeroporto = $aeroportos->json();
            $dadosClasses = $classes->json();

            $voo = $dadosVoo['voo'];
            $aeroportos = $dadosAeroporto['aeroportos'];
            $classes = $dadosClasses['classes'];


            return view('pages.voos.editar', compact('voo', 'aeroportos', 'classes'));
        } else {
            return view('pages.voos.editar')->with('message', 'Nenhum Voo Foi Encontrado.');
        }
    }

    public function voos(Request $request)
    {
        if ($request->ajax()) {
            $dados = $request->get('query');
            $responseVoos = Http::post($this->baseUrl . '/voos/' . $dados);

            if ($responseVoos->successful()) {
                return response()->json(['html' => $responseVoos->json()]);
            } else {
                return response()->json(['html' => '<div class="card-body text-center"><h3>Nenhuma informação encontrada para o voo informado 😩</h3></div>']);
            }
        }
    }

    public function passageiros()
    {
        $responseVoos = Http::get($this->baseUrl . '/voos');

        if ($responseVoos->successful()) {
            $dados = $responseVoos->json();
            $voos = $dados['voos'];
            return view('pages.voos.search', compact('voos'));
        } else {
            return view('pages.voos.search')->with('message', 'Erro ao buscar dados dos aeroportos e voos.');
        }
    }
    public function teste()
    {
        $origem = '71';
        $destino = '70';
        $dataPartida = "2024-07-30 07:30";
        $dataRetorno = "2024-08-01 07:30";
        $precoMin = "0";
        $precoMax = "0";


        $query = Voo::with(['aeroportoOrigem.cidade', 'aeroportoDestino.cidade', 'classes.tipoClasse'])
            ->where('data_hora_partida', '>=', now());

        $queryIda = clone $query;
        $queryIda->where('aeroporto_origem_id', $origem)
            ->where('aeroporto_destino_id', $destino)
            ->whereDate('data_hora_partida', $dataPartida)
            ->whereHas('classes', function($q) {
                $q->where('quantidade_assentos', '>', 0)
                    ->where('valor_assento', '>', 0); // Verifica se o valor do assento é maior que zero
            });

// Filtro opcional por preço mínimo
        if ($precoMin !== null) {
            $queryIda->whereHas('classes', function($q) use ($precoMin) {
                $q->where('valor_assento', '>=', $precoMin);
            });
        }

// Filtro opcional por preço máximo
        if ($precoMax !== null) {
            $queryIda->whereHas('classes', function($q) use ($precoMax) {
                $q->where('valor_assento', '<=', $precoMax);
            });
        }

// Filtro para voos de retorno
        $queryRetorno = clone $query;
        if ($dataRetorno) {
            $queryRetorno->where('aeroporto_origem_id', $destino)
                ->where('aeroporto_destino_id', $origem)
                ->whereDate('data_hora_partida', $dataRetorno)
                ->whereHas('classes', function($q) {
                    $q->where('quantidade_assentos', '>', 0)
                        ->where('valor_assento', '>', 0); // Verifica se o valor do assento é maior que zero
                });

            // Filtro opcional por preço mínimo para voos de retorno
            if ($precoMin !== null) {
                $queryRetorno->whereHas('classes', function($q) use ($precoMin) {
                    $q->where('valor_assento', '>=', $precoMin);
                });
            }

            // Filtro opcional por preço máximo para voos de retorno
            if ($precoMax !== null) {
                $queryRetorno->whereHas('classes', function($q) use ($precoMax) {
                    $q->where('valor_assento', '<=', $precoMax);
                });
            }
        } else {
            $queryRetorno->whereRaw('1 = 0'); // Evita que traga voos de retorno quando não há data de retorno especificada
        }

// Executando as queries
        $voosIda = $queryIda->orderBy('data_hora_partida', 'asc')->get();
        $voosRetorno = $queryRetorno->orderBy('data_hora_partida', 'asc')->get();
        dd($voosIda. " - " . $voosRetorno);
    }

    public function create()
    {
        $dadosAeroportos = Http::get($this->baseUrl . '/aeroportos');
        $dadosClasses  = Http::get($this->baseUrl . '/classes');
        $aeroportos = $dadosAeroportos['aerportos'];
        $classes  = $dadosClasses['classes'];

        return view('pages.voos.cadastrar',compact( 'aeroportos', 'classes'));
    }

    public function store(Request $request)
    {
        try {
            $response = Http::asForm()->post($this->baseUrl . '/voos/store', $request);
            if ($response->successful()) {
                return redirect()->route('voos.index')->with('success', 'Voo criado com sucesso!');
            } else {
                return redirect()->route('voos.index')->with('error', 'Erro ao criar o voo. Tente novamente.');
            }
        } catch (\Exception $e) {
            return redirect()->route('voos.index')->with('error', 'Erro ao criar o voo. Por favor, tente novamente mais tarde.');
        }
    }
    public function update(Request $request, string $id)
    {
        try {
            $request['id'] = $id;
            $response = Http::asForm()->post($this->baseUrl . '/voos/update', $request);
            if ($response->successful()) {
                return redirect()->route('voos.index')->with('success', 'Voo atualizado com sucesso!');
            } else {
                return redirect()->route('voos.index')->with('error', 'Erro ao atualizar o voo. Tente novamente.');
            }
        } catch (\Exception $e) {
            return redirect()->route('voos.index')->with('error', 'Erro ao atualizar o voo, favor tente novamente mais tarde.');
        }
    }

    public function destroy($id)
    {

        $voo = Voo::findOrFail($id);
        $voo->delete();

        return redirect()->route('voos.index')->with('success', 'Voo excluído com sucesso!');
    }
}
