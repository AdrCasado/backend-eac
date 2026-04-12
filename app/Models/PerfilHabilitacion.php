<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PerfilHabilitacion extends Model
{
    protected $fillable = [
        'estudiante_id', 'ecosistema_laboral_id', 'calificacion_actual',
    ];
    protected $table = 'perfiles_habilitacion';

    protected $casts = ['calificacion_actual' => 'decimal:2'];

    public function estudiante(): BelongsTo
    {
        return $this->belongsTo(User::class, 'estudiante_id');
    }

    public function ecosistemaLaboral(): BelongsTo
    {
        return $this->belongsTo(EcosistemaLaboral::class);
    }

    public function prerequisitos(): BelongsToMany
    {
        return $this->belongsToMany(
            SituacionCompetencia::class,
            'sc_precedencia',
            'sc_id',
            'sc_requisito_id'
        );
    }

    // SCs que requieren esta SC como prerequisito
    public function dependientes(): BelongsToMany
    {
        return $this->belongsToMany(
            SituacionCompetencia::class,
            'sc_precedencia',
            'sc_requisito_id',
            'sc_id'
        );
    }

    // CEs del currículo que cubre esta SC
    public function criteriosEvaluacion(): BelongsToMany
    {
        return $this->belongsToMany(
            CriterioEvaluacion::class,
            'sc_criterios_evaluacion',
            'situacion_competencia_id',
            'criterio_evaluacion_id'
        )->withPivot('peso_en_sc');
    }

    public function perfilesHabilitacion(): HasMany
    {
        return $this->hasMany(PerfilSituacion::class, 'situacion_competencia_id');
    }
}
