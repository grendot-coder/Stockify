<?php

namespace App\Http\Controllers;

use App\Services\RackLocation\RackLocationService;
use Illuminate\Http\Request;

class RackLocationController extends Controller
{
    protected $rackLocationService;

    public function __construct(RackLocationService $rackLocationService)
    {
        $this->rackLocationService = $rackLocationService;
    }

    /**
     * Menampilkan visualizer Dot-Matrix dan daftar rak gudang.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $locations = $this->rackLocationService->getPaginated($search, 12);
        $allRacks = $this->rackLocationService->getAllWithStock();
        $stats = $this->rackLocationService->getMatrixStats();

        return view('pages.locations.index', compact('locations', 'allRacks', 'stats', 'search'));
    }

    /**
     * Menyimpan data rak baru dengan format standar "Rack-0X".
     */
    public function store(Request $request)
    {
        // Normalisasi nama jika hanya menginput angka (misal "1" -> "Rack-01", "Rack 1" -> "Rack-01")
        $rawName = trim((string) $request->input('name'));
        if (preg_match('/^\d+$/', $rawName)) {
            $formattedName = 'Rack-'.sprintf('%02d', (int) $rawName);
            $request->merge(['name' => $formattedName]);
        } elseif (preg_match('/^rack[\s\-_]*(\d+)$/i', $rawName, $matches)) {
            $formattedName = 'Rack-'.sprintf('%02d', (int) $matches[1]);
            $request->merge(['name' => $formattedName]);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50', 'unique:rack_locations,name', 'regex:/^Rack-\d+$/i'],
            'capacity' => ['required', 'integer', 'min:1', 'max:100000'],
            'zone' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:1000'],
        ], [
            'name.required' => 'Nama rak wajib diisi.',
            'name.unique' => 'Nama rak ini sudah terdaftar.',
            'name.regex' => 'Format nama rak harus menggunakan penamaan "Rack-0X" (contoh: Rack-01, Rack-02).',
            'capacity.required' => 'Kapasitas maksimum unit wajib diisi.',
            'capacity.min' => 'Kapasitas minimal 1 unit.',
        ]);

        $this->rackLocationService->createRackLocation($validated);

        return redirect()->route('locations.index')->with('success', "Lokasi {$validated['name']} berhasil ditambahkan.");
    }

    /**
     * Memperbarui data lokasi rak.
     */
    public function update(Request $request, $id)
    {
        // Normalisasi nama
        $rawName = trim((string) $request->input('name'));
        if (preg_match('/^\d+$/', $rawName)) {
            $formattedName = 'Rack-'.sprintf('%02d', (int) $rawName);
            $request->merge(['name' => $formattedName]);
        } elseif (preg_match('/^rack[\s\-_]*(\d+)$/i', $rawName, $matches)) {
            $formattedName = 'Rack-'.sprintf('%02d', (int) $matches[1]);
            $request->merge(['name' => $formattedName]);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50', "unique:rack_locations,name,{$id}", 'regex:/^Rack-\d+$/i'],
            'capacity' => ['required', 'integer', 'min:1', 'max:100000'],
            'zone' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:1000'],
        ], [
            'name.required' => 'Nama rak wajib diisi.',
            'name.unique' => 'Nama rak ini sudah terdaftar.',
            'name.regex' => 'Format nama rak harus menggunakan penamaan "Rack-0X" (contoh: Rack-01, Rack-02).',
            'capacity.required' => 'Kapasitas maksimum unit wajib diisi.',
            'capacity.min' => 'Kapasitas minimal 1 unit.',
        ]);

        $this->rackLocationService->updateRackLocation($id, $validated);

        return redirect()->route('locations.index')->with('success', "Data {$validated['name']} berhasil diperbarui.");
    }

    /**
     * Menghapus lokasi rak.
     */
    public function destroy($id)
    {
        try {
            $this->rackLocationService->deleteRackLocation($id);

            return redirect()->route('locations.index')->with('success', 'Lokasi rak berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('locations.index')->with('error', 'Gagal menghapus lokasi rak: Masih ada produk yang disimpan di rak ini.');
        }
    }
}
