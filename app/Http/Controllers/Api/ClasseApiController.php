<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ClasseResource;
use App\Models\Classe;
use Illuminate\Http\Request;

class ClasseApiController extends Controller
{
    public function index()
    {
        $classes = Classe::with('tipoClasse')->get();
        $classes = ClasseResource::collection($classes);

        if ($classes->resource) {
            return ['classes' => $classes->resource];
        } else {
            // Lida com a falha da solicitação
            return response()->json(['message' => 'Os dados de clase não foram encontrados'], 404);
        }

    }

    public function create()
    {
        return view('classes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'quantidade_assentos' => 'required|integer',
            'valor_assento' => 'required|numeric',
            'tipo_id' => 'required|exists:tipo_classes,id',
        ]);

        Classe::create($request->all());

        return redirect()->route('classes.index')->with('success', 'Classe cadastrada com sucesso!');
    }

    public function update(Request $request, string $classe)
    {
        $classe = Classe::findOrFail($classe);

        $request->validate([
            'quantidade_assentos' => 'required|integer',
            'valor_assento' => 'required|numeric',
            'tipo_id' => 'nullable|exists:tipos,id',
        ]);

        $classe->update($request->all());

        return redirect()->route('classes.index')->with('success', 'Classe atualizada com sucesso!');
    }
    public function destroy(Classe $classe)
    {

        $classe->delete();

        return redirect()->route('classes.index')->with('success', 'Classe excluída com sucesso!');
    }
}
