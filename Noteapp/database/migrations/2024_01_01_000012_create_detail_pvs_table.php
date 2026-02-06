<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_pvs', function (Blueprint $table) {
            $table->foreignId('id_PV')->constrained('proces_verbals', 'id_PV');
            $table->foreignId('id_Etudiant')->constrained('etudiants', 'id_Etudiant');
            $table->float('moyenne_Etudiant');
            $table->string('mention', 50)->nullable();
            $table->enum('decision', ['Admis', 'Rattrapage']);
            $table->timestamps();
            
            $table->primary(['id_PV', 'id_Etudiant']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_pvs');
    }
};