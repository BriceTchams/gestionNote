<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inscriptions', function (Blueprint $table) {
            $table->id('id_Inscription');
            $table->foreignId('id_Etudiant')->constrained('etudiants', 'id_Etudiant');
            $table->foreignId('id_Filiere')->constrained('filieres', 'id_Filiere');
            $table->foreignId('id_Annee')->constrained('annee_academiques', 'id_Annee');
            $table->date('date_Inscription');
            $table->string('statut_Paiement', 50)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inscriptions');
    }
};