<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('groupe_ue', function (Blueprint $table) {
            $table->id("idGroupe");
            $table->string("code");
            $table->string("intitule");
            $table->foreignId('id_Filiere')->constrained('filieres', 'id_Filiere');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('groupe_ue');
    }
};
