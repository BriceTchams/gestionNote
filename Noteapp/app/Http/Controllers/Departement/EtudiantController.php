<?php

namespace App\Http\Controllers\Departement;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


use App\Http\Controllers\Controller;
use App\Models\Filiere;
use App\Models\Classe;
use App\Models\Etudiant;
use Illuminate\Auth\Middleware\AuthenticateWithBasicAuth;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Mime\Email;

class EtudiantController extends Controller
{
    public function index(Request $request)
    {
        $filieres = Filiere::all();
        $classes = [];
        $etudiants = collect();
        
        if ($request->filled('filiere_id')) {
            $classes = Classe::where('id_Filiere', $request->filiere_id)->get();
        }
        
        if ($request->filled('classe_id')) {
            $etudiants = Etudiant::where('id_Classe', $request->classe_id)->get();
        }
        
        return view('departement.etudiants', compact('filieres', 'classes', 'etudiants'));
    }
    
    public function getEtudiantsByClasse(Request $request)
    {
        $request->validate([
            'classe_id' => 'required|exists:classes,id_Classe'
        ]);
        
        $etudiants = Etudiant::where('id_Classe', $request->classe_id)->get();
        
        return response()->json(['etudiants' => $etudiants]);
    }
    
    public function search(Request $request)
    {
        $query = Etudiant::query();
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'LIKE', '%' . $search . '%')
                  ->orWhere('matricule', 'LIKE', '%' . $search . '%')
                  ->orWhere('email', 'LIKE', '%' . $search . '%')
                  ->orWhere('login', 'LIKE', '%' . $search . '%');
            });
        }
        
        if ($request->filled('classe_id')) {
            $query->where('id_Classe', $request->classe_id);
        }
        
        $etudiants = $query->get();
        
        return response()->json(['etudiants' => $etudiants]);
    }
    
    public function updateEtudiant(Request $request, $id)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'telephone' => 'required|string|max:11|unique:etudiants,telephone,' . $id . ',id_Etudiant',
            'email' => 'required|email|unique:etudiants,email,' . $id . ',id_Etudiant',
            'idClasse' => 'required|exists:classes,id_Classe'
        ]);
        
        $etudiant = Etudiant::findOrFail($id);
        $etudiant->update([
            'nom' => $request->nom,
            'telephone' => $request->telephone,
            'email' => $request->email,
            'id_Classe' => $request->idClasse,
            'date_naissance' => $request->dateNais
        ]);
        
        return response()->json(['success' => true, 'message' => 'Étudiant mis à jour avec succès']);
    }

    public function store(Request $request)
    {

        $request->validate([
            'nom' => 'required|string|max:255',
            'telephone' => 'required|string|max:11|unique:etudiants,telephone',
            'email'=>'required'
        ]);
        // Génération d'un mot de passe en clair puis hachage pour stockage
        $plainPassword = Str::random(8);
        $password = Hash::make($plainPassword);

        // Génération d'un login lisible (nom + matricule si fourni) et garantissant l'unicité
        $base = strtolower(preg_replace('/\s+/', '', explode(' ', $request->nom)[0] ?? 'etudiant'));
        if (!empty($request->matriule)) {
            $base .= preg_replace('/\s+/', '', strtolower($request->matriule));
        }
        if (empty($base)) {
            $base = 'etudiant';
        }

        $login = $base;
        $i = 1;
        while (Etudiant::where('login', $login)->exists()) {
            $login = $base . $i;
            $i++;
        }

        Etudiant::create([
            'matricule' => $request->matriule ,
            'nom' => $request->nom,
            'telephone' => $request->telephone,
            'email' => $request->email ,
            'password' => $password ,
            'login' => $login ,
            'id_Classe' => $request->idClasse ,
            'date_naissance' =>$request->dateNais ,

        ]);

        // Retourner les informations de connexion générées (mot de passe en clair dans le flash)
        return redirect()->route('departement.')->with('success', 'Étudiant créé avec succès !')->with([
            'generated_login' => $login,
            'generated_password' => $plainPassword,
        ]);
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
            'nbre_Elv' => 'required|integer|min:1',
        ]);

        Classe::create([
            'lib_Classe' => $request->lib_Classe,
            'nbre_Elv' => $request->nbre_Elv,
            'id_Filiere' => $request->filiere_id,
        ]);

        return redirect()->route('departement.filieres')->with('success', 'Classe créée avec succès !');
    }

    public function updateClasse(Request $request, $id)
    {
        $request->validate([
            'lib_Classe' => 'required|string|max:255',
            'nbre_Elv' => 'required|integer|min:1',
        ]);

        $classe = Classe::findOrFail($id);
        $classe->update([
            'lib_Classe' => $request->lib_Classe,
            'nbre_Elv' => $request->nbre_Elv,
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