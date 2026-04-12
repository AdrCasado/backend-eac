<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SituacionCompetencia extends Model
{
    protected $fillable = [
        'ecosistema_laboral_id', 'codigo', 'titulo', 'descripcion', 'umbral_maestria', 'nivel_complejidad', 'activa',
    ];

    protected $table = 'situaciones_competencia';

    protected $casts = [
        'umbral_maestria' => 'decimal:2',
        'activa' => 'boolean',
    ];

    public function ecosistemaLaboral(): BelongsTo
    {
        return $this->belongsTo(EcosistemaLaboral::class);
    }

    public function criterioEvaluacion(): BelongsToMany
    {
        return $this->belongsToMany(CriterioEvaluacion::class);
    }
}
