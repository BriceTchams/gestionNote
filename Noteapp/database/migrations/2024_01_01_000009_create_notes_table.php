<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notes', function (Blueprint $table) {
            $table->id('id_Note'); // Identifiant unique de la note (clé primaire)
            $table->foreignId('id_Etudiant')->constrained('etudiants', 'id_Etudiant'); // Clé étrangère vers la table etudiants
            $table->foreignId('id_Evaluation')->constrained('evaluations', 'id_Evaluation'); // Clé étrangère vers la table evaluations
            $table->foreignId('id_Enseignant')->constrained('enseignants', 'id_Enseignant'); // Clé étrangère vers la table enseignants
            $table->float('valeur')->check('valeur >= null AND valeur <= 20'); // Valeur de la note entre 0 et 20
            $table->dateTime('date_Saisie')->useCurrent(); // Date et heure de saisie (par défaut maintenant)
            $table->timestamps(); // Colonnes created_at et updated_at automatiques
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notes'); // Supprime la table notes en cas de rollback
    }
};