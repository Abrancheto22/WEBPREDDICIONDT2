<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

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
        $usuarios = User::all();
        return view('users.index', compact('usuarios'));
    }
}
