<?php

namespace App\Http\Controllers\Departement;

use App\Http\Controllers\Controller;
use App\Models\Enseignant;
use App\Models\Departement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Str;

class EnseignantController extends Controller
{
    public function index()
    {
        $departementId = auth()->guard('departement')->id();
        if (!$departementId) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter.');
        }
        $enseignants = Enseignant::where('id_Departement', $departementId)
            ->with(['departement', 'ues'])
            ->get();
        $departement = Departement::find($departementId);

        // Statistiques
        $stats = [
            'count' => $enseignants->count(),
            'total_ues' => $enseignants->sum(function($e) { return $e->ues->count(); }),
            'avg_ues' => $enseignants->count() > 0 ? round($enseignants->sum(function($e) { return $e->ues->count(); }) / $enseignants->count(), 1) : 0
        ];

        return view('departement.enseignants', compact('enseignants', 'departement', 'stats'));
    }

    public function store(Request $request)
    {
        $departementId = auth()->guard('departement')->id();
        if (!$departementId) {
            return redirect()->route('login');
        }

        $request->validate([
            'nom_Enseignant' => 'required|string|max:255',
            'email' => 'required|email|unique:enseignants,email',
        ]);

        // Génération d'un mot de passe aléatoire
        $plainPassword = Str::random(8);
        $password = Hash::make($plainPassword);

        // Génération automatique du matricule
        do {
            $matricule = 'ENS' . date('Y') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (Enseignant::where('matricule', $matricule)->exists());

        // Génération d'un login à partir du nom
        $base = strtolower(preg_replace('/\s+/', '', explode(' ', $request->nom_Enseignant)[0] ?? 'ens'));
        $base = substr($base, 0, 4);

        do {
            $login = $base . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (Enseignant::where('login', $login)->exists());

        $departementId = auth()->guard('departement')->id() ?? 1;

        Enseignant::create([
            'nom_Enseignant' => $request->nom_Enseignant,
            'email' => $request->email,
            'matricule' => $matricule,
            'login' => $login,
            'password' => $password,
            'add_plain_password' => $plainPassword,
            'id_Departement' => $departementId,
        ]);

        return redirect()->route('departement.enseignants')->with('success', 'Enseignant ajouté avec succès !')->with([
            'generated_login' => $login,
            'generated_password' => $plainPassword,
            'generated_matricule' => $matricule,
        ]);
    }

    public function update(Request $request, $id)
    {
        $departementId = auth()->guard('departement')->id();
        $enseignant = Enseignant::where('id_Departement', $departementId)->findOrFail($id);

        $request->validate([
            'nom_Enseignant' => 'required|string|max:255',
            'email' => 'required|email|unique:enseignants,email,' . $id . ',id_Enseignant',
        ]);

        $enseignant->update([
            'nom_Enseignant' => $request->nom_Enseignant,
            'email' => $request->email,
        ]);

        return redirect()->route('departement.enseignants')->with('success', 'Enseignant mis à jour avec succès !');
    }

    public function destroy($id)
    {
        $departementId = auth()->guard('departement')->id();
        $enseignant = Enseignant::where('id_Departement', $departementId)->findOrFail($id);
        $enseignant->delete();

        return redirect()->route('departement.enseignants')->with('success', 'Enseignant supprimé avec succès !');
    }
}
