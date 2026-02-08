<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GroupeUe extends Model
{
    protected $table = 'groupe_ue';
    protected $primaryKey = 'idGroupe';
    protected $fillable = ['code' , 'intitule' , 'id_Filiere'];

    public function ues() : HasMany {
        return $this->hasMany(Ue::class , 'idGroupe');
    }

    public function filiere() : BelongsTo {
        return $this->belongsTo(Filiere::class , 'id_Filiere');
    }

}
