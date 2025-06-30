<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Doctor extends Model
{
    use HasFactory;
    
    protected $table = 'doctor';
    
    protected $primaryKey = 'iddoctor';
    
    protected $fillable = [
        'DNI',
        'nombre',
        'apellido',
        'numero',
        'especialidad',
        'imagen',
        'iduser'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'iduser', 'id');
    }
}
