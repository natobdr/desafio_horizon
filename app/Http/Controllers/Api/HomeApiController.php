<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bagagem;
use App\Models\Classe;
use App\Models\Passageiro;
use App\Models\Ticket;
use App\Models\Voo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HomeApiController extends Controller
{
    public function registerCustomer(Request $request)
    {
        $dados = $request->all();
        $token = $dados['_token'];
        $vooRetorno = $dados['voosRetorno'];

        $dados = $request->all();
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
