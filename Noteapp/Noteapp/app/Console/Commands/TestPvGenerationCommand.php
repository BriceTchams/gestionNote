<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\Departement;
use App\Models\Filiere;
use App\Models\Classe;
use App\Models\AnneeAcademique;
use App\Models\Semestre;
use App\Models\GroupeUe;
use App\Models\Ue;
use App\Models\Evaluation;
use App\Models\Etudiant;
use App\Models\Note;
use App\Http\Controllers\Departement\PvController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestPvGenerationCommand extends Command
{
    protected $signature = 'test:pv-generation';
    protected $description = 'Teste la generation de PV et l\'envoi de mails';

    public function handle()
    {
        $this->info('Début du test de génération PV...');

        // 1. Préparer les données
        try {
            $this->info('Création des données de test...');

            $departement = Departement::create([
                'nom_Departement' => 'Test Dept ' . time(),
                'login' => 'test_dept_' . time(),
                'password' => bcrypt('password')
            ]);

            // Simuler l'authentification
            Auth::guard('departement')->login($departement);

            $annee = AnneeAcademique::create(['libelle_Annee' => '2023-' . time()]);
            $semestre = Semestre::create(['numero' => 1, 'id_Annee' => $annee->id_Annee]);
            $filiere = Filiere::create(['nom_Filiere' => 'GL Test', 'id_Departement' => $departement->id_Departement]);
            $classe = Classe::create(['lib_Classe' => 'Niveau Test', 'id_Filiere' => $filiere->id_Filiere]);

            $email = 'test.' . time() . '@example.com';
            $etudiant = Etudiant::create([
                'nom_Complet' => 'Jean Testeur',
                'matricule_Et' => 'TEST' . time(),
                'login' => 'jtest' . time(),
                'email' => $email,
                'id_Classe' => $classe->id_Classe,
                'password' => bcrypt('password')
            ]);

            $groupe = GroupeUe::create(['nom_groupe' => 'Groupe Test', 'id_Filiere' => $filiere->id_Filiere]);
            $ue = Ue::create(['libelle' => 'UE Test', 'code' => 'INFTEST', 'credits' => 4, 'id_groupe' => $groupe->id_groupe]);

            $eval = Evaluation::create([
                'id_UE' => $ue->id_UE,
                'id_Semestre' => $semestre->id_Semestre,
                'type_Evaluation' => 'Examen',
                'date_Evaluation' => now()
            ]);

            Note::create([
                'id_Etudiant' => $etudiant->id_Etudiant,
                'id_Evaluation' => $eval->id_Evaluation,
                'valeur' => 15
            ]);

            $this->info('Données créées avec succès.');

            // 2. Exécuter le contrôleur
            $controller = new PvController();
            $request = new Request([
                'filiere_id' => $filiere->id_Filiere,
                'classe_id' => $classe->id_Classe,
                'semestre_id' => $semestre->id_Semestre,
            ]);

            // Nettoyage log avant test (si possible)
            file_put_contents(storage_path('logs/laravel.log'), '');

            $this->info('Lancement de generate()...');

            // On appelle generate(). Attention : ça retourne un Download Response normalement.
            // On veut juste vérifier si ça ne plante pas et si ça envoie le mail.
            try {
                ob_start(); // Capturer le flux PDF pour ne pas polluer la sortie
                $controller->generate($request);
                ob_end_clean();
            } catch (\Exception $e) {
                 // Si c'est juste le download qui tente d'écrire des headers, on peut ignorer si le mail est parti avant
                $this->error("Erreur lors de l'exécution (peut être normal pour PDF download): " . $e->getMessage());
            }

            // 3. Vérifier les logs
            $logContent = file_get_contents(storage_path('logs/laravel.log'));
            if (strpos($logContent, $email) !== false && strpos($logContent, 'Résultats Semestriels') !== false) {
                $this->info("SUCCESS: Le mail semble avoir été envoyé (trouvé dans les logs).");
            } else {
                $this->error("FAILURE: Aucune trace du mail dans les logs.");
                $this->info("Log snippet: " . substr($logContent, -1000));
            }

            // Cleanup partiel
            $etudiant->delete();
            // ... autres cleanups ...

        } catch (\Exception $e) {
            $this->error('Exception générale : ' . $e->getMessage());
            $this->error($e->getTraceAsString());
        }
    }
}
