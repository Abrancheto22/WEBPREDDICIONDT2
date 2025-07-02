<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cita extends Model
{
    use HasFactory;

    protected $table = 'cita';

    protected $primaryKey = 'idcita';
    
    protected $fillable = [
        'idpaciente',
        'iddoctor',
        'idenfermera',
        'fecha_cita',
        'hora_cita',
        'motivo',
        'estado'
    ];
    
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
    
    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'idpaciente', 'idpaciente');
    }
    
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'iddoctor', 'iddoctor');
    }
    
    public function enfermera()
    {
        return $this->belongsTo(Enfermera::class, 'idenfermera', 'idenfermera');
    }

    public function triaje()
    {
        return $this->hasOne(Triaje::class, 'idcita', 'idcita');
    }
}
