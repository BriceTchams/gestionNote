<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enseignants', function (Blueprint $table) {
            $table->id('id_Enseignant');
            $table->foreignId('id_Departement')->constrained('departements', 'id_Departement');
            $table->string('nom_Enseignant', 255);
            $table->string('matricule', 50)->unique();
            $table->string('password', 200);
            $table->string('login', 10);
            $table->string('email', 255)->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enseignants');
    }
};