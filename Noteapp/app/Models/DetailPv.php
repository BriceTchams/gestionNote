<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPv extends Model
{
    protected $primaryKey = ['id_PV', 'id_Etudiant'];
    public $incrementing = false;
    protected $fillable = ['id_PV', 'id_Etudiant', 'moyenne_Etudiant', 'mention', 'decision'];
    
    public function procesVerbal(): BelongsTo
    {
        return $this->belongsTo(ProcesVerbal::class, 'id_PV');
    }
    
    public function etudiant(): BelongsTo
    {
        return $this->belongsTo(Etudiant::class, 'id_Etudiant');
    }
}
