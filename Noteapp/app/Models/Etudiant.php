<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Etudiant extends Authenticatable{
    protected $primaryKey = 'id_Etudiant';
    protected $fillable = ['nom_Complet', 'matricule_Et', 'login', 'email', 'telephone', 'date_Naissance' , 'id_Classe', 'password', 'add_plain_password'];

    protected $appends = ['nom', 'matricule'];

    public function getNomAttribute()
    {
        return $this->attributes['nom_Complet'] ?? null;
    }

    public function getMatriculeAttribute()
    {
        return $this->attributes['matricule_Et'] ?? null;
    }

    public function getDateNaissanceAttribute()
    {
        return $this->attributes['date_Naissance'] ?? null;
    }

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
