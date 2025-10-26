<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        // Données simulées - remplacez par votre logique métier
        $stats = [
             'total_products' => Product::count(),
            'in_stock' => Product::where('quantity', '>', 10)->count(),
            'low_stock' => Product::whereBetween('quantity', [1, 10])->count(),
            'out_of_stock' => Product::where('quantity', 0)->count()
        ];

        return view('dashboard.index', compact('stats'));
    }
}