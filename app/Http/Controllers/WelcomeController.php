<?php

namespace App\Http\Controllers;

use App\Http\Resources\AeroportoResource;
use App\Http\Resources\VooResource;
use App\Models\Aeroporto;
use App\Models\Voo;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index()
    {
        $voos = Voo::with(['aeroportoOrigem.cidade', 'aeroportoDestino.cidade', 'classes.tipoClasse'])->get();
        $aeroportos = Aeroporto::with('cidade')->get();

        $voos = VooResource::collection($voos);
        $aeroportos =AeroportoResource::collection($aeroportos);
        return view('welcome', compact('voos','aeroportos'));
    }
}
