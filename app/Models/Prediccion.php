<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prediccion extends Model
{
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
        'timer'
    ];
    
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    
    public function cita()
    {
        return $this->belongsTo(Cita::class, 'idcita', 'idcita');
    }
}
