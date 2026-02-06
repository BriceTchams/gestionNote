<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            // Supprimer la contrainte de clé étrangère puis la colonne id_Etudiant,
            // qui n'est pas utilisée pour le lien entre classes et étudiants.
            if (Schema::hasColumn('classes', 'id_Etudiant')) {
                $table->dropForeign(['id_Etudiant']);
                $table->dropColumn('id_Etudiant');
            }
        });
    }

    public function down(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            // On rétablit la colonne si besoin lors d'un rollback
            if (! Schema::hasColumn('classes', 'id_Etudiant')) {
                $table->foreignId('id_Etudiant')->constrained('etudiants', 'id_Etudiant');
            }
        });
    }
};

