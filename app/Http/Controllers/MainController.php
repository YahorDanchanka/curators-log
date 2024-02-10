<?php

namespace App\Http\Controllers;

class MainController extends Controller
{
    public function index()
    {
        return to_route('groups.index');
    }
}
