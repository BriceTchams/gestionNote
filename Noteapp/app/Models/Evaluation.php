<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Evaluation extends Model
{
    protected $primaryKey = 'id_Evaluation';
    protected $fillable = ['id_UE', 'id_Semestre', 'type_Evaluation', 'date_Evaluation'];
    
    public function ue(): BelongsTo
    {
        return $this->belongsTo(Ue::class, 'id_UE');
    }
    
    public function semestre(): BelongsTo
    {
        return $this->belongsTo(Semestre::class, 'id_Semestre');
    }
    
    public function notes(): HasMany
    {
        return $this->hasMany(Note::class, 'id_Evaluation');
    }
}
