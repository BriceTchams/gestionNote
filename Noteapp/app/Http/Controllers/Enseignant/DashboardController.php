<?php

namespace App\Http\Controllers\Enseignant;

use App\Http\Controllers\Controller;
use App\Models\Etudiant;
use App\Models\Ue;
use App\Models\Evaluation;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $enseignant = Auth::guard('enseignant')->user();

        // Matières (UEs) de l'enseignant
        $ues = Ue::where('id_Enseignant', $enseignant->id_Enseignant)
                 ->with(['groupe_ue.filiere'])
                 ->get();

        $nbMatieres = $ues->count();

        // Nombre d'évaluations
        $nbEvaluations = Evaluation::whereHas('ue', function($query) use ($enseignant) {
            $query->where('id_Enseignant', $enseignant->id_Enseignant);
        })->count();

        // Classes uniques
        $filiereIds = $ues->pluck('groupe_ue.id_Filiere')->unique()->filter();

        // On récupère les classes liées à ces filières
        $classes = \App\Models\Classe::whereIn('id_Filiere', $filiereIds)->get();
        $nbClasses = $classes->count();

        // Nombre d'étudiants total dans ces classes
        $nbEtudiants = Etudiant::whereIn('id_Classe', $classes->pluck('id_Classe'))->count();

        // Revendications
        $nbRevendications = \App\Models\Revendication::whereHas('evaluation.ue', function($query) use ($enseignant) {
            $query->where('id_Enseignant', $enseignant->id_Enseignant);
        })->where('statut', 'en attente')->count();

        // Revendications en attente (les 3 dernières)
        $dernieresRevendications = \App\Models\Revendication::whereHas('evaluation.ue', function($query) use ($enseignant) {
            $query->where('id_Enseignant', $enseignant->id_Enseignant);
        })->where('statut', 'en attente')
          ->with(['etudiant', 'evaluation.ue'])
          ->take(3)
          ->get();

        return view('enseignant.dashboard', compact(
            'enseignant',
            'ues',
            'nbMatieres',
            'nbClasses',
            'nbEtudiants',
            'nbRevendications',
            'nbEvaluations',
            'dernieresRevendications'
        ));
    }
}
