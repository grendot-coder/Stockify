<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockOpname;
use App\Services\StockOpname\StockOpnameService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockOpnameController extends Controller
{
    protected $stockOpnameService;

    public function __construct(StockOpnameService $stockOpnameService)
    {
        $this->stockOpnameService = $stockOpnameService;
    }

    public function index(Request $request)
    {
        $search = $request->query('search');
        $dateFrom = $request->query('date_from');
        $dateTo = $request->query('date_to');
        $productId = $request->query('product_id') ? (int) $request->query('product_id') : null;

        $opnames = $this->stockOpnameService->getPaginated($search, $dateFrom, $dateTo, $productId, 10);
        $stats = $this->stockOpnameService->getStats();
        $products = Product::orderBy('name')->get(['id', 'name', 'sku', 'stock']);

        return view('pages.opnames.index', compact('opnames', 'stats', 'products', 'search', 'dateFrom', 'dateTo', 'productId'));
    }

    public function create()
    {
        $products = Product::with('category')->orderBy('name')->get();

        return view('pages.opnames.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'physical_stock' => 'required|integer|min:0',
            'opname_date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
            'adjust_stock' => 'nullable|boolean',
        ], [
            'product_id.required' => 'Pilih produk yang akan dihitung fisik persediaannya.',
            'product_id.exists' => 'Produk yang dipilih tidak ditemukan dalam katalog.',
            'physical_stock.required' => 'Jumlah stok fisik aktual di gudang wajib diisi.',
            'physical_stock.integer' => 'Jumlah fisik harus berupa angka bulat positif.',
            'physical_stock.min' => 'Jumlah fisik minimal adalah 0.',
            'opname_date.required' => 'Tanggal pelaksanaan opname wajib ditentukan.',
        ]);

        $user = Auth::user();
        $adjustStock = $request->boolean('adjust_stock') && in_array($user->role, ['admin', 'manager']);

        try {
            $opname = $this->stockOpnameService->createOpname(
                $request->all(),
                $user->id,
                $adjustStock
            );

            $msg = 'Hasil stock opname berhasil direkam.';
            if ($adjustStock && $opname->difference !== 0) {
                $msg .= ' Stok sistem gudang telah otomatis diselaraskan dengan hasil fisik.';
            }

            return redirect()->route('opnames.show', $opname->id)->with('success', $msg);
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memproses stock opname: '.$e->getMessage());
        }
    }

    public function show($id)
    {
        $opname = StockOpname::with(['product.category', 'product.supplier', 'user'])->findOrFail($id);

        return view('pages.opnames.show', compact('opname'));
    }
}
