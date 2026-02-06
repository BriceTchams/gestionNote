<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AnneeAcademique extends Model
{
    protected $primaryKey = 'id_Annee';
    protected $fillable = ['libelle_Annee'];
    
    public function semestres(): HasMany
    {
        return $this->hasMany(Semestre::class, 'id_Annee');
    }
    
    public function inscriptions(): HasMany
    {
        return $this->hasMany(Inscription::class, 'id_Annee');
    }
    
    public function procesVerbaux(): HasMany
    {
        return $this->hasMany(ProcesVerbal::class, 'id_Annee');
    }
}
