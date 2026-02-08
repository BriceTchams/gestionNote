<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Evaluation;
use App\Models\Ue;
use Illuminate\Support\Facades\Auth;

class EtudiantController extends Controller
{
    public function dashboard()
    {
        $etudiant = Auth::guard('etudiant')->user();

        // Nombre de revendications de l'étudiant
        $nbRevendications = \App\Models\Revendication::where('id_Etudiant', $etudiant->id_Etudiant)->count();

        // Notes récentes
        $notesRecentes = Note::where('id_Etudiant', $etudiant->id_Etudiant)
            ->with(['evaluation.ue'])
            ->orderBy('date_Saisie', 'desc')
            ->take(5)
            ->get();

        return view('etudiant.dashboard', compact('etudiant', 'nbRevendications', 'notesRecentes'));
    }

    public function notes()
    {
        $etudiant = Auth::guard('etudiant')->user();

        // Récupérer les évaluations qui ont au moins une note pour la classe de l'étudiant
        // OU qui sont rattachées à la classe (au cas où on veut voir les évaluations sans notes)
        $evaluations = Evaluation::whereHas('ue.groupe_ue.filiere.classes', function($query) use ($etudiant) {
            $query->where('id_Classe', $etudiant->id_Classe);
        })
        ->orWhereHas('notes.etudiant', function($query) use ($etudiant) {
            $query->where('id_Classe', $etudiant->id_Classe);
        })
        ->with(['ue', 'semestre'])
        ->get();

        // Récupérer les notes de l'étudiant pour ces évaluations
        $notes = Note::where('id_Etudiant', $etudiant->id_Etudiant)
            ->whereIn('id_Evaluation', $evaluations->pluck('id_Evaluation'))
            ->get()
            ->keyBy('id_Evaluation');

        // Récupérer le dernier PV publié pour chaque semestre pour la filière de l'étudiant
        $pvs = \App\Models\ProcesVerbal::where('id_Filiere', $etudiant->classe->id_Filiere)
            ->where('statut', 'Publié')
            ->with(['semestre.anneeAcademique'])
            ->orderBy('id_Semestre')
            ->orderBy('date_Generation', 'desc')
            ->get()
            ->groupBy('id_Semestre')
            ->map(function ($group) {
                return $group->first();
            });

        return view('etudiant.notes', compact('etudiant', 'evaluations', 'notes', 'pvs'));
    }

    public function revendications()
    {
        $etudiant = Auth::guard('etudiant')->user();

        $revendications = \App\Models\Revendication::where('id_Etudiant', $etudiant->id_Etudiant)
            ->with(['evaluation.ue', 'evaluation.ue.enseignant'])
            ->orderBy('created_at', 'desc')
            ->get();

        $stats = [
            'en_attente' => $revendications->where('statut', 'en attente')->count(),
            'approuvees' => $revendications->where('statut', 'traitée')->count(),
            'rejetees' => $revendications->where('statut', 'rejetée')->count(),
        ];

        // Pour le formulaire de création : on récupère les évaluations où l'étudiant a une note
        $evaluations = Evaluation::whereHas('notes', function($query) use ($etudiant) {
            $query->where('id_Etudiant', $etudiant->id_Etudiant);
        })
        ->with(['ue'])
        ->get();

        return view('etudiant.revendications', compact('etudiant', 'revendications', 'stats', 'evaluations'));
    }

    public function storeRevendication(\Illuminate\Http\Request $request)
    {
        // Auto-correction de la base de données si nécessaire
        if (!\Illuminate\Support\Facades\Schema::hasColumn('revendications', 'id_Evaluation')) {
            if (\Illuminate\Support\Facades\Schema::hasColumn('revendications', 'id_Note')) {
                \Illuminate\Support\Facades\Schema::table('revendications', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->renameColumn('id_Note', 'id_Evaluation');
                });
            } else {
                \Illuminate\Support\Facades\Schema::table('revendications', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->foreignId('id_Evaluation')->after('id_Etudiant')->constrained('evaluations', 'id_Evaluation');
                });
            }
        }

        $request->validate([
            'id_Evaluation' => 'required|exists:evaluations,id_Evaluation',
            'message' => 'required|string|max:1000',
        ]);

        $etudiant = Auth::guard('etudiant')->user();

        // Vérifier que l'étudiant a bien une note pour cette évaluation
        $hasNote = Note::where('id_Evaluation', $request->id_Evaluation)
            ->where('id_Etudiant', $etudiant->id_Etudiant)
            ->exists();

        if (!$hasNote) {
            return back()->with('error', 'Vous ne pouvez pas revendiquer pour une évaluation sans note.');
        }

        \App\Models\Revendication::create([
            'id_Etudiant' => $etudiant->id_Etudiant,
            'id_Evaluation' => $request->id_Evaluation,
            'message' => $request->message,
            'statut' => 'en attente',
        ]);

        return redirect()->route('etudiant.revendications')->with('success', 'Votre revendication a été soumise avec succès.');
    }
}
