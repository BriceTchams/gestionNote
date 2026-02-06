<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GroupeUe extends Model
{
    protected $fillable = ['code' , 'intitule' , 'id_departement'];

    public function ues() : HasMany {
        $this->hasMany(Ue::class , 'idGroupe');
    }
}
