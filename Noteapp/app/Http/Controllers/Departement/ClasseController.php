<?php

namespace App\Http\Controllers\Departement;

use App\Http\Controllers\Controller;
use App\Models\Classe;
use App\Models\Filiere;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClasseController extends Controller
{
    public function index()
    {
        $classes = Classe::with('filiere')->get();
        return view('departement.classes', compact('classes'));
    }

    public function create()
    {
        $filieres = Filiere::all();
        return view('departement.classes.create', compact('filieres'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom_Classe' => 'required|string|max:255|unique:classes,nom_Classe',
            'id_Filiere' => 'required|exists:filieres,id_Filiere',
        ]);

        Classe::create([
            'nom_Classe' => $request->nom_Classe,
            'id_Filiere' => $request->id_Filiere,
        ]);

        return redirect()->route('departement.classes')->with('success', 'Classe créée avec succès !');
    }

    public function edit($id)
    {
        $classe = Classe::findOrFail($id);
        $filieres = Filiere::all();
        return view('departement.classes.edit', compact('classe', 'filieres'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nom_Classe' => 'required|string|max:255|unique:classes,nom_Classe,' . $id . ',id_Classe',
            'id_Filiere' => 'required|exists:filieres,id_Filiere',
        ]);

        $classe = Classe::findOrFail($id);
        $classe->update([
            'nom_Classe' => $request->nom_Classe,
            'id_Filiere' => $request->id_Filiere,
        ]);

        return redirect()->route('departement.classes')->with('success', 'Classe mise à jour avec succès !');
    }

    public function destroy($id)
    {
        $classe = Classe::findOrFail($id);
        $classe->delete();

        return redirect()->route('departement.classes')->with('success', 'Classe supprimée avec succès !');
    }
}