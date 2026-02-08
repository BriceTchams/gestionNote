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
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\Mime\Email;

class EtudiantController extends Controller
{
    public function index()
    {
        $departementId = auth()->guard('departement')->id();
        $filieres = Filiere::where('id_Departement', $departementId)->get();
        $classes = Classe::whereHas('filiere', function($q) use ($departementId) {
            $q->where('id_Departement', $departementId);
        })->get();

        $etudiants = [];
        return view('departement.etudiants', compact('etudiants', 'filieres', 'classes'));
    }

    public function store(Request $request)
    {
        $departementId = auth()->guard('departement')->id();

        $request->validate([
            'nom' => 'required|string|max:255',
            'telephone' => 'required|string|max:11|unique:etudiants,telephone',
            'email'=>'required',
            'idClasse' => 'required|exists:classes,id_Classe'
        ]);

        // Vérifier que la classe appartient au département
        Classe::whereHas('filiere', function($q) use ($departementId) {
            $q->where('id_Departement', $departementId);
        })->findOrFail($request->idClasse);

        // Génération d'un mot de passe aléatoire
        $plainPassword = Str::random(8);
        $password = Hash::make($plainPassword);

        // Génération automatique du matricule
        do {
            $matricule = 'ETU' . date('Y') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (Etudiant::where('matricule_Et', $matricule)->exists());

        // Génération d'un login de 8 caractères maximum
        $base = strtolower(preg_replace('/\s+/', '', explode(' ', $request->nom)[0] ?? 'etud'));
        // Prendre les 4 premières lettres du nom et ajouter un nombre aléatoire de 4 chiffres
        $base = substr($base, 0, 4);

        $login = $base . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

        // Garantir l'unicité
        while (Etudiant::where('login', $login)->exists()) {
            $login = $base . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        }

        Etudiant::create([
            'matricule_Et' => $matricule ,
            'nom_Complet' => $request->nom,
            'telephone' => $request->telephone,
            'email' => $request->email ,
            'password' => $password ,
            'add_plain_password' => $plainPassword,
            'login' => $login ,
            'id_Classe' => $request->idClasse,
            'date_Naissance' => $request->dateNais,
        ]);

        // Retourner les informations de connexion générées (mot de passe en clair dans le flash)
        return redirect()->route('departement.etudiants')->with('success', 'Étudiant créé avec succès !')->with([
            'generated_login' => $login,
            'generated_password' => $plainPassword,
            'generated_matricule' => $matricule,
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

    public function getByClasse(Request $request)
    {
        $departementId = auth()->guard('departement')->id();
        $classeId = $request->query('classe_id');

        $etudiants = Etudiant::whereHas('classe.filiere', function($q) use ($departementId) {
                $q->where('id_Departement', $departementId);
            })
            ->with(['classe.filiere'])
            ->where('id_Classe', $classeId)
            ->orderBy('nom_Complet', 'asc')
            ->get();

        return response()->json(['etudiants' => $etudiants]);
    }

    public function search(Request $request)
    {
        $departementId = auth()->guard('departement')->id();
        $searchTerm = $request->query('search');
        $classeId = $request->query('classe_id');

        $query = Etudiant::whereHas('classe.filiere', function($q) use ($departementId) {
            $q->where('id_Departement', $departementId);
        })->with(['classe.filiere']);

        if ($classeId) {
            $query->where('id_Classe', $classeId);
        }

        if ($searchTerm) {
            $query->where(function($q) use ($searchTerm) {
                $q->where('nom_Complet', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('matricule_Et', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('login', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('email', 'LIKE', "%{$searchTerm}%");
            });
        }

        $etudiants = $query->orderBy('nom_Complet', 'asc')->get();

        return response()->json(['etudiants' => $etudiants]);
    }

    public function show($id)
    {
        $departementId = auth()->guard('departement')->id();
        $etudiant = Etudiant::whereHas('classe.filiere', function($q) use ($departementId) {
            $q->where('id_Departement', $departementId);
        })->with(['classe.filiere'])->findOrFail($id);

        return response()->json($etudiant);
    }

    public function updateEtudiant(Request $request, $id)
    {
        $departementId = auth()->guard('departement')->id();

        $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telephone' => 'required|string|max:11',
            'idClasse' => 'required|exists:classes,id_Classe'
        ]);

        $etudiant = Etudiant::whereHas('classe.filiere', function($q) use ($departementId) {
            $q->where('id_Departement', $departementId);
        })->findOrFail($id);

        // Vérifier que la nouvelle classe appartient au département
        Classe::whereHas('filiere', function($q) use ($departementId) {
            $q->where('id_Departement', $departementId);
        })->findOrFail($request->idClasse);

        $etudiant->update([
            'nom_Complet' => $request->nom,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'date_Naissance' => $request->dateNais,
            'id_Classe' => $request->idClasse,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Étudiant modifié avec succès !'
        ]);
    }

    public function destroyEtudiant($id)
    {
        $departementId = auth()->guard('departement')->id();
        $etudiant = Etudiant::whereHas('classe.filiere', function($q) use ($departementId) {
            $q->where('id_Departement', $departementId);
        })->findOrFail($id);

        $etudiant->delete();

        return response()->json([
            'success' => true,
            'message' => 'Étudiant supprimé avec succès !'
        ]);
    }

    public function downloadPdf(Request $request)
    {
        $departementId = auth()->guard('departement')->id();
        $classeId = $request->query('classe_id');
        $searchTerm = $request->query('search');

        $query = Etudiant::whereHas('classe.filiere', function($q) use ($departementId) {
            $q->where('id_Departement', $departementId);
        })->with(['classe.filiere']);

        if ($classeId) {
            $query->where('id_Classe', $classeId);
        }

        if ($searchTerm) {
            $query->where(function($q) use ($searchTerm) {
                $q->where('nom_Complet', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('matricule_Et', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('login', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('email', 'LIKE', "%{$searchTerm}%");
            });
        }

        $etudiants = $query->orderBy('nom_Complet', 'asc')->get();

        $classe = null;
        if ($classeId) {
            $classe = Classe::with('filiere')->find($classeId);
        }

        $pdf = Pdf::loadView('departement.etudiants_pdf', [
            'etudiants' => $etudiants,
            'classe' => $classe,
            'date' => now()->format('d/m/Y')
        ]);

        return $pdf->download('liste_etudiants_' . now()->format('Y-m-d') . '.pdf');
    }
}
