<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Note extends Model
{
    protected $primaryKey = 'id_Note';
    protected $fillable = ['id_Etudiant', 'id_Evaluation', 'id_Enseignant', 'valeur', 'date_Saisie'];
    
    public function etudiant(): BelongsTo
    {
        return $this->belongsTo(Etudiant::class, 'id_Etudiant');
    }
    
    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(Evaluation::class, 'id_Evaluation');
    }
    
    public function enseignant(): BelongsTo
    {
        return $this->belongsTo(Enseignant::class, 'id_Enseignant');
    }
}
