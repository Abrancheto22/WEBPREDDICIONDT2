<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UpdatePasswordsSeeder extends Seeder
{
    public function run()
    {
        // Obtener todos los usuarios
        $users = User::all();
        
        // Actualizar la contraseña de cada usuario usando Bcrypt
        foreach ($users as $user) {
            $user->password = Hash::make($user->password);
            $user->save();
        }
    }
}
