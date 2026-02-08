<?php

namespace App\Http\Controllers\Departement;

use App\Http\Controllers\Controller;
use App\Models\Etudiant;
use App\Models\Note;
use App\Models\Evaluation;
use App\Models\Filiere;
use App\Models\Classe;
use App\Models\Semestre;
use App\Models\Ue;
use App\Models\GroupeUe;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\ResultatsPubliesMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class PvController extends Controller
{
    public function index()
    {
        $departementId = auth()->guard('departement')->id();
        if (!$departementId) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter.');
        }
        $filieres = Filiere::where('id_Departement', $departementId)->get();
        $semestres = Semestre::with('anneeAcademique')->get();

        return view('departement.pv', compact('filieres', 'semestres'));
    }

    public function generate(Request $request)
    {
        $departementId = auth()->guard('departement')->id();

        $request->validate([
            'filiere_id' => 'required|exists:filieres,id_Filiere',
            'classe_id' => 'required|exists:classes,id_Classe',
            'semestre_id' => 'required|exists:semestres,id_Semestre',
        ]);

        $filiere = Filiere::where('id_Departement', $departementId)->findOrFail($request->filiere_id);
        $classe = Classe::whereHas('filiere', function($q) use ($departementId) {
            $q->where('id_Departement', $departementId);
        })->findOrFail($request->classe_id);

        $semestre = Semestre::with('anneeAcademique')->findOrFail($request->semestre_id);

        // Récupérer les groupes d'UE de la filière avec leurs UEs
        $groupesUe = GroupeUe::where('id_Filiere', $request->filiere_id)
            ->with(['ues' => function($q) {
                $q->orderBy('libelle');
            }])
            ->get();

        $ueIds = $groupesUe->pluck('ues')->flatten()->pluck('id_UE');

        // Récupérer les évaluations du semestre pour ces UEs
        $evaluations = Evaluation::where('id_Semestre', $request->semestre_id)
            ->whereIn('id_UE', $ueIds)
            ->get();

        // Récupérer les étudiants de la classe
        $etudiants = Etudiant::where('id_Classe', $request->classe_id)->get();

        $resultats = [];
        foreach ($etudiants as $etudiant) {
            $notesEtudiant = Note::where('id_Etudiant', $etudiant->id_Etudiant)
                ->whereIn('id_Evaluation', $evaluations->pluck('id_Evaluation'))
                ->get();

            $detailGroupes = [];
            $totalMoyennesPonderees = 0;
            $totalCreditsSemestre = 0;
            $creditsValides = 0;
            $toutesUesValidees = true;

            foreach ($groupesUe as $groupe) {
                $totalMoyennesPondereesGroupe = 0;
                $totalCreditsGroupe = 0;
                $detailUes = [];

                foreach ($groupe->ues as $ue) {
                    $noteCC = $notesEtudiant->where('id_Evaluation', $evaluations->where('id_UE', $ue->id_UE)->where('type_Evaluation', 'CC')->first()?->id_Evaluation)->first()?->valeur ?? 0;
                    $noteExamen = $notesEtudiant->where('id_Evaluation', $evaluations->where('id_UE', $ue->id_UE)->where('type_Evaluation', 'Examen')->first()?->id_Evaluation)->first()?->valeur ?? 0;
                    $noteRattrapage = $notesEtudiant->where('id_Evaluation', $evaluations->where('id_UE', $ue->id_UE)->where('type_Evaluation', 'Rattrapage')->first()?->id_Evaluation)->first()?->valeur ?? null;

                    // Calcul 30/70
                    $noteFinale = ($noteCC * 0.3) + ($noteExamen * 0.7);

                    // Si rattrapage existe, il remplace l'examen si meilleur (ou règle standard)
                    if ($noteRattrapage !== null) {
                        $noteFinaleRattrapage = ($noteCC * 0.3) + ($noteRattrapage * 0.7);
                        if ($noteFinaleRattrapage > $noteFinale) {
                            $noteFinale = $noteFinaleRattrapage;
                        }
                    }

                    $statutUE = $noteFinale >= 10 ? 'V' : 'NV';
                    if ($statutUE === 'NV') $toutesUesValidees = false;

                    $detailUes[] = [
                        'ue' => $ue,
                        'cc' => $noteCC,
                        'examen' => $noteExamen,
                        'rattrapage' => $noteRattrapage,
                        'finale' => round($noteFinale, 2),
                        'statut' => $statutUE
                    ];

                    $totalMoyennesPondereesGroupe += ($noteFinale * $ue->credits);
                    $totalCreditsGroupe += $ue->credits;
                }

                $moyenneGroupe = $totalCreditsGroupe > 0 ? $totalMoyennesPondereesGroupe / $totalCreditsGroupe : 0;
                $decisionGroupe = $moyenneGroupe >= 10 ? 'Validé' : 'Non Validé';

                // Gérer la compensation (VC)
                foreach ($detailUes as &$item) {
                    if ($item['statut'] === 'NV' && $moyenneGroupe >= 10) {
                        $item['statut'] = 'VC';
                    }

                    if ($item['statut'] === 'V' || $item['statut'] === 'VC') {
                        $creditsValides += $item['ue']->credits;
                    }
                }

                $detailGroupes[] = [
                    'groupe' => $groupe,
                    'ues' => $detailUes,
                    'moyenne' => round($moyenneGroupe, 2),
                    'decision' => $decisionGroupe,
                    'totalCredits' => $totalCreditsGroupe
                ];

                $totalMoyennesPonderees += $totalMoyennesPondereesGroupe;
                $totalCreditsSemestre += $totalCreditsGroupe;
            }

            $moyenneSemestre = $totalCreditsSemestre > 0 ? $totalMoyennesPonderees / $totalCreditsSemestre : 0;

            $resultats[] = [
                'etudiant' => $etudiant,
                'groupes' => $detailGroupes,
                'moyenne' => round($moyenneSemestre, 2),
                'credits_valides' => $creditsValides,
                'statut' => $this->determinerStatut($moyenneSemestre),
                'toutesUesValidees' => $toutesUesValidees
            ];
        }

        // Calculer la moyenne des étudiants ayant tout validé
        $admisAyantToutValide = collect($resultats)->where('toutesUesValidees', true);
        $moyenneAdmisToutValide = $admisAyantToutValide->count() > 0 ? $admisAyantToutValide->avg('moyenne') : 0;

        // Trier les étudiants par ordre alphabétique
        usort($resultats, function($a, $b) {
            return strcasecmp($a['etudiant']->nom_Complet, $b['etudiant']->nom_Complet);
        });

        $isRattrapageRequest = $request->input('session_type') === 'rattrapage';

        $data = [
            'filiere' => $filiere,
            'classe' => $classe,
            'semestre' => $semestre,
            'resultats' => $resultats,
            'groupesUe' => $groupesUe,
            'dateGeneration' => now()->format('d/m/Y'),
            'isRattrapage' => $isRattrapageRequest,
            'moyenneAdmisToutValide' => round($moyenneAdmisToutValide, 2)
        ];

        if ($request->has('preview')) {
            return view('departement.pv_template', $data);
        }

        // Sauvegarder le PV dans l'historique
        $this->savePvToHistory($data);

        // Envoyer les mails aux étudiants
        $mailsEnvoyes = 0;
        foreach ($data['resultats'] as $resultat) {
            if ($resultat['etudiant']->email) {
                Mail::to($resultat['etudiant']->email)->send(new ResultatsPubliesMail($resultat, $data));
                $mailsEnvoyes++;
            }
        }

        session()->flash('success', "Le PV a été généré avec succès et $mailsEnvoyes emails de notification ont été envoyés aux étudiants.");

        $pdf = Pdf::loadView('departement.pv_template', $data)->setPaper('a4', 'landscape');

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

    private function savePvToHistory($data)
    {
        \DB::transaction(function() use ($data) {
            $pv = \App\Models\ProcesVerbal::create([
                'id_Filiere' => $data['filiere']->id_Filiere,
                'id_Semestre' => $data['semestre']->id_Semestre,
                'id_Annee' => $data['semestre']->id_Annee,
                'date_Generation' => now(),
                'statut' => 'Publié',
                'moyenne_Generale_Classe' => collect($data['resultats'])->avg('moyenne')
            ]);

            foreach ($data['resultats'] as $res) {
                \App\Models\DetailPv::create([
                    'id_PV' => $pv->id_PV,
                    'id_Etudiant' => $res['etudiant']->id_Etudiant,
                    'moyenne_Etudiant' => $res['moyenne'],
                    'credits_valides' => $res['credits_valides'],
                    'mention' => $this->obtenirMention($res['moyenne']),
                    'decision' => $res['statut']
                ]);
            }
        });
    }

    private function obtenirMention($moyenne)
    {
        if ($moyenne >= 16) return 'Très Bien';
        if ($moyenne >= 14) return 'Bien';
        if ($moyenne >= 12) return 'Assez Bien';
        if ($moyenne >= 10) return 'Passable';
        return 'Insuffisant';
    }

    public function history()
    {
        $departementId = auth()->guard('departement')->id();
        $pvs = \App\Models\ProcesVerbal::with(['filiere', 'semestre.anneeAcademique'])
            ->whereHas('filiere', function($q) use ($departementId) {
                $q->where('id_Departement', $departementId);
            })
            ->orderBy('date_Generation', 'desc')
            ->get();

        return view('departement.pv_history', compact('pvs'));
    }

    public function getClasses(Request $request)
    {
        $departementId = auth()->guard('departement')->id();
        $classes = Classe::whereHas('filiere', function($q) use ($departementId) {
            $q->where('id_Departement', $departementId);
        })->where('id_Filiere', $request->filiere_id)->get();

        return response()->json($classes);
    }

    public function downloadExistingPv(Request $request)
    {
        $request->validate([
            'pv_id' => 'required|exists:proces_verbals,id_PV',
        ]);

        $pv = \App\Models\ProcesVerbal::with(['filiere', 'semestre.anneeAcademique'])->findOrFail($request->pv_id);
        $filiere = $pv->filiere;
        $semestre = $pv->semestre;

        // On doit retrouver la classe concernée.
        // Note: Le PV semestriel dans ce projet semble être par Filière et Classe (basé sur generate)
        // Mais ProcesVerbal ne stocke pas id_Classe.
        // On va essayer de déduire la classe via l'étudiant si c'est un étudiant qui demande
        // Ou via les paramètres si fournis.

        $classeId = $request->classe_id;
        if (!$classeId && Auth::guard('etudiant')->check()) {
            $classeId = Auth::guard('etudiant')->user()->id_Classe;
        }

        if (!$classeId) {
            return back()->with('error', 'Classe non spécifiée.');
        }

        $classe = Classe::findOrFail($classeId);

        // Régénérer les données pour le template (identique à generate)
        $groupesUe = GroupeUe::where('id_Filiere', $filiere->id_Filiere)
            ->with(['ues' => function($q) {
                $q->orderBy('libelle');
            }])
            ->get();

        $ueIds = $groupesUe->pluck('ues')->flatten()->pluck('id_UE');
        $evaluations = Evaluation::where('id_Semestre', $semestre->id_Semestre)
            ->whereIn('id_UE', $ueIds)
            ->get();

        $etudiants = Etudiant::where('id_Classe', $classe->id_Classe)->orderBy('nom_Complet', 'asc')->get();

        $resultats = [];
        foreach ($etudiants as $etudiant) {
            $notesEtudiant = Note::where('id_Etudiant', $etudiant->id_Etudiant)
                ->whereIn('id_Evaluation', $evaluations->pluck('id_Evaluation'))
                ->get();

            $detailGroupes = [];
            $totalMoyennesPonderees = 0;
            $totalCreditsSemestre = 0;
            $creditsValides = 0;
            $toutesUesValidees = true;

            foreach ($groupesUe as $groupe) {
                $totalMoyennesPondereesGroupe = 0;
                $totalCreditsGroupe = 0;
                $detailUes = [];

                foreach ($groupe->ues as $ue) {
                    $noteCC = $notesEtudiant->where('id_Evaluation', $evaluations->where('id_UE', $ue->id_UE)->where('type_Evaluation', 'CC')->first()?->id_Evaluation)->first()?->valeur ?? 0;
                    $noteExamen = $notesEtudiant->where('id_Evaluation', $evaluations->where('id_UE', $ue->id_UE)->where('type_Evaluation', 'Examen')->first()?->id_Evaluation)->first()?->valeur ?? 0;
                    $noteRattrapage = $notesEtudiant->where('id_Evaluation', $evaluations->where('id_UE', $ue->id_UE)->where('type_Evaluation', 'Rattrapage')->first()?->id_Evaluation)->first()?->valeur ?? null;

                    $noteFinale = ($noteCC * 0.3) + ($noteExamen * 0.7);
                    if ($noteRattrapage !== null) {
                        $noteFinaleRattrapage = ($noteCC * 0.3) + ($noteRattrapage * 0.7);
                        if ($noteFinaleRattrapage > $noteFinale) {
                            $noteFinale = $noteFinaleRattrapage;
                        }
                    }

                    $statutUE = $noteFinale >= 10 ? 'V' : 'NV';
                    if ($statutUE === 'NV') $toutesUesValidees = false;

                    $detailUes[] = [
                        'ue' => $ue,
                        'cc' => $noteCC,
                        'examen' => $noteExamen,
                        'rattrapage' => $noteRattrapage,
                        'finale' => round($noteFinale, 2),
                        'statut' => $statutUE
                    ];

                    $totalMoyennesPondereesGroupe += ($noteFinale * $ue->credits);
                    $totalCreditsGroupe += $ue->credits;
                }

                $moyenneGroupe = $totalCreditsGroupe > 0 ? $totalMoyennesPondereesGroupe / $totalCreditsGroupe : 0;
                $decisionGroupe = $moyenneGroupe >= 10 ? 'Validé' : 'Non Validé';

                foreach ($detailUes as &$item) {
                    if ($item['statut'] === 'NV' && $moyenneGroupe >= 10) {
                        $item['statut'] = 'VC';
                    }
                    if ($item['statut'] === 'V' || $item['statut'] === 'VC') {
                        $creditsValides += $item['ue']->credits;
                    }
                }

                $detailGroupes[] = [
                    'groupe' => $groupe,
                    'ues' => $detailUes,
                    'moyenne' => round($moyenneGroupe, 2),
                    'decision' => $decisionGroupe,
                    'totalCredits' => $totalCreditsGroupe
                ];

                $totalMoyennesPonderees += $totalMoyennesPondereesGroupe;
                $totalCreditsSemestre += $totalCreditsGroupe;
            }

            $moyenneSemestre = $totalCreditsSemestre > 0 ? $totalMoyennesPonderees / $totalCreditsSemestre : 0;

            $resultats[] = [
                'etudiant' => $etudiant,
                'groupes' => $detailGroupes,
                'moyenne' => round($moyenneSemestre, 2),
                'credits_valides' => $creditsValides,
                'statut' => $this->determinerStatut($moyenneSemestre),
                'toutesUesValidees' => $toutesUesValidees
            ];
        }

        $admisAyantToutValide = collect($resultats)->where('toutesUesValidees', true);
        $moyenneAdmisToutValide = $admisAyantToutValide->count() > 0 ? $admisAyantToutValide->avg('moyenne') : 0;

        usort($resultats, function($a, $b) {
            return $b['moyenne'] <=> $a['moyenne'];
        });

        $isRattrapage = Evaluation::where('id_Semestre', $semestre->id_Semestre)
            ->whereIn('id_UE', $ueIds)
            ->where('type_Evaluation', 'Rattrapage')
            ->exists();

        $data = [
            'filiere' => $filiere,
            'classe' => $classe,
            'semestre' => $semestre,
            'resultats' => $resultats,
            'groupesUe' => $groupesUe,
            'dateGeneration' => \Carbon\Carbon::parse($pv->date_Generation)->format('d/m/Y'),
            'isRattrapage' => $isRattrapage,
            'moyenneAdmisToutValide' => round($moyenneAdmisToutValide, 2)
        ];

        $pdf = Pdf::loadView('departement.pv_template', $data)->setPaper('a4', 'landscape');

        return $pdf->download('pv_' . ($filiere->nom_Filiere ?? 'filiere') . '_' . ($classe->lib_Classe ?? 'classe') . '.pdf');
    }

    public function ueIndex(Request $request)
    {
        $departementId = auth()->guard('departement')->id();
        if (!$departementId) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter.');
        }

        $filieres = Filiere::where('id_Departement', $departementId)->get();
        $filiereId = $request->query('filiere_id');

        $groupes = collect();
        $ues = collect();

        if ($filiereId) {
            // Vérifier que la filière appartient au département
            $filiere = Filiere::where('id_Departement', $departementId)->findOrFail($filiereId);
            $groupes = GroupeUe::where('id_Filiere', $filiereId)->with('ues.enseignant')->get();
        }

        $enseignants = \App\Models\Enseignant::where('id_Departement', $departementId)->get();

        if ($request->ajax()) {
            return response()->json([
                'groupes' => $groupes,
                'filiere' => $filiere ?? null
            ]);
        }

        $groupes_all = GroupeUe::whereIn('id_Filiere', $filieres->pluck('id_Filiere'))->get();

        return view('departement.ues', compact('filieres', 'groupes', 'ues', 'enseignants', 'groupes_all'));
    }

    public function storeUe(Request $request)
    {
        $departementId = auth()->guard('departement')->id();

        $request->validate([
            'code' => 'required|unique:ues,code',
            'libelle' => 'required',
            'credits' => 'required|integer',
            'idGroupe' => 'required|exists:groupe_ue,idGroupe',
            'id_Enseignant' => 'required|exists:enseignants,id_Enseignant'
        ]);

        // Vérifier que le groupe appartient au département via sa filière
        $groupe = GroupeUe::whereHas('filiere', function($q) use ($departementId) {
            $q->where('id_Departement', $departementId);
        })->findOrFail($request->idGroupe);

        // Vérifier que l'enseignant appartient au département
        \App\Models\Enseignant::where('id_Departement', $departementId)->findOrFail($request->id_Enseignant);

        Ue::create($request->all());

        return redirect()->route('departement.ues', ['filiere_id' => $groupe->id_Filiere])->with('success', 'UE créée avec succès');
    }

    public function storeGroupe(Request $request)
    {
        $departementId = auth()->guard('departement')->id();

        $request->validate([
            'code' => 'required',
            'intitule' => 'required',
            'id_Filiere' => 'required|exists:filieres,id_Filiere'
        ]);

        // Vérifier que la filière appartient au département
        Filiere::where('id_Departement', $departementId)->findOrFail($request->id_Filiere);

        GroupeUe::create([
            'code' => $request->code,
            'intitule' => $request->intitule,
            'id_Filiere' => $request->id_Filiere
        ]);

        return redirect()->route('departement.ues', ['filiere_id' => $request->id_Filiere])->with('success', 'Groupe d\'UE créé avec succès');
    }
}
