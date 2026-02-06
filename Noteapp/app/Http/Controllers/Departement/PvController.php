<?php

namespace App\Http\Controllers\Departement;

use App\Http\Controllers\Controller;
use App\Models\Etudiant;
use App\Models\Note;
use App\Models\Evaluation;
use App\Models\Filiere;
use App\Models\Classe;
use App\Models\Semestre;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PvController extends Controller
{
    public function index()
    {
        $filieres = Filiere::all();
        $semestres = Semestre::all();
        
        return view('departement.pv', compact('filieres', 'semestres'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'filiere_id' => 'required|exists:filieres,id_Filiere',
            'classe_id' => 'required|exists:classes,id_Classe',
            'semestre_id' => 'required|exists:semestres,id_Semestre',
        ]);

        $filiere = Filiere::find($request->filiere_id);
        $classe = Classe::find($request->classe_id);
        $semestre = Semestre::find($request->semestre_id);

        // Récupérer les étudiants de la classe
        $etudiants = Etudiant::where('id_Classe', $request->classe_id)->get();
        
        // Récupérer les évaluations du semestre
        $evaluations = Evaluation::where('id_Semestre', $request->semestre_id)->get();
        
        // Calculer les moyennes pour chaque étudiant
        $resultats = [];
        foreach ($etudiants as $etudiant) {
            $notes = Note::where('id_Etudiant', $etudiant->id_Etudiant)
                ->whereIn('id_Evaluation', $evaluations->pluck('id_Evaluation'))
                ->get();
            
            $totalNotes = 0;
            $nombreNotes = 0;
            
            foreach ($evaluations as $evaluation) {
                $note = $notes->where('id_Evaluation', $evaluation->id_Evaluation)->first();
                if ($note) {
                    $totalNotes += $note->valeur;
                    $nombreNotes++;
                }
            }
            
            $moyenne = $nombreNotes > 0 ? $totalNotes / $nombreNotes : 0;
            
            // Déterminer le statut
            $statut = $this->determinerStatut($moyenne);
            
            $resultats[] = [
                'etudiant' => $etudiant,
                'notes' => $notes,
                'moyenne' => round($moyenne, 2),
                'statut' => $statut,
                'evaluations' => $evaluations
            ];
        }

        // Trier les étudiants par moyenne décroissante
        usort($resultats, function($a, $b) {
            return $b['moyenne'] <=> $a['moyenne'];
        });

        $data = [
            'filiere' => $filiere,
            'classe' => $classe,
            'semestre' => $semestre,
            'resultats' => $resultats,
            'evaluations' => $evaluations,
            'dateGeneration' => now()->format('d/m/Y'),
        ];

        if ($request->has('preview')) {
            return view('departement.pv_template', $data);
        }

        // Générer le PDF
        $pdf = Pdf::loadView('departement.pv_template', $data);

        $nomFiliere = $filiere->nom_Filiere ?? 'filiere';
        $nomClasse = $classe->lib_Classe ?? 'classe';
        $nomSemestre = 'S' . ($semestre->numero ?? 'X');

        return $pdf->download('pv_' . $nomFiliere . '_' . $nomClasse . '_' . $nomSemestre . '.pdf');
    }

    private function determinerStatut($moyenne)
    {
        if ($moyenne >= 10) {
            return 'Admis';
        } elseif ($moyenne >= 7) {
            return 'Rattrapage';
        } else {
            return 'Ajourné';
        }
    }

    public function getClasses(Request $request)
    {
        $classes = Classe::where('id_Filiere', $request->filiere_id)->get();
        return response()->json($classes);
    }
}