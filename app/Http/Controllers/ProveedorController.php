<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class ProveedorController extends Controller
{
    public function index(): View
    {
        return view('proveedores.index');
    }
}
