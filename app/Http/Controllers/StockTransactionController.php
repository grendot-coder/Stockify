<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockTransaction;
use App\Models\Supplier;
use App\Services\StockTransaction\StockTransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockTransactionController extends Controller
{
    protected $transactionService;

    public function __construct(StockTransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * Tampilkan riwayat seluruh transaksi stok.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $type = $request->query('type');
        $status = $request->query('status');
        $productId = $request->query('product_id');

        $transactions = $this->transactionService->getPaginated(
            $search,
            $type,
            $status,
            $productId ? intval($productId) : null,
            15
        );

        $products = Product::orderBy('name')->get();

        // Statistik ringkas transaksi
        $stats = [
            'total' => StockTransaction::count(),
            'inbound_count' => StockTransaction::where('type', 'in')->where('status', 'confirmed')->count(),
            'outbound_count' => StockTransaction::where('type', 'out')->where('status', 'confirmed')->count(),
            'pending_count' => StockTransaction::where('status', 'pending')->count(),
        ];

        return view('pages.transactions.index', compact(
            'transactions',
            'products',
            'search',
            'type',
            'status',
            'productId',
            'stats'
        ));
    }

    /**
     * Formulir penerimaan barang masuk (Inbound).
     */
    public function createInbound()
    {
        $products = Product::with('category')->orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();
        $suggestedInvoice = $this->transactionService->generateInvoiceNumber('in');

        return view('pages.transactions.inbound_create', compact('products', 'suppliers', 'suggestedInvoice'));
    }

    /**
     * Simpan transaksi barang masuk.
     */
    public function storeInbound(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
            'invoice_number' => ['nullable', 'string', 'max:50', 'unique:stock_transactions,invoice_number'],
            'quantity' => ['required', 'integer', 'min:1'],
            'status' => ['required', 'in:pending,confirmed'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ], [
            'product_id.required' => 'Pilih barang yang masuk ke gudang.',
            'quantity.required' => 'Kuantitas barang masuk wajib diisi.',
            'quantity.min' => 'Jumlah kuantitas minimal 1 unit.',
        ]);

        $this->transactionService->createInbound($validated, Auth::id());

        $message = $validated['status'] === 'confirmed'
            ? 'Transaksi Barang Masuk berhasil dicatat dan stok gudang otomatis ditambahkan!'
            : 'Pengajuan Barang Masuk berhasil disimpan (menunggu persetujuan/verifikasi)!';

        return redirect()->route('transactions.index')->with('success', $message);
    }

    /**
     * Formulir pengeluaran barang keluar (Outbound).
     */
    public function createOutbound()
    {
        $products = Product::with('category')->orderBy('name')->get();
        $suggestedInvoice = $this->transactionService->generateInvoiceNumber('out');

        return view('pages.transactions.outbound_create', compact('products', 'suggestedInvoice'));
    }

    /**
     * Simpan transaksi barang keluar.
     */
    public function storeOutbound(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'invoice_number' => ['nullable', 'string', 'max:50', 'unique:stock_transactions,invoice_number'],
            'quantity' => ['required', 'integer', 'min:1'],
            'status' => ['required', 'in:pending,confirmed'],
            'notes' => ['required', 'string', 'max:1000'],
        ], [
            'product_id.required' => 'Pilih barang yang akan dikeluarkan dari gudang.',
            'quantity.required' => 'Kuantitas barang keluar wajib diisi.',
            'quantity.min' => 'Jumlah kuantitas minimal 1 unit.',
            'notes.required' => 'Wajib mengisi alasan atau tujuan pengeluaran barang (cth: Penjualan, Retur Rusak, Distribusi).',
        ]);

        $this->transactionService->createOutbound($validated, Auth::id());

        $message = $validated['status'] === 'confirmed'
            ? 'Transaksi Barang Keluar berhasil dicatat dan stok gudang otomatis dikurangi!'
            : 'Pengajuan Barang Keluar berhasil disimpan (menunggu persetujuan)!';

        return redirect()->route('transactions.index')->with('success', $message);
    }

    /**
     * Tampilkan detail transaksi.
     */
    public function show($id)
    {
        $transaction = StockTransaction::with(['product.category', 'supplier', 'user', 'confirmedByUser'])
            ->findOrFail($id);

        return view('pages.transactions.show', compact('transaction'));
    }

    /**
     * Konfirmasi / Setujui transaksi pending.
     */
    public function confirm($id)
    {
        // Hanya Admin dan Manager yang berhak menyetujui transaksi
        if (!in_array(Auth::user()->role, ['admin', 'manager'])) {
            abort(403, 'Anda tidak memiliki hak akses untuk menyetujui transaksi.');
        }

        try {
            $this->transactionService->confirmTransaction($id, Auth::id());
            return redirect()->back()->with('success', 'Transaksi berhasil disetujui dan stok gudang telah diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Tolak transaksi pending.
     */
    public function reject(Request $request, $id)
    {
        // Hanya Admin dan Manager yang berhak menolak transaksi
        if (!in_array(Auth::user()->role, ['admin', 'manager'])) {
            abort(403, 'Anda tidak memiliki hak akses untuk menolak transaksi.');
        }

        $reason = $request->input('reason');
        $this->transactionService->rejectTransaction($id, Auth::id(), $reason);

        return redirect()->back()->with('success', 'Transaksi telah ditolak.');
    }
}
