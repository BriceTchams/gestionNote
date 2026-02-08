<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Filiere extends Model
{
    protected $primaryKey = 'id_Filiere';
    protected $fillable = ['id_Departement', 'nom_Filiere'];

    public function departement(): BelongsTo
    {
        return $this->belongsTo(Departement::class, 'id_Departement');
    }

    public function classes(): HasMany
    {
        return $this->hasMany(Classe::class, 'id_Filiere');
    }

    public function ues()
    {
        return $this->hasManyThrough(Ue::class, GroupeUe::class, 'id_Filiere', 'idGroupe', 'id_Filiere', 'idGroupe');
    }

    public function etudiants()
    {
        return $this->hasManyThrough(Etudiant::class, Classe::class, 'id_Filiere', 'id_Classe', 'id_Filiere', 'id_Classe');
    }

    public function inscriptions(): HasMany
    {
        return $this->hasMany(Inscription::class, 'id_Filiere');
    }

    public function procesVerbaux(): HasMany
    {
        return $this->hasMany(ProcesVerbal::class, 'id_Filiere');
    }
}
