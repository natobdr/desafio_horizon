<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Bagagem;
use App\Models\Classe;
use App\Models\Passageiro;
use App\Models\Ticket;
use App\Models\Voo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = env('API_URL');
    }

    public function index()
    {
        $response = Http::get($this->baseUrl . '/aeroportos');

        if ($response->successful()) {
            $aeroportos = $response->json();
            $aeroportos = $aeroportos['aerportos'];
            return view('home', compact('aeroportos'));
        } else {
            // Tratar erros, retornar uma resposta apropriada para falhas na requisição
            return view('home')->with('message', 'Erro ao buscar dados dos aeroportos.');
        }
    }
    public function gerarticket(Request $request)
    {
        $cpf = str_replace(['.', '-'], '', $request->get('query'));

        if ($request->ajax()) {

            $clientes = Passageiro::with([
                'ticket' => function ($query) {
                    $query->whereHas('voo', function ($query) {
                        $query->whereDate('data_hora_partida', '>=', Carbon::now()->format('Y-m-d H:i:s'));
                    })->with('voo', 'voo.aeroportoOrigem', 'voo.aeroportoDestino', 'classe.tipoClasse', 'bagagem');
                }
            ])
                ->where('cpf', $cpf)
                ->get();


            if ($clientes[0]->ticket->count() > 0) {
                return response()->json(['html' => $this->montarDiv($clientes)]);
            } else {
                return response()->json(['html' => '<div class="card-body text-center"><h3>Nenhum voo encontrado para o CPF informado 😩</h3></div>']);
            }
        }
    }
    public function searchticket()
    {
        return view('searchticket');
    }

    public function trecho(Request $request)
    {

        $clientes = Passageiro::with([
            'ticket' => function ($query) {
                $query->whereHas('voo', function ($query) {
                    $query->whereDate('data_hora_partida', '>=', Carbon::now()->format('Y-m-d H:i:s'));
                })->with('voo', 'voo.aeroportoOrigem', 'voo.aeroportoDestino', 'classe.tipoClasse', 'bagagem');
            }
        ])
            ->where('cpf', '02192115580')
            ->get();

        $voos = Voo::with(['aeroportoOrigem.cidade', 'aeroportoDestino.cidade', 'classes.tipoClasse'])
            ->where([
                ['aeroporto_origem_id', '=', ],
                ['aeroporto_destino_id', '=', ]
            ])
            ->get();

        if ($voos->count() > 0) {
            return $voos;
        } else {
            return response()->json('Nenhum voo encontrado 😩');
        }
    }
    public function buyticket(Request $request){

        $clientes = Passageiro::with('ticket', 'ticket.voo', 'ticket.voo.aeroportoOrigem.cidade', 'ticket.voo.aeroportoDestino.cidade', 'ticket.classes.tipoClasse',  'ticket.bagagem')
            ->where('cpf', '02757792547')
            ->get();
        $this.$this->montarDiv($clientes);
    }

    public function montarDiv($dados) {
        $div = '<section class="booking-area">';

        foreach ($dados as $cliente) {

            $div .= '<div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="nome">Nome</label>
                        <input type="text" class="form-control" id="nome" name="nome" value="' . $cliente->nome . '" disabled>
                    </div>
                 </div>';

            foreach ($cliente->ticket as $ticket) {
                $div .= '<table class="table">
                        <thead>
                            <tr>
                                <th colspan="2">Origem X Destino</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Número do Voo</td>
                                <td>' . $ticket->voo->numero . '</td>
                            </tr>
                            <tr>
                                <td>Aeroporto Origem</td>
                                <td>' . $ticket->voo->aeroportoOrigem->codigo_iata . ' - ' . $ticket->voo->aeroportoOrigem->nome . ' - ' . $ticket->voo->aeroportoOrigem->cidade->nome . '</td>
                            </tr>
                            <tr>
                                <td>Aeroporto Destino</td>
                                <td>' . $ticket->voo->aeroportoDestino->codigo_iata . ' - ' . $ticket->voo->aeroportoDestino->nome . ' - ' . $ticket->voo->aeroportoDestino->cidade->nome . '</td>
                            </tr>
                            <tr>
                                <td>Classe</td>
                                <td>' . $ticket->classe->tipoClasse->tipo . '</td>
                            </tr>
                            <tr>
                                <td>Preço Total</td>
                                <td>R$ ' . $ticket->preco_total . '</td>
                            </tr>
                            <tr>
                                <td>Data e Hora de Partida</td>
                                <td>' . Carbon::parse($ticket->voo->data_hora_partida)->format('d-m-y H:i') . '</td>
                            </tr>';
                            if(Carbon::parse($ticket->voo->data_hora_partida)->subHours(5)->toDateTimeString() <= Carbon::now()->subHours(5)->toDateTimeString()){
                                $div .= '<tr>
                                <td>Faça a impressão de sua passagem</td>
                                <td><a href="' . route('download.voucher', $ticket->id) . '">Baixar Voucher</a></td>
                            </tr>';
                                if($ticket->bagagem){
                                    $div .= '<tr>
                                <td>Faça a impressão etiqueta de bagagem</td>
                                <td><a href="' . route('download.tag', $ticket->id) . '">Baixar Etiqueta</a></td>
                            </tr>';
                                }
                            }else{
                                $div .= '<tr>
                                <td>A impressão da passagem estará disponível a partir de 5 horas antes do embarque.</td>                                
                            </tr>';
                            }
                $div .= '</tbody>
                    </table>';
                $div .= '</section>';
            }
        }

        return $div;
    }


    public function searchFlights(Request $request)
    {
        try {
            $response = Http::asForm()->get($this->baseUrl . '/searchFlights', $request);
            $dados = $response->json();

            if ($response->successful()) {
                $voosIda = $dados['voosIda'];
                $voosRetorno = $dados['voosRetorno'];
                return view('voos', compact('voosIda', 'voosRetorno'));
            } else {
                return view('voos')->with('error', 'Não foram encontrados voos para esta data.');
            }
        } catch (\Exception $e) {
            return view('voos')->with('error', 'Erro ao buscar o voo. Por favor, tente novamente mais tarde.');
        }



    }
    public function registerCustomer(Request $request)
    {
        try {
            $dados = $request->all();
            $response = Http::asForm()->post($this->baseUrl . '/registerCustomer', $dados);

            if ($response->successful()) {
                return redirect()->route('searchticket')->with('success', 'Compra realizada com sucesso, a passagem estará disponivel até 5 horas antes do embarque!');
            }
        } catch (\Exception $e) {
            return redirect()->route('searchticket')->with('error', 'Compra não realizada,  tente novamente mais tarde');
        }

    }
}
