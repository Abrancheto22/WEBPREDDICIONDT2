<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Triaje extends Model
{
    use HasFactory;
    
    protected $table = 'triaje';

    protected $primaryKey = 'idtriaje';
    
    protected $fillable = [
        'idcita',
        'edad',
        'talla',
        'peso',
        'BMI',
        'grosor_piel',
        'observaciones',
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
