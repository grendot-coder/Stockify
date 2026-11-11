<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockTransaction;
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
        $products = Product::with(['category', 'supplier'])->orderBy('name')->get();
        $suggestedInvoice = $this->transactionService->generateInvoiceNumber('in');

        return view('pages.transactions.inbound_create', compact('products', 'suggestedInvoice'));
    }

    /**
     * Simpan transaksi barang masuk.
     * Otomatis disetujui (confirmed) dan stok langsung bertambah tanpa perlu konfirmasi manual lanjutan.
     */
    public function storeInbound(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
            'invoice_number' => ['nullable', 'string', 'max:50', 'unique:stock_transactions,invoice_number'],
            'quantity' => ['required', 'integer', 'min:1'],
            'status' => ['nullable', 'in:pending,confirmed'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ], [
            'product_id.required' => 'Pilih barang yang masuk ke gudang.',
            'quantity.required' => 'Kuantitas barang masuk wajib diisi.',
            'quantity.min' => 'Jumlah kuantitas minimal 1 unit.',
            'invoice_number.unique' => 'Nomor invoice ini sudah pernah digunakan. Mohon gunakan invoice yang unik.',
        ]);

        // Inbound otomatis confirmed agar langsung masuk stok tanpa butuh tombol konfirmasi
        $validated['status'] = $validated['status'] ?? 'confirmed';

        // Auto-generate invoice jika dikosongkan (anti-duplikasi otomatis)
        if (empty($validated['invoice_number'])) {
            $validated['invoice_number'] = $this->transactionService->generateInvoiceNumber('in');
        }

        $this->transactionService->createInbound($validated, Auth::id());

        return redirect()->route('transactions.index')->with('success', 'Transaksi Barang Masuk berhasil dicatat dan stok gudang otomatis langsung ditambahkan.');
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
            'status' => ['nullable', 'in:pending,confirmed'],
            'notes' => ['required', 'string', 'max:1000'],
        ], [
            'product_id.required' => 'Pilih barang yang akan dikeluarkan dari gudang.',
            'quantity.required' => 'Kuantitas barang keluar wajib diisi.',
            'quantity.min' => 'Jumlah kuantitas minimal 1 unit.',
            'notes.required' => 'Wajib mengisi alasan atau tujuan pengeluaran barang (cth: Penjualan, Retur Rusak, Distribusi).',
            'invoice_number.unique' => 'Nomor invoice ini sudah pernah digunakan. Mohon gunakan invoice yang unik.',
        ]);

        $validated['status'] = $validated['status'] ?? 'confirmed';

        if (empty($validated['invoice_number'])) {
            $validated['invoice_number'] = $this->transactionService->generateInvoiceNumber('out');
        }

        $this->transactionService->createOutbound($validated, Auth::id());

        return redirect()->route('transactions.index')->with('success', 'Transaksi Barang Keluar berhasil dicatat dan stok gudang otomatis dikurangi.');
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
     * Hanya Admin dan Manager yang berhak menyetujui transaksi fisik.
     */
    public function confirm($id)
    {
        if (! in_array(Auth::user()->role, ['admin', 'manager'])) {
            abort(403, 'Anda tidak memiliki hak akses untuk menyetujui transaksi.');
        }

        try {
            $this->transactionService->confirmTransaction($id, Auth::id());

            return redirect()->back()->with('success', 'Transaksi berhasil dikonfirmasi dan stok gudang telah diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Tolak transaksi pending.
     * Hanya Admin dan Manager yang berhak menolak transaksi fisik.
     */
    public function reject(Request $request, $id)
    {
        if (! in_array(Auth::user()->role, ['admin', 'manager'])) {
            abort(403, 'Anda tidak memiliki hak akses untuk menolak transaksi.');
        }

        $reason = $request->input('reason');
        $this->transactionService->rejectTransaction($id, Auth::id(), $reason);

        return redirect()->back()->with('success', 'Transaksi telah ditolak.');
    }

    /**
     * Endpoint API internal untuk generate nomor invoice baru anti-duplikasi.
     */
    public function generateInvoice(Request $request)
    {
        $type = $request->query('type', 'in');
        $invoice = $this->transactionService->generateInvoiceNumber($type === 'out' ? 'out' : 'in');

        return response()->json([
            'invoice' => $invoice,
            'message' => 'Nomor invoice baru berhasil digenerate dan dijamin unik.',
        ]);
    }

    /**
     * Endpoint API internal untuk verifikasi nomor invoice secara realtime (Anti-Duplikasi).
     */
    public function checkInvoice(Request $request)
    {
        $invoice = trim($request->query('invoice', ''));
        $type = $request->query('type', 'in');

        if ($invoice === '') {
            return response()->json([
                'available' => true,
                'is_empty' => true,
                'message' => 'Nomor invoice kosong akan digenerate otomatis oleh sistem.',
            ]);
        }

        $exists = StockTransaction::where('invoice_number', $invoice)->exists();

        if ($exists) {
            $suggested = $this->transactionService->generateInvoiceNumber($type === 'out' ? 'out' : 'in');
            return response()->json([
                'available' => false,
                'message' => "Nomor invoice '{$invoice}' sudah pernah digunakan dalam transaksi lain.",
                'suggested_invoice' => $suggested,
            ]);
        }

        return response()->json([
            'available' => true,
            'message' => "Nomor invoice '{$invoice}' unik dan siap digunakan.",
        ]);
    }
}
