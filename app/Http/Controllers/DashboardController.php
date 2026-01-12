<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'totalCategorias' => 0,
            'totalProductos' => 0,
            'productosSinStock' => 0,
            'productosDestacados' => 0,
            'totalUsuarios' => 0,
            'categoriasActivas' => 0,
        ];

        return view('dashboard.index', compact('stats'));
    }
}

