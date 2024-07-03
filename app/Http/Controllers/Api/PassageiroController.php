<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Voo;
use Illuminate\Http\Request;

class PassageiroController extends Controller
{
    public function passageirosVoo(Request $request){

        try{
            $voo = Voo::with(['ticket.passageiro','ticket.classe.tipoClasse'])->find($request->input('id_voo'));
            return response()->json(['message' => 'Passageiros encontrados com sucesso', 'passageiros' => $voo], 200);
        }catch (\Exception){
            return response()->json(['message' => 'O voo informado não foi encontrado'], 422);
        }
    }

}
