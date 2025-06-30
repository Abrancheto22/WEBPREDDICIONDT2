<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Paciente extends Model
{
    use HasFactory;

    protected $table = 'paciente';

    protected $primaryKey = 'idpaciente';

    protected $fillable = [
        'DNI',
        'nombre',
        'apellido',
        'sexo',
        'fecha_nacimiento',
        'direccion',
        'telefono',
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

    public function getImagenUrlAttribute()
    {
        if ($this->imagen && Storage::disk('public')->exists($this->imagen)) {
            return Storage::disk('public')->url($this->imagen);
        }
        return null;
    }
}
