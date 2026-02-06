<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proces_verbals', function (Blueprint $table) {
            $table->id('id_PV');
            $table->unsignedBigInteger('id_Filiere');
            $table->unsignedBigInteger('id_Semestre');
            $table->unsignedBigInteger('id_Annee'); // Ajouté pour ton seeder
            $table->decimal('moyenne_Generale_Classe', 5, 2)->nullable(); // Ajouté pour ton seeder
            $table->date('date_Generation');
            $table->string('statut')->default('Brouillon');
            $table->timestamps();

            // Clés étrangères
            $table->foreign('id_Filiere')->references('id_Filiere')->on('filieres')->onDelete('cascade');
            $table->foreign('id_Semestre')->references('id_Semestre')->on('semestres')->onDelete('cascade');
            $table->foreign('id_Annee')->references('id_Annee')->on('annee_academiques')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proces_verbals');
    }
};