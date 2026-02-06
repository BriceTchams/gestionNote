<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('annee_academiques', function (Blueprint $table) {
            $table->id('id_Annee');
            $table->string('libelle_Annee', 20);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('annee_academiques');
    }
};