<?php

namespace App\Http\Controllers\Enseignant;

use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Models\Evaluation;
use App\Models\Etudiant;
use App\Models\Filiere;
use App\Models\Classe;
use App\Models\Ue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotePublieeMail;
use Barryvdh\DomPDF\Facade\Pdf;

class NoteController extends Controller
{
    public function index(Request $request)
    {
        $enseignant = Auth::guard('enseignant')->user();

        // Récupérer les UEs de l'enseignant avec leurs filières
        $ues = Ue::where('id_Enseignant', $enseignant->id_Enseignant)
                 ->with('groupe_ue.filiere')
                 ->get();

        // Récupérer les filières uniques liées aux UEs de l'enseignant
        $filieres = $ues->pluck('groupe_ue.filiere')->unique('id_Filiere')->filter();

        // Initialiser les variables
        $classes = collect();
        $evaluations = collect();
        $etudiants = collect();
        $selectedFiliere = null;
        $selectedClasse = null;
        $selectedEvaluation = null;

        // Si une filière est sélectionnée
        if ($request->has('filiere_id')) {
            $selectedFiliere = $request->filiere_id;
            $classes = Classe::where('id_Filiere', $selectedFiliere)->get();
        }

        // Si une classe est sélectionnée
        if ($request->has('classe_id')) {
            $selectedClasse = $request->classe_id;
            $etudiants = Etudiant::where('id_Classe', $selectedClasse)->orderBy('nom_Complet', 'asc')->get();
        }

        // Si une UE est sélectionnée, récupérer les évaluations
        if ($request->has('ue_id')) {
            $evaluations = Evaluation::where('id_UE', $request->ue_id)
                                     ->with('semestre')
                                     ->get();
        }

        // Si une évaluation est sélectionnée, récupérer les notes déjà saisies
        $existingNotes = collect();
        if ($request->has('evaluation_id')) {
            $selectedEvaluation = $request->evaluation_id;
            $existingNotes = Note::where('id_Evaluation', $selectedEvaluation)
                                 ->whereIn('id_Etudiant', $etudiants->pluck('id_Etudiant'))
                                 ->get()
                                 ->pluck('valeur', 'id_Etudiant');
        }

        return view('enseignant.saisie_notes', compact(
            'filieres',
            'classes',
            'ues',
            'evaluations',
            'etudiants',
            'selectedFiliere',
            'selectedClasse',
            'selectedEvaluation',
            'existingNotes'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'notes' => 'required|array',
            'notes.*' => 'nullable|numeric|min:0|max:20',
            'evaluation_id' => 'required|exists:evaluations,id_Evaluation',
        ]);

        $enseignantId = Auth::guard('enseignant')->user()->id_Enseignant;
        $evaluationId = $request->input('evaluation_id');

        foreach ($request->notes as $etudiantId => $noteValue) {
            if ($noteValue !== null && $noteValue !== '') {
                Note::updateOrCreate(
                    [
                        'id_Etudiant' => $etudiantId,
                        'id_Evaluation' => $evaluationId,
                    ],
                    [
                        'id_Enseignant' => $enseignantId,
                        'valeur' => $noteValue,
                        'date_Saisie' => now(),
                    ]
                );
            }
        }

        return redirect()->route('enseignant.saisie')->with('success', 'Notes enregistrées avec succès !');
    }

    public function downloadPdf(Request $request)
    {
        $request->validate([
            'filiere_id' => 'required|exists:filieres,id_Filiere',
            'classe_id' => 'required|exists:classes,id_Classe',
            'ue_id' => 'required|exists:ues,id_UE',
            'evaluation_id' => 'required|exists:evaluations,id_Evaluation',
        ]);

        $filiere = Filiere::findOrFail($request->filiere_id);
        $classe = Classe::findOrFail($request->classe_id);
        $ue = Ue::with('enseignant')->findOrFail($request->ue_id);
        $evaluation = Evaluation::with('semestre.anneeAcademique')->findOrFail($request->evaluation_id);

        // Récupérer l'enseignant : soit l'utilisateur connecté (si enseignant), soit l'enseignant de l'UE
        $enseignant = Auth::guard('enseignant')->user() ?: $ue->enseignant;

        $etudiants = Etudiant::where('id_Classe', $request->classe_id)->orderBy('nom_Complet', 'asc')->get();
        $notes = Note::where('id_Evaluation', $request->evaluation_id)
                     ->whereIn('id_Etudiant', $etudiants->pluck('id_Etudiant'))
                     ->get()
                     ->pluck('valeur', 'id_Etudiant');

        $data = [
            'filiere' => $filiere,
            'classe' => $classe,
            'ue' => $ue,
            'evaluation' => $evaluation,
            'etudiants' => $etudiants,
            'notes' => $notes,
            'enseignant' => $enseignant,
            'date' => now()->format('d/m/Y'),
        ];

        $pdf = Pdf::loadView('enseignant.notes_pdf', $data);

        return $pdf->download('notes_' . $ue->code . '_' . $evaluation->type_Evaluation . '.pdf');
    }
}
