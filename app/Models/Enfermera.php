<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Enfermera extends Model
{
    use HasFactory;

    protected $table = 'efermera';

    protected $primaryKey = 'idenfermera';

    protected $fillable = [
        'DNI',
        'nombre',
        'apellido',
        'numero',
        'imagen',
        'iduser'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $appends = [
        'DNI'
    ];

    public function getDNIAttribute()
    {
        return $this->attributes['dni'] ?? $this->attributes['DNI'] ?? null;
    }

    public function setDNIAttribute($value)
    {
        $this->attributes['dni'] = $value;
    }

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
