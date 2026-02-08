<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id('id_Evaluation');
            $table->foreignId('id_Enseignant')->constrained('enseignants', 'id_Enseignant');

            $table->foreignId('id_UE')->constrained('ues', 'id_UE');
            $table->foreignId('id_Semestre')->constrained('semestres', 'id_Semestre');
            $table->enum('type_Evaluation', ['CC', 'Examen', 'TP', 'Rattrapage']);
            $table->date('date_Evaluation');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
