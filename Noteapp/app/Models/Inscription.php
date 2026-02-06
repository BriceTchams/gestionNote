<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inscription extends Model
{
    protected $primaryKey = 'id_Inscription';
    protected $fillable = [
        'id_Etudiant',
        'id_Filiere', 
        'id_Annee',
        'date_Inscription',
        'statut_Paiement'
    ];
    
    public function etudiant(): BelongsTo
    {
        return $this->belongsTo(Etudiant::class, 'id_Etudiant', 'id_Etudiant');
    }
    
    public function filiere(): BelongsTo
    {
        return $this->belongsTo(Filiere::class, 'id_Filiere', 'id_Filiere');
    }
    
    public function anneeAcademique(): BelongsTo
    {
        return $this->belongsTo(AnneeAcademique::class, 'id_Annee', 'id_Annee');
    }
}
