<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User; // Added the User model to use in the relationship

class Rol extends Model
{
    protected $table = 'rols';
    protected $primaryKey = 'idrol';
    
    protected $fillable = [
        'nombre'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    // Relación con usuarios (si es necesario)
    public function usuarios()
    {
        return $this->hasMany(User::class);
    }
}
