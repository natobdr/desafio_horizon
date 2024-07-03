<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PassageiroResource;
use App\Http\Resources\TicketResource;
use App\Models\Bagagem;
use App\Models\Classe;
use App\Models\Passageiro;
use App\Models\Ticket;
use App\Services\PdfService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TicketAPiController extends Controller
{
    protected $pdfService;

    public function __construct(PdfService $pdfService)
    {
        $this->pdfService = $pdfService;
    }
    public function downloadTag(Request $request)
    {
        try{
            $ticket = Ticket::with('passageiro', 'bagagem')
                ->where('ticket_status', true)
                ->findOrFail($request->input('id_passagem'));
        }catch (\Exception){
            return response()->json(['message' => 'A passagem não foi encontrado'], 422);
        }

        try {
            $bagagem = $ticket->bagagem;
            $passageiro = $ticket->passageiro;
            if(Carbon::parse($ticket->voo->data_hora_partida)->subHours(5)->toDateTimeString() <= Carbon::now()->subHours(5)->toDateTimeString())
                return $this->pdfService->gerarPdf($bagagem->id, 'tag', [$bagagem, $passageiro], 'etiqueta');

            return response()->json(['message' => 'A impressão da etiqueta estará disponível a partir de 5 horas antes do embarque'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao cancelar a passagem. Por favor, tente novamente mais tarde.'], 500);
        }


    }
    public function downloadVoucher(Request $request)
    {
        try{
            $ticket = Ticket::with('voo.aeroportoOrigem', 'voo.aeroportoDestino', 'passageiro', 'bagagem', 'classe.tipoClasse')
                ->where('ticket_status', true)
                ->findOrFail($request->input('id_passagem'));
        }catch (\Exception){
            return response()->json(['message' => 'A passagem não foi encontrado'], 422);
        }

        try{
            $passageiro = $ticket->passageiro;
            $classe = $ticket->classe;

            if(Carbon::parse($ticket->voo->data_hora_partida)->subHours(5)->toDateTimeString() <= Carbon::now()->subHours(5)->toDateTimeString())
                return $this->pdfService->gerarPdf($ticket->id, 'voucher', [$ticket, $passageiro, $classe], 'voucher');

            return response()->json(['message' => 'A impressão da passagem estará disponível a partir de 5 horas antes do embarque'], 200);
        }catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao cancelar a passagem. Por favor, tente novamente mais tarde.'], 500);
        }
    }

    public function registerPassage(Request $request)
    {
        try{
            $request->validate([
                'cpf_passageiro' => 'required',
                'nome_passageiro' => 'required',
                'email_passageiro' => 'required',
                'data_nascimento' => 'required',
                'id_voo' => 'required',
                'id_classe' => 'required',
                'bagagem' => 'required',
            ]);

            $cliente = Passageiro::where('cpf', $request->input('cpf_passageiro'))->first();
            if (!$cliente) {
                // Cria o passageiro
                $cliente = Passageiro::create([
                    'nome' => $request->input('nome_passageiro'),
                    'email' => $request->input('email_passageiro'),
                    'cpf' => $request->input('cpf_passageiro'),
                    'data_nascimento' => $request->input('data_nascimento'),
                ]);
            }

            $classe = Classe::findOrFail($request->input('id_classe'));
            $nPassagens = $this->getTicketsSold($request->input('id_voo'),$classe->id);


            if($nPassagens < $classe->quantidade_assentos){

                $numero = Ticket::generateUniqueTicketNumber();

                $passagem = Ticket::create([
                    'numero' => $numero,
                    'passageiro_id' =>  $cliente->id,
                    'voo_id' => $request->input('id_voo'),
                    'classe_id' => $request->input('id_classe'),
                    'preco_total' => $classe->valor_assento,
                ]);
            }else{
                return response()->json(['message' => 'Não existem mais assentos disponiveis nesta classe'], 422);
            }


            if($request->input('bagagem'))
            {
                $numBagage = Bagagem::generateUniqueBagNumber();

                Bagagem::create([
                    'ticket_id' => $passagem->id,
                    'numero_identificacao' => $numBagage,
                ]);

                $passagem->update(['preco_total' => $this->calcularAcrescimo($classe->valor_assento)]);
            }

            return response()->json(['message' => 'Passagem comprada com sucesso', 'voo' => $passagem], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao registrar passagem. Por favor, tente novamente mais tarde.'], 500);
        }
    }
    public function getPassage(Request $request)
    {
        try{
            $request->validate([
                'cpf_passageiro' => 'required',
            ]);

            $cpf = $request->input('cpf_passageiro');

            $tickets = Ticket::with(['voo', 'voo.aeroportoOrigem', 'voo.aeroportoDestino', 'classe.tipoClasse', 'bagagem'])
                ->whereHas('passageiro', function ($query) use ($cpf) {
                    $query->where('cpf', $cpf);
                })
                ->whereHas('voo', function ($query) {
                    $query->where('data_hora_partida', '>=', now());
                })
                ->get();

            if($tickets){
                return response()->json(['message' => 'Passagem encotrada com sucesso', 'passagem' => $tickets], 200);
            }
            return response()->json(['message' => 'Nenhuma passagem encontrada para o CPF informado'], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao buscar passagem. Por favor, tente novamente mais tarde.'], 500);
        }
    }

    public function destroy(Request $request)
    {
        try{
            $passagem = Ticket::findOrFail($request->input('id_passagem'));
        }catch (\Exception){
            return response()->json(['message' => 'A passagem não foi encontrado'], 422);
        }

        try {

            $passagem->update([
                'ticket_status' => false
            ]);

            return response()->json(['message' => 'Passagem cancelada com sucesso'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao cancelar a passagem. Por favor, tente novamente mais tarde.'], 500);
        }
    }

    function calcularAcrescimo($valor) {
        $acrescimo = $valor * 1.10;
        return $acrescimo;
    }

    public function getTicketsSold($vooId, $classeId)
    {
        $quantidade = Ticket::where('voo_id', $vooId)
            ->where('classe_id', $classeId)
            ->count();

        return $quantidade;
    }
}
