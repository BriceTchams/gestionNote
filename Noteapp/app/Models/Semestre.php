<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Semestre extends Model
{
    protected $primaryKey = 'id_Semestre';
    protected $fillable = ['id_Annee', 'numero'];
    
    public function anneeAcademique(): BelongsTo
    {
        return $this->belongsTo(AnneeAcademique::class, 'id_Annee');
    }
    
    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class, 'id_Semestre');
    }
    
    public function procesVerbaux(): HasMany
    {
        return $this->hasMany(ProcesVerbal::class, 'id_Semestre');
    }
}
