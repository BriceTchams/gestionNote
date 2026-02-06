<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ues', function (Blueprint $table) {
            $table->id('id_UE');
            $table->string('libelle', 255);
            $table->string('code', 25)->unique();
            $table->integer('credits')->default(0);
            $table->foreignId('id_Filiere')->constrained('filieres', 'id_Filiere');
            $table->foreignId('idGroupe')->constrained('groupe_ue', 'idGroupe');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ues');
    }
};