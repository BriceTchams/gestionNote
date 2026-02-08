<?php

namespace App\Http\Controllers\Enseignant;

use App\Http\Controllers\Controller;
use App\Models\Evaluation;
use App\Models\Ue;
use App\Models\Semestre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvaluationController extends Controller
{
    public function index()
    {
        $enseignant = Auth::guard('enseignant')->user();

        // Récupère seulement les évaluations de l'enseignant connecté, triées par semestre
        $evaluations = Evaluation::whereHas('ue', function($query) use ($enseignant) {
            $query->where('id_Enseignant', $enseignant->id_Enseignant);
        })->with(['ue', 'semestre'])
        ->join('semestres', 'evaluations.id_Semestre', '=', 'semestres.id_Semestre')
        ->orderBy('semestres.numero', 'asc')
        ->select('evaluations.*')
        ->get();

        // Récupère seulement les UEs de l'enseignant
        $ues = Ue::where('id_Enseignant', $enseignant->id_Enseignant)
                 ->with('groupe_ue.filiere')
                 ->get();

        // Récupère tous les semestres triés
        $semestres = Semestre::orderBy('numero', 'asc')->get();

        return view('enseignant.evaluation', compact('evaluations', 'ues', 'semestres'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type_Evaluation' => 'required|string|max:255',
            'id_UE' => 'required|integer|exists:ues,id_UE',
            'id_Semestre' => 'required|integer|exists:semestres,id_Semestre',
            'date_Evaluation' => 'required|date',
        ]);

        $enseignant = Auth::guard('enseignant')->user();

        // Vérifier que l'UE appartient bien à l'enseignant
        $ue = Ue::where('id_UE', $request->id_UE)
                ->where('id_Enseignant', $enseignant->id_Enseignant)
                ->firstOrFail();

        $evaluation = Evaluation::create([
            'type_Evaluation' => $request->type_Evaluation,
            'id_UE' => $request->id_UE,
            'id_Semestre' => $request->id_Semestre,
            'date_Evaluation' => $request->date_Evaluation,
            'id_Enseignant' => $enseignant->id_Enseignant,
        ]);

        return redirect()->route('enseignant.evaluation')->with('success', 'Évaluation créée avec succès !');
    }
}
