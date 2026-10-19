<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    /**
     * Tampilkan formulir pengaturan umum aplikasi.
     */
    public function index()
    {
        $settings = [
            'app_name' => Setting::get('app_name', 'Stockify'),
            'app_tagline' => Setting::get('app_tagline', 'Warehouse & Supply Chain System'),
            'app_logo' => Setting::get('app_logo', null),
            'warehouse_address' => Setting::get('warehouse_address', 'Kawasan Industri Pergudangan Terpadu Blok B-12, Jakarta'),
            'warehouse_phone' => Setting::get('warehouse_phone', '0812-3456-7890'),
            'warehouse_email' => Setting::get('warehouse_email', 'operasional@stockify.id'),
            'default_min_stock' => Setting::get('default_min_stock', 5),
        ];

        return view('pages.settings.index', compact('settings'));
    }

    /**
     * Simpan pembaruan pengaturan aplikasi.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'app_name' => ['required', 'string', 'max:100'],
            'app_tagline' => ['nullable', 'string', 'max:255'],
            'warehouse_address' => ['nullable', 'string', 'max:500'],
            'warehouse_phone' => ['nullable', 'string', 'max:50'],
            'warehouse_email' => ['nullable', 'email', 'max:100'],
            'default_min_stock' => ['required', 'integer', 'min:0'],
            'logo' => ['nullable', 'image', 'mimes:png,jpg,jpeg,svg,webp', 'max:2048'],
        ], [
            'app_name.required' => 'Nama aplikasi atau nama gudang wajib diisi.',
            'default_min_stock.required' => 'Batas minimum stok default wajib diisi.',
            'logo.image' => 'Berkas logo harus berupa gambar (PNG, JPG, SVG, WebP).',
            'logo.max' => 'Ukuran berkas logo maksimal 2 MB.',
        ]);

        Setting::set('app_name', $validated['app_name']);
        Setting::set('app_tagline', $validated['app_tagline'] ?? '');
        Setting::set('warehouse_address', $validated['warehouse_address'] ?? '');
        Setting::set('warehouse_phone', $validated['warehouse_phone'] ?? '');
        Setting::set('warehouse_email', $validated['warehouse_email'] ?? '');
        Setting::set('default_min_stock', (string) $validated['default_min_stock']);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('settings', 'public');
            Setting::set('app_logo', $path);
        }

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'UPDATE_SETTINGS',
            'description' => "Memperbarui konfigurasi identitas aplikasi: {$validated['app_name']}",
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('settings.index')->with('success', 'Pengaturan umum aplikasi berhasil disimpan dan diperbarui.');
    }
}
