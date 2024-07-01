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
        $dados = Voo::with(['classe.tipoClasse'])->find($id);
        dd($clientes->resource);
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
