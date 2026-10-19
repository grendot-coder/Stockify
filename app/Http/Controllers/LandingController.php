<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockTransaction;
use App\Models\Supplier;

class LandingController extends Controller
{
    /**
     * Tampilkan landing page editorial Stockify.
     */
    public function index()
    {
        // Metrik riil dari database atau fallback elegan
        $productCount = Product::count();
        $transactionCount = StockTransaction::count();
        $supplierCount = Supplier::count();

        return view('pages.landing', compact(
            'productCount',
            'transactionCount',
            'supplierCount'
        ));
    }
}
