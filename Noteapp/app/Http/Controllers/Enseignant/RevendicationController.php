<?php

namespace App\Http\Controllers\Enseignant;

use App\Http\Controllers\Controller;
use App\Models\Revendication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RevendicationController extends Controller
{
    public function index()
    {
        $enseignant = Auth::guard('enseignant')->user();

        $revendications = Revendication::whereHas('evaluation.ue', function ($query) use ($enseignant) {
            $query->where('id_Enseignant', $enseignant->id_Enseignant);
        })
        ->with(['etudiant', 'evaluation.ue'])
        ->orderBy('created_at', 'desc')
        ->get();

        $stats = [
            'en_attente' => $revendications->where('statut', 'en attente')->count(),
            'traitees' => $revendications->where('statut', '!=', 'en attente')->count(),
        ];

        return view('enseignant.revendications', compact('revendications', 'stats'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'statut' => 'required|in:traitée,rejetée',
            'reponse_enseignant' => 'required|string|max:1000',
        ]);

        $enseignant = Auth::guard('enseignant')->user();

        $revendication = Revendication::where('id_Revendication', $id)
            ->whereHas('evaluation.ue', function ($query) use ($enseignant) {
                $query->where('id_Enseignant', $enseignant->id_Enseignant);
            })
            ->firstOrFail();

        $revendication->update([
            'statut' => $request->statut,
            'reponse_enseignant' => $request->reponse_enseignant,
        ]);

        return redirect()->route('enseignant.revendications')->with('success', 'Revendication traitée avec succès.');
    }
}
