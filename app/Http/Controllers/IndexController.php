<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Rol;

class IndexController
{
    public function index()
    {
        return view('welcome');
    }

    public function dashboard()
    {
        return view('index');
    }

    public function users()
    {
        $users = User::with('rol')->get();
        return view('users.index', compact('users'));
    }
}
