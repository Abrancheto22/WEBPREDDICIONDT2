<?php

namespace App\Http\Controllers;


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
}
