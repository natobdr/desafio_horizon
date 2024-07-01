<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PassageiroController extends Controller
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
}
