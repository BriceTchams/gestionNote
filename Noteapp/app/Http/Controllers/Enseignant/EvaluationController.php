<?php

namespace App\Http\Controllers\Enseignant;

use App\Http\Controllers\Controller;
use App\Models\Evaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvaluationController extends Controller
{
    public function index()
    {
        // Récupère toutes les évaluations sans charger les relations ues et semestre
        $evaluations = Evaluation::all(); 
        
        return view('enseignant.evaluation', compact('evaluations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type_Evaluation' => 'required|string|max:255',
            'id_UE' => 'required|integer|exists:ues,id_UE',
            'id_Semestre' => 'required|integer|exists:semestres,id_Semestre',
            'date_Evaluation' => 'required|date',
        ]);

        $evaluation = Evaluation::create([
            'type_Evaluation' => $request->type_Evaluation,
            'id_UE' => $request->id_UE,
            'id_Semestre' => $request->id_Semestre,
            'date_Evaluation' => $request->date_Evaluation,
        ]);

        return redirect()->route('enseignant.evaluation')->with('success', 'Évaluation créée avec succès !');
    }
}