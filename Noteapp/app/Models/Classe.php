<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classe extends Model
{
    protected $primaryKey = 'id_Classe';
    protected $fillable = ['lib_Classe', 'nbre_Elv', 'id_Filiere'];

    public function filiere(): BelongsTo
    {
        return $this->belongsTo(Filiere::class, 'id_Filiere');
    }

    public function etudiants(): HasMany
    {
        return $this->hasMany(Etudiant::class, 'id_Classe');
    }
    // Les étudiants sont liés à la classe via la clé étrangère id_Classe
    // définie dans le modèle Etudiant (relation inverse utilisée ailleurs).
}
