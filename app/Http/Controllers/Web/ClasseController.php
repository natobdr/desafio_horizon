<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\ClasseResource;
use App\Models\Classe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ClasseController extends Controller
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = env('API_URL');
    }

    public function index()
    {
        $responseClasses = Http::get($this->baseUrl . '/classes');

        if ($responseClasses->successful()) {
            $dados = $responseClasses->json();
            $classes = $dados['classes'];

            return view('pages.classes.index', compact('classes'));
        } else {
            return view('pages.classes.index')->with('message', 'Erro ao buscar dados de classes.');
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
