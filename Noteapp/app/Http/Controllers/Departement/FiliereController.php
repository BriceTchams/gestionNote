<?php

namespace App\Http\Controllers\Departement;

use App\Http\Controllers\Controller;
use App\Models\Filiere;
use App\Models\Classe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FiliereController extends Controller
{
    public function index()
    {
        $filieres = Filiere::withCount('classes')->get();
        return view('departement.filieres', compact('filieres'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom_Filiere' => 'required|string|max:255|unique:filieres,nom_Filiere',
        ]);

        Filiere::create([
            'nom_Filiere' => $request->nom_Filiere,
            'id_Departement' => 1, // À adapter selon l'authentification
        ]);

        return redirect()->route('departement.filieres')->with('success', 'Filière créée avec succès !');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nom_Filiere' => 'required|string|max:255|unique:filieres,nom_Filiere,' . $id . ',id_Filiere',
        ]);

        $filiere = Filiere::findOrFail($id);
        $filiere->update([
            'nom_Filiere' => $request->nom_Filiere,
        ]);

        return redirect()->route('departement.filieres')->with('success', 'Filière mise à jour avec succès !');
    }

    public function destroy($id)
    {
        $filiere = Filiere::findOrFail($id);
        $filiere->delete();

        return redirect()->route('departement.filieres')->with('success', 'Filière supprimée avec succès !');
    }

    public function storeClasse(Request $request)
    {
        $request->validate([
            'filiere_id' => 'required|exists:filieres,id_Filiere',
            'lib_Classe' => 'required|string|max:255',
            // 'nbre_Elv' => 'required|integer|min:1',
        ]);

        Classe::create([
            'lib_Classe' => $request->lib_Classe,
            // 'nbre_Elv' => $request->nbre_Elv,
            'id_Filiere' => $request->filiere_id,
        ]);

        return redirect()->route('departement.filieres')->with('success', 'Classe créée avec succès !');
    }

    public function updateClasse(Request $request, $id)
    {
        $request->validate([
            'lib_Classe' => 'required|string|max:255',
            // 'nbre_Elv' => 'required|integer|min:1',
        ]);

        $classe = Classe::findOrFail($id);
        $classe->update([
            'lib_Classe' => $request->lib_Classe,
            // 'nbre_Elv' => $request->nbre_Elv,
        ]);

        return redirect()->route('departement.filieres')->with('success', 'Classe mise à jour avec succès !');
    }

    public function destroyClasse($id)
    {
        $classe = Classe::findOrFail($id);
        $classe->delete();

        return redirect()->route('departement.filieres')->with('success', 'Classe supprimée avec succès !');
    }
}