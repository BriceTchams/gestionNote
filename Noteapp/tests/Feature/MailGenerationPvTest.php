<?php

namespace Tests\Feature;

use App\Http\Controllers\Departement\PvController;
use App\Mail\ResultatsPubliesMail;
use App\Models\Classe;
use App\Models\Departement;
use App\Models\Etudiant;
use App\Models\Filiere;
use App\Models\Semestre;
use App\Models\AnneeAcademique;
use App\Models\Ue;
use App\Models\GroupeUe;
use App\Models\Evaluation;
use App\Models\Note;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class MailGenerationPvTest extends TestCase
{
    use RefreshDatabase;

    public function test_mail_is_sent_when_pv_is_generated()
    {
        Mail::fake();

        // Créer un département et l'authentifier
        $departement = Departement::create([
            'nom_Departement' => 'Informatique',
            'login' => 'dept_info',
            'password' => bcrypt('password')
        ]);
        Auth::guard('departement')->login($departement);

        // Créer les données nécessaires
        $annee = AnneeAcademique::create(['libelle_Annee' => '2023-2024']);
        $semestre = Semestre::create(['numero' => 1, 'id_Annee' => $annee->id_Annee]);
        $filiere = Filiere::create(['nom_Filiere' => 'Génie Logiciel', 'id_Departement' => $departement->id_Departement]);
        $classe = Classe::create(['lib_Classe' => 'Niveau 3', 'id_Filiere' => $filiere->id_Filiere]);

        $etudiant = Etudiant::create([
            'nom_Complet' => 'Jean Dupont',
            'matricule_Et' => '1234567',
            'login' => 'jdupont',
            'email' => 'jdupont@example.com',
            'id_Classe' => $classe->id_Classe,
            'password' => bcrypt('password')
        ]);

        $groupe = GroupeUe::create(['nom_groupe' => 'Tronc Commun', 'id_Filiere' => $filiere->id_Filiere]);
        $ue = Ue::create(['libelle' => 'Algorithmique', 'code' => 'INF301', 'credits' => 6, 'id_groupe' => $groupe->id_groupe]);

        $evalCC = Evaluation::create(['id_UE' => $ue->id_UE, 'id_Semestre' => $semestre->id_Semestre, 'type_Evaluation' => 'CC', 'date_Evaluation' => now()]);
        $evalEx = Evaluation::create(['id_UE' => $ue->id_UE, 'id_Semestre' => $semestre->id_Semestre, 'type_Evaluation' => 'Examen', 'date_Evaluation' => now()]);

        Note::create(['id_Etudiant' => $etudiant->id_Etudiant, 'id_Evaluation' => $evalCC->id_Evaluation, 'valeur' => 12]);
        Note::create(['id_Etudiant' => $etudiant->id_Etudiant, 'id_Evaluation' => $evalEx->id_Evaluation, 'valeur' => 14]);

        // Appeler la génération du PV
        $response = $this->post(route('departement.pv.generate'), [
            'filiere_id' => $filiere->id_Filiere,
            'classe_id' => $classe->id_Classe,
            'semestre_id' => $semestre->id_Semestre,
        ]);

        // Vérifier si le mail a été envoyé
        Mail::assertSent(ResultatsPubliesMail::class, function ($mail) use ($etudiant) {
            return $mail->hasTo($etudiant->email);
        });
    }
}
