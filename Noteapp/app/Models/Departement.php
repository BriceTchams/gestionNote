<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Departement extends Authenticatable
{
    protected $primaryKey = 'id_Departement';
    protected $fillable = ['nom_Departement', 'chef_Departement', 'login', 'password', 'add_plain_password'];

    public function filieres(): HasMany
    {
        return $this->hasMany(Filiere::class, 'id_Departement');
    }

    public function enseignants(): HasMany
    {
        return $this->hasMany(Enseignant::class, 'id_Departement');
    }
}
