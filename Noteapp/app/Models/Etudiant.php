<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Etudiant extends Authenticatable{
    protected $primaryKey = 'id_Etudiant';
    protected $fillable = ['nom_Complet', 'matricule_Et', 'login', 'email', 'telephone', 'date_Naissance'];
    
    public function notes(): HasMany
    {
        return $this->hasMany(Note::class, 'id_Etudiant');
    }
    
    public function inscriptions(): HasMany
    {
        return $this->hasMany(Inscription::class, 'id_Etudiant');
    }
    
    public function detailPVs(): BelongsToMany
    {
        return $this->belongsToMany(ProcesVerbal::class, 'detail_pvs', 'id_Etudiant', 'id_PV')
            ->withPivot('moyenne_Etudiant', 'mention', 'decision')
            ->withTimestamps();
    }
    public function classe(): BelongsTo
    {
        return $this->belongsTo(Classe::class, 'id_Classe');
    }
}
