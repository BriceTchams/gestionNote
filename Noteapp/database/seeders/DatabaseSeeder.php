<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        $this->cleanTables();
        
        $this->seedDepartements();
        $this->seedAnneeAcademiques();
        $this->seedEtudiants();
        $this->seedFilieres(); 
        $this->seedEnseignants();
        $this->seedSemestres();
        $this->seedUEs();
        $this->seedInscriptions();
        $this->seedEvaluations();
        $this->seedNotes(); 
        $this->seedProcesVerbals(); 

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        $this->command->info('Base de données UniNotes peuplée avec succès !');
    }

    private function cleanTables(): void
    {
        $tables = ['detail_pvs', 'proces_verbals', 'notes', 'evaluations', 'inscriptions', 'ues', 'semestres', 'filieres', 'enseignants', 'etudiants', 'annee_academiques', 'departements'];
        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }
    }

    private function seedDepartements(): void
    {
        DB::table('departements')->insert([
            ['nom_Departement' => 'Informatique', 'chef_Departement' => 'Dr. Diallo', 'login' => 'dept_info', 'password' => Hash::make('pass123')],
            ['nom_Departement' => 'Mathématiques', 'chef_Departement' => 'Dr. Traoré', 'login' => 'dept_math', 'password' => Hash::make('pass123')],
        ]);
    }

    private function seedEtudiants(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            DB::table('etudiants')->insert([
                'nom_Complet' => "Etudiant $i",
                'matricule_Et' => "ETU00$i",
                'email' => "etudiant$i@uninotes.com",
                'telephone' => "67000000$i",
                'date_Naissance' => '2002-05-15',
                'login' => "etu$i",
                'password' => Hash::make('pass123'),
                'created_at' => now()
            ]);
        }
    }

    private function seedEnseignants(): void
    {
        $deptId = DB::table('departements')->first()->id_Departement;
        DB::table('enseignants')->insert([
            ['nom_Enseignant' => 'Michel Lefebvre', 'matricule' => 'ENS001', 'email' => 'm.lefebvre@uninotes.com', 'login' => 'michel', 'password' => Hash::make('michel2026'), 'id_Departement' => $deptId, 'created_at' => now()],
        ]);
    }

    private function seedFilieres(): void
    {
        $deptId = DB::table('departements')->first()->id_Departement;
        DB::table('filieres')->insert([
            ['nom_Filiere' => 'Génie Logiciel', 'id_Departement' => $deptId],
            ['nom_Filiere' => 'Réseaux', 'id_Departement' => $deptId],
        ]);
    }

    private function seedAnneeAcademiques(): void
    {
        DB::table('annee_academiques')->insert(['libelle_Annee' => '2025-2026']);
    }

    private function seedSemestres(): void
    {
        $anneeId = DB::table('annee_academiques')->first()->id_Annee;
        DB::table('semestres')->insert([
            ['numero' => 1, 'id_Annee' => $anneeId],
            ['numero' => 2, 'id_Annee' => $anneeId],
        ]);
    }

    private function seedUEs(): void
    {
        $filiereId = DB::table('filieres')->first()->id_Filiere;
        DB::table('ues')->insert([
            ['libelle' => 'Programmation Avancée', 'code' => 'PA301', 'credits' => 6, 'coefficient' => 3, 'id_Filiere' => $filiereId],
            ['libelle' => 'Génie Logiciel', 'code' => 'GL301', 'credits' => 4, 'coefficient' => 2, 'id_Filiere' => $filiereId],
        ]);
    }

    private function seedInscriptions(): void
    {
        $etudiants = DB::table('etudiants')->pluck('id_Etudiant');
        $filiereId = DB::table('filieres')->first()->id_Filiere;
        $anneeId = DB::table('annee_academiques')->first()->id_Annee;

        foreach ($etudiants as $id) {
            DB::table('inscriptions')->insert([
                'id_Etudiant' => $id, 'id_Filiere' => $filiereId, 'id_Annee' => $anneeId,
                'date_Inscription' => now(), 'statut_Paiement' => 'Payé'
            ]);
        }
    }

    private function seedEvaluations(): void
    {
        $ueIds = DB::table('ues')->pluck('id_UE');
        $semestreId = DB::table('semestres')->first()->id_Semestre;
        foreach ($ueIds as $id) {
            DB::table('evaluations')->insert([
                ['type_Evaluation' => 'CC', 'id_UE' => $id, 'id_Semestre' => $semestreId, 'date_Evaluation' => now()],
                ['type_Evaluation' => 'Examen', 'id_UE' => $id, 'id_Semestre' => $semestreId, 'date_Evaluation' => now()],
            ]);
        }
    }

    private function seedNotes(): void
    {
        $etudiants = DB::table('etudiants')->pluck('id_Etudiant')->toArray();
        $evals = DB::table('evaluations')->pluck('id_Evaluation')->toArray();
        $enseignantId = DB::table('enseignants')->first()->id_Enseignant;

        for ($i = 0; $i < 30; $i++) {
            DB::table('notes')->insert([
                'id_Etudiant' => $etudiants[array_rand($etudiants)],
                'id_Evaluation' => $evals[array_rand($evals)],
                'id_Enseignant' => $enseignantId,
                'valeur' => rand(8, 18) + (rand(0, 9) / 10),
                'date_Saisie' => now(),
                'created_at' => now()
            ]);
        }
    }

    private function seedProcesVerbals(): void
    {
        $filiereId = DB::table('filieres')->first()->id_Filiere;
        $semestreId = DB::table('semestres')->first()->id_Semestre;
        $anneeId = DB::table('annee_academiques')->first()->id_Annee;

        // 1. Insertion dans 'proces_verbals'
        $pvId = DB::table('proces_verbals')->insertGetId([
            'id_Filiere' => $filiereId,
            'id_Semestre' => $semestreId,
            'id_Annee' => $anneeId,
            'moyenne_Generale_Classe' => 12.45,
            'date_Generation' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // 2. Insertion des enregistrements dans 'detail_pvs' (un pour chaque étudiant)
        $etudiants = DB::table('etudiants')->get();
        
        foreach ($etudiants as $key => $etudiant) {
            DB::table('detail_pvs')->insert([
                'id_PV' => $pvId,
                'id_Etudiant' => $etudiant->id_Etudiant,
                'moyenne_Etudiant' => rand(10, 17) + (rand(0, 9) / 10),
                // 'rang' => $key + 1,
                'decision' => (rand(0, 1) > 0.1) ? 'Admis' : 'Rattrapage',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}