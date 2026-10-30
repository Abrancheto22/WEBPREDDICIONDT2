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
        'iduser',
        'sueldo'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $appends = [
        'dni'
    ];

    public function getDniAttribute()
    {
        return $this->attributes['DNI'] ?? $this->attributes['dni'] ?? null;
    }

    public function setDniAttribute($value)
    {
        $this->attributes['DNI'] = $value;
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'iduser', 'id');
    }
}
