<?php

namespace App\Http\Controllers;

use App\Services\Supplier\SupplierService;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    protected $supplierService;

    public function __construct(SupplierService $supplierService)
    {
        $this->supplierService = $supplierService;
    }

    /**
     * Tampilkan daftar supplier.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $suppliers = $this->supplierService->getPaginated($search, 10);

        return view('pages.suppliers.index', compact('suppliers', 'search'));
    }

    /**
     * Simpan supplier baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:500'],
        ], [
            'name.required' => 'Nama supplier wajib diisi.',
            'phone.required' => 'Nomor kontak telepon supplier wajib diisi.',
            'email.email' => 'Format email tidak valid.',
        ]);

        $this->supplierService->createSupplier($validated);

        return redirect()->route('suppliers.index')->with('success', 'Supplier baru berhasil ditambahkan.');
    }

    /**
     * Perbarui data supplier.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:500'],
        ], [
            'name.required' => 'Nama supplier wajib diisi.',
            'phone.required' => 'Nomor kontak telepon supplier wajib diisi.',
        ]);

        $this->supplierService->updateSupplier($id, $validated);

        return redirect()->route('suppliers.index')->with('success', 'Data supplier berhasil diperbarui.');
    }

    /**
     * Hapus supplier.
     */
    public function destroy($id)
    {
        try {
            $this->supplierService->deleteSupplier($id);
            return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('suppliers.index')->with('error', 'Gagal menghapus supplier: Masih terhubung dengan produk atau riwayat transaksi.');
        }
    }
}
