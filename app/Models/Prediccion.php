<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prediccion extends Model
{
    public $timestamps = false;

    protected $table = 'prediccion';

    protected $primaryKey = 'idprediccion';
    
    protected $fillable = [
        'idcita',
        'glucosa',
        'presion_sanguinea',
        'grosor_piel',
        'embarazos',
        'BMI',
        'pedigree',
        'edad',
        'insulina',
        'resultado',
        'observacion',
        'analisis_ia',
        'timer',
        'timer_inicio', // Agregado
        'timer_parada', // Agregado
        'attachment_paths', // Agregado para documentos adjuntos
        'attachment_names', // Agregado para nombres de documentos adjuntos
        'validar_prediccion',
    ];
    
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
    
    protected $casts = [
        'analisis_ia' => 'string',
        'timer_inicio' => 'string',
        'timer_parada' => 'string',
        'attachment_paths' => 'array', // Cast para JSON array
        'attachment_names' => 'array', // Cast para JSON array
    ];

    
    public function cita()
    {
        return $this->belongsTo(Cita::class, 'idcita', 'idcita');
    }
}