<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ue extends Model
{
    //
    protected $fillable = [ 'code' , 'libelle' , 'credits' ,  'id_filiere ' , 'idGroupe' ];

    public function groupe_ue():BelongsTo {
        $this->belongsTo(GroupeUe::class , 'idGroupe');
    }
}
