<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ue extends Model
{
    protected $primaryKey = 'id_UE';
    protected $fillable = [ 'code' , 'libelle' , 'credits' , 'idGroupe' , 'id_Enseignant' ];

    public function groupe_ue():BelongsTo {
        return $this->belongsTo(GroupeUe::class , 'idGroupe');
    }

    public function enseignant():BelongsTo {
        return $this->belongsTo(Enseignant::class , 'id_Enseignant');
    }
}
