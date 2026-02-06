<?php

namespace App\Http\Controllers\Departement;

use App\Http\Controllers\Controller;
use App\Models\Enseignant;
use App\Models\Departement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EnseignantController extends Controller
{
    public function index()
    {
        $enseignants = Enseignant::with('departement')->get();
        return view('departement.enseignants', compact('enseignants'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom_Enseignant' => 'required|string|max:255',
            'email' => 'required|email|unique:enseignants,email',
            'matricule' => 'required|string|max:50|unique:enseignants,matricule',
            'login' => 'required|string|max:100|unique:enseignants,login',
        ]);

        $password = 'password123'; // Mot de passe par défaut, à changer par l'enseignant
        
        Enseignant::create([
            'nom_Enseignant' => $request->nom_Enseignant,
            'email' => $request->email,
            'matricule' => $request->matricule,
            'login' => $request->login,
            'password' => Hash::make($password),
            'id_Departement' => 1, // À adapter selon l'authentification
        ]);

        return redirect()->route('departement.enseignants')->with('success', 'Enseignant ajouté avec succès !');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nom_Enseignant' => 'required|string|max:255',
            'email' => 'required|email|unique:enseignants,email,' . $id . ',id_Enseignant',
            'matricule' => 'required|string|max:50|unique:enseignants,matricule,' . $id . ',id_Enseignant',
            'login' => 'required|string|max:100|unique:enseignants,login,' . $id . ',id_Enseignant',
        ]);

        $enseignant = Enseignant::findOrFail($id);
        $enseignant->update([
            'nom_Enseignant' => $request->nom_Enseignant,
            'email' => $request->email,
            'matricule' => $request->matricule,
            'login' => $request->login,
        ]);

        return redirect()->route('departement.enseignants')->with('success', 'Enseignant mis à jour avec succès !');
    }

    public function destroy($id)
    {
        $enseignant = Enseignant::findOrFail($id);
        $enseignant->delete();

        return redirect()->route('departement.enseignants')->with('success', 'Enseignant supprimé avec succès !');
    }
}