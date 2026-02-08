<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Revendication extends Model
{
    protected $primaryKey = 'id_Revendication';
    protected $fillable = ['id_Etudiant', 'id_Evaluation', 'message', 'statut', 'reponse_enseignant'];

    public function etudiant(): BelongsTo
    {
        return $this->belongsTo(Etudiant::class, 'id_Etudiant');
    }

    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(Evaluation::class, 'id_Evaluation');
    }
}
