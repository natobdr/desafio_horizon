<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AeroportoResource;
use App\Models\Aeroporto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class AeroportoApiController extends Controller
{
    public function index()
    {

        $aeroportos = Aeroporto::with('cidade')->get();
        $aeroportos = AeroportoResource::collection($aeroportos);

        if ($aeroportos) {
            return response()->json(['message' => 'Aeroportos encontrados com sucesso', 'aeroportos' => $aeroportos->resource], 200);
        } else {
            // Lida com a falha da solicitação
            return response()->json(['message' => 'Nenhum aeroporto encontrado'], 404);
        }
    }

}
