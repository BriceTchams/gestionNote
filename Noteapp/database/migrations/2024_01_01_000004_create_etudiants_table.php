<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('etudiants', function (Blueprint $table) {
            $table->id('id_Etudiant');
            $table->string('nom_Complet', 255);
            $table->string('matricule_Et', 50)->unique();
            $table->string('login', 8)->unique();
            $table->string('email', 100)->nullable();
            $table->string('password', 200)->nullable();
            $table->string('telephone', 25)->nullable();
            $table->date('date_Naissance')->nullable();
            $table->foreignId('id_Classe')->constrained('classes', 'id_Classe');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('etudiants');
    }
};