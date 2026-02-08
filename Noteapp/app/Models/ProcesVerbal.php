<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProcesVerbal extends Model
{
    protected $primaryKey = 'id_PV';
    protected $fillable = ['id_Filiere', 'id_Annee', 'id_Semestre', 'date_Generation', 'moyenne_Generale_Classe', 'statut'];

    public function filiere(): BelongsTo
    {
        return $this->belongsTo(Filiere::class, 'id_Filiere');
    }

    public function anneeAcademique(): BelongsTo
    {
        return $this->belongsTo(AnneeAcademique::class, 'id_Annee');
    }

    public function semestre(): BelongsTo
    {
        return $this->belongsTo(Semestre::class, 'id_Semestre');
    }

    public function detailPVs(): HasMany
    {
        return $this->hasMany(DetailPv::class, 'id_PV');
    }
}
