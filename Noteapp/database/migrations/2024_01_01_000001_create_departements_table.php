<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departements', function (Blueprint $table) {
            $table->id('id_Departement');
            $table->string('nom_Departement', 255);
            $table->string('chef_Departement', 255)->nullable();
            $table->string('login', 10)->unique();
            $table->string('password', 200);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('departements');
    }
};