<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\StockTransaction;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    /**
     * Laporan Persediaan Stok Barang
     */
    public function stock(Request $request)
    {
        $categoryId = $request->query('category_id');
        $supplierId = $request->query('supplier_id');
        $stockStatus = $request->query('status'); // all, low, out, safe
        $search = $request->query('search');
        $export = $request->query('export');

        $query = Product::with(['category', 'supplier'])
            ->when($search, function ($q, $search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%");
                });
            })
            ->when($categoryId, function ($q, $categoryId) {
                $q->where('category_id', $categoryId);
            })
            ->when($supplierId, function ($q, $supplierId) {
                $q->where('supplier_id', $supplierId);
            })
            ->when($stockStatus, function ($q, $stockStatus) {
                if ($stockStatus === 'low') {
                    $q->whereColumn('stock', '<=', 'min_stock')->where('stock', '>', 0);
                } elseif ($stockStatus === 'out') {
                    $q->where('stock', '<=', 0);
                } elseif ($stockStatus === 'safe') {
                    $q->whereColumn('stock', '>', 'min_stock');
                }
            })
            ->orderBy('name');

        // Jika export CSV
        if ($export === 'csv') {
            return $this->exportStockCsv($query->get());
        }

        // Ringkasan Agregat
        $totalItems = (clone $query)->count();
        $totalQty = (clone $query)->sum('stock');
        $totalValuation = (clone $query)->reorder()->select(DB::raw('SUM(stock * purchase_price) as total'))->value('total') ?? 0;

        $products = $export === 'print' ? $query->get() : $query->paginate(20)->withQueryString();
        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();

        if ($export === 'print') {
            return view('pages.reports.stock_print', compact(
                'products',
                'categories',
                'suppliers',
                'categoryId',
                'supplierId',
                'stockStatus',
                'search',
                'totalItems',
                'totalQty',
                'totalValuation'
            ));
        }

        return view('pages.reports.stock', compact(
            'products',
            'categories',
            'suppliers',
            'categoryId',
            'supplierId',
            'stockStatus',
            'search',
            'totalItems',
            'totalQty',
            'totalValuation'
        ));
    }

    /**
     * Laporan Riwayat Mutasi Transaksi Barang
     */
    public function transactions(Request $request)
    {
        $dateFrom = $request->query('date_from');
        $dateTo = $request->query('date_to');
        $type = $request->query('type');
        $status = $request->query('status');
        $productId = $request->query('product_id');
        $search = $request->query('search');
        $export = $request->query('export');

        $query = StockTransaction::with(['product.category', 'supplier', 'user', 'confirmedByUser'])
            ->when($search, function ($q, $search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('invoice_number', 'like', "%{$search}%")
                        ->orWhere('notes', 'like', "%{$search}%")
                        ->orWhereHas('product', function ($pq) use ($search) {
                            $pq->where('name', 'like', "%{$search}%")
                                ->orWhere('sku', 'like', "%{$search}%");
                        });
                });
            })
            ->when($dateFrom, function ($q, $dateFrom) {
                $q->whereDate('created_at', '>=', $dateFrom);
            })
            ->when($dateTo, function ($q, $dateTo) {
                $q->whereDate('created_at', '<=', $dateTo);
            })
            ->when($type, function ($q, $type) {
                $q->where('type', $type);
            })
            ->when($status, function ($q, $status) {
                $q->where('status', $status);
            })
            ->when($productId, function ($q, $productId) {
                $q->where('product_id', $productId);
            })
            ->latest('id');

        // Jika export CSV
        if ($export === 'csv') {
            return $this->exportTransactionsCsv($query->get());
        }

        // Ringkasan Agregat
        $totalTransactions = (clone $query)->count();
        $inboundQty = (clone $query)->where('type', 'in')->where('status', 'confirmed')->sum('quantity');
        $outboundQty = (clone $query)->where('type', 'out')->where('status', 'confirmed')->sum('quantity');

        $transactions = $export === 'print' ? $query->get() : $query->paginate(20)->withQueryString();
        $products = Product::orderBy('name')->get(['id', 'name', 'sku']);

        if ($export === 'print') {
            return view('pages.reports.transactions_print', compact(
                'transactions',
                'products',
                'dateFrom',
                'dateTo',
                'type',
                'status',
                'productId',
                'search',
                'totalTransactions',
                'inboundQty',
                'outboundQty'
            ));
        }

        return view('pages.reports.transactions', compact(
            'transactions',
            'products',
            'dateFrom',
            'dateTo',
            'type',
            'status',
            'productId',
            'search',
            'totalTransactions',
            'inboundQty',
            'outboundQty'
        ));
    }

    /**
     * Generate file CSV Laporan Stok
     */
    protected function exportStockCsv($products): StreamedResponse
    {
        $fileName = 'laporan-stok-stockify-' . date('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($products) {
            $handle = fopen('php://output', 'w');
            // Menulis UTF-8 BOM agar rapi di Microsoft Excel
            fputs($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'No',
                'SKU Produk',
                'Nama Produk',
                'Kategori',
                'Supplier Utama',
                'Harga Beli (Rp)',
                'Harga Jual (Rp)',
                'Stok Fisik',
                'Batas Minimum Stok',
                'Total Nilai Persediaan (Rp)',
                'Status Ketersediaan'
            ], ';');

            $i = 1;
            foreach ($products as $p) {
                $val = $p->stock * $p->purchase_price;
                $status = $p->stock <= 0 ? 'Habis' : ($p->stock <= $p->min_stock ? 'Menipis' : 'Aman');

                fputcsv($handle, [
                    $i++,
                    $p->sku,
                    $p->name,
                    $p->category?->name ?? '-',
                    $p->supplier?->name ?? '-',
                    $p->purchase_price,
                    $p->selling_price,
                    $p->stock,
                    $p->min_stock,
                    $val,
                    $status
                ], ';');
            }

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ]);
    }

    /**
     * Generate file CSV Laporan Mutasi Transaksi
     */
    protected function exportTransactionsCsv($transactions): StreamedResponse
    {
        $fileName = 'laporan-mutasi-stockify-' . date('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($transactions) {
            $handle = fopen('php://output', 'w');
            fputs($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'No',
                'Tanggal & Waktu',
                'Nomor Invoice',
                'Tipe Mutasi',
                'SKU Produk',
                'Nama Produk',
                'Kuantitas (Unit)',
                'Harga Beli Satuan (Rp)',
                'Total Nilai (Rp)',
                'Supplier / Catatan',
                'Operator Penginput',
                'Status Otorisasi',
                'Diverifikasi Oleh'
            ], ';');

            $i = 1;
            foreach ($transactions as $t) {
                $unitPrice = $t->product?->purchase_price ?? 0;
                $totalVal = $unitPrice * $t->quantity;

                fputcsv($handle, [
                    $i++,
                    $t->created_at->format('Y-m-d H:i:s'),
                    $t->invoice_number,
                    $t->type === 'in' ? 'Barang Masuk (Inbound)' : 'Barang Keluar (Outbound)',
                    $t->product?->sku ?? '-',
                    $t->product?->name ?? 'Produk Dihapus',
                    $t->quantity,
                    $unitPrice,
                    $totalVal,
                    $t->supplier?->name ?? ($t->notes ?: '-'),
                    $t->user?->name ?? '-',
                    strtoupper($t->status),
                    $t->confirmedByUser?->name ?? '-'
                ], ';');
            }

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ]);
    }
}
