<?php

namespace App\Http\Controllers\Departement;

use App\Http\Controllers\Controller;
use App\Models\Filiere;
use App\Models\Classe;
use App\Models\Enseignant;
use App\Models\Etudiant;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $departement = auth()->guard('departement')->user();

        if (!$departement) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter en tant que département.');
        }

        $stats = [
            'filieres_count' => Filiere::where('id_Departement', $departement->id_Departement)->count(),
            'classes_count' => Classe::whereHas('filiere', function($q) use ($departement) {
                $q->where('id_Departement', $departement->id_Departement);
            })->count(),
            'enseignants_count' => Enseignant::where('id_Departement', $departement->id_Departement)->count(),
            'etudiants_count' => Etudiant::whereHas('classe.filiere', function($q) use ($departement) {
                $q->where('id_Departement', $departement->id_Departement);
            })->count(),
            'ues_count' => \App\Models\Ue::whereHas('groupe_ue.filiere', function($q) use ($departement) {
                $q->where('id_Departement', $departement->id_Departement);
            })->count(),
            'groupes_count' => \App\Models\GroupeUe::whereHas('filiere', function($q) use ($departement) {
                $q->where('id_Departement', $departement->id_Departement);
            })->count(),
        ];

        // Répartition par filière pour le graphique
        $repartition = Filiere::where('id_Departement', $departement->id_Departement)
            ->withCount('etudiants')
            ->get();

        return view('departement.dashboard', compact('departement', 'stats', 'repartition'));
    }
}
