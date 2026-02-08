<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('revendications', function (Blueprint $table) {
            $table->id('id_Revendication');
            $table->foreignId('id_Etudiant')->constrained('etudiants', 'id_Etudiant');
            $table->foreignId('id_Evaluation')->constrained('evaluations', 'id_Evaluation');
            $table->text('message');
            $table->string('statut')->default('en attente'); // en attente, traitée, rejetée
            $table->text('reponse_enseignant')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('revendications');
    }
};
