<?php

namespace App\Http\Controllers\Enseignant;

use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Models\Evaluation;
use App\Models\Etudiant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    public function index()
    {
        $etudiants = Etudiant::all();
        return view('enseignant.saisie_notes', compact('etudiants'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'notes' => 'required|array',
            'notes.*' => 'nullable|numeric|min:0|max:20',
            'etudiants' => 'required|array',
        ]);

        $enseignantId = Auth::id();
        $evaluationId = $request->input('evaluation_id', 1); // À adapter selon la sélection

        foreach ($request->etudiants as $key => $etudiantId) {
            if (isset($request->notes[$key]) && $request->notes[$key] !== '') {
                Note::updateOrCreate(
                    [
                        'id_Etudiant' => $etudiantId,
                        'id_Evaluation' => $evaluationId,
                    ],
                    [
                        'id_Enseignant' => 1,
                        'valeur' => $request->notes[$key],
                        'date_Saisie' => now(),
                    ]
                );
            }
        }

        return redirect()->route('enseignant.saisie')->with('success', 'Notes enregistrées avec succès !');
    }
}