<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Rol;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    protected $table = 'users';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'idrol',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'idrol', 'idrol');
    }

    public function getRoleName()
    {
        return $this->rol ? $this->rol->nombre : null;
    }

    public function doctor()
    {
        return $this->hasOne(Doctor::class, 'iduser', 'id');
    }

    public function enfermera()
    {
        return $this->hasOne(Enfermera::class, 'iduser', 'id');
    }

    public function paciente()
    {
        return $this->hasOne(Paciente::class, 'iduser', 'id');
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'idrol' => 'integer'
        ];
    }
}
