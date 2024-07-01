<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\AeroportoResource;
use App\Models\Aeroporto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class AeroportoController extends Controller
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
            return view('pages.aeroportos.index', compact('aeroportos'));
        } else {
            // Tratar erros, retornar uma resposta apropriada para falhas na requisição
            return view('pages.aeroportos.index')->with('message', 'Erro ao buscar dados dos aeroportos.');
        }
    }
}
