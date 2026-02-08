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
        $departementId = auth()->guard('departement')->id();
        if (!$departementId) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter.');
        }
        $filieres = Filiere::where('id_Departement', $departementId)->withCount('classes')->get();
        return view('departement.filieres', compact('filieres'));
    }

    public function store(Request $request)
    {
        $departementId = auth()->guard('departement')->id();
        if (!$departementId) {
            return redirect()->route('login');
        }

        $request->validate([
            'nom_Filiere' => 'required|string|max:255|unique:filieres,nom_Filiere',
        ]);

        Filiere::create([
            'nom_Filiere' => $request->nom_Filiere,
            'id_Departement' => $departementId,
        ]);

        return redirect()->route('departement.filieres')->with('success', 'Filière créée avec succès !');
    }

    public function update(Request $request, $id)
    {
        $departementId = auth()->guard('departement')->id();
        $filiere = Filiere::where('id_Departement', $departementId)->findOrFail($id);

        $request->validate([
            'nom_Filiere' => 'required|string|max:255|unique:filieres,nom_Filiere,' . $id . ',id_Filiere',
        ]);

        $filiere->update([
            'nom_Filiere' => $request->nom_Filiere,
        ]);

        return redirect()->route('departement.filieres')->with('success', 'Filière mise à jour avec succès !');
    }

    public function destroy($id)
    {
        $departementId = auth()->guard('departement')->id();
        $filiere = Filiere::where('id_Departement', $departementId)->findOrFail($id);
        $filiere->delete();

        return redirect()->route('departement.filieres')->with('success', 'Filière supprimée avec succès !');
    }

    public function storeClasse(Request $request)
    {
        $departementId = auth()->guard('departement')->id();

        $request->validate([
            'filiere_id' => 'required|exists:filieres,id_Filiere',
            'lib_Classe' => 'required|string|max:255',
        ]);

        // Vérifier que la filière appartient au département
        Filiere::where('id_Departement', $departementId)->findOrFail($request->filiere_id);

        Classe::create([
            'lib_Classe' => $request->lib_Classe,
            'id_Filiere' => $request->filiere_id,
        ]);

        return redirect()->route('departement.filieres')->with('success', 'Classe créée avec succès !');
    }

    public function updateClasse(Request $request, $id)
    {
        $departementId = auth()->guard('departement')->id();

        $classe = Classe::whereHas('filiere', function($q) use ($departementId) {
            $q->where('id_Departement', $departementId);
        })->findOrFail($id);

        $request->validate([
            'lib_Classe' => 'required|string|max:255',
        ]);

        $classe->update([
            'lib_Classe' => $request->lib_Classe,
        ]);

        return redirect()->route('departement.filieres')->with('success', 'Classe mise à jour avec succès !');
    }

    public function destroyClasse($id)
    {
        $departementId = auth()->guard('departement')->id();

        $classe = Classe::whereHas('filiere', function($q) use ($departementId) {
            $q->where('id_Departement', $departementId);
        })->findOrFail($id);

        $classe->delete();

        return redirect()->route('departement.filieres')->with('success', 'Classe supprimée avec succès !');
    }

    public function getClasses($id)
    {
        $departementId = auth()->guard('departement')->id();
        $classes = Classe::whereHas('filiere', function($q) use ($departementId) {
            $q->where('id_Departement', $departementId);
        })->where('id_Filiere', $id)->get();

        return response()->json(['classes' => $classes]);
    }
}
