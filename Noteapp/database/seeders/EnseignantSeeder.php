<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EnseignantSeeder extends Seeder
{
    public function run(): void
    {
        // On récupère l'ID du premier département (Informatique par exemple)
        $deptId = DB::table('departements')->first()->id_Departement;

        $enseignants = [
            [
                'nom_Enseignant' => 'Michel Lefebvre',
                'matricule' => 'ENS002',
                'email' => 'm.lefebvre@uni2notes.com',
                'login' => 'michel',
                'password' => Hash::make('michel2026'),
                'id_Departement' => $deptId,
                'created_at' => now(),
            ],
            [
                'nom_Enseignant' => 'Jean Dupont',
                'matricule' => 'ENS003',
                'email' => 'j.dupont@unin3otes.com',
                'login' => 'jean',
                'password' => Hash::make('pass123'),
                'id_Departement' => $deptId,
                'created_at' => now(),
            ],
            [
                'nom_Enseignant' => 'Marie Curie',
                'matricule' => 'ENS004',
                'email' => 'm.curie@unindotes.com',
                'login' => 'marie',
                'password' => Hash::make('pass123'),
                'id_Departement' => $deptId,
                'created_at' => now(),
            ],
        ];

        DB::table('enseignants')->insert($enseignants);
    }
}