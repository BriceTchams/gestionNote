<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Enseignant extends Authenticatable
{
    protected $primaryKey = 'id_Enseignant';
    protected $fillable = ['id_Departement', 'nom_Enseignant', 'matricule', 'password', 'login', 'email', 'add_plain_password'];

    public function departement(): BelongsTo
    {
        return $this->belongsTo(Departement::class, 'id_Departement');
    }

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class, 'id_Enseignant');
    }

    public function ues(): HasMany
    {
        return $this->hasMany(Ue::class, 'id_Enseignant')->with('groupe_ue.filiere');
    }
}
