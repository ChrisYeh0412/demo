<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $data = [];
        return view('index', compact('data'));
    }

    public function privacy() {
        $data = [];
        return view('privacy', compact('data'));
    }

}
