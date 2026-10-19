@extends('layouts.dashboard')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header Pengaturan -->
    <div class="bg-[#FFFFFF] rounded-2xl border border-[#E7E0DA] p-6 shadow-xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="w-2 h-2 rounded-full bg-[#BD6745]"></span>
                <span class="text-[11px] font-mono tracking-widest uppercase text-[#BD6745] font-semibold">Konfigurasi Sistem</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-editorial font-medium text-[#292524] tracking-tight">Pengaturan Umum Aplikasi</h1>
            <p class="text-xs text-[#78716C] mt-1">Kelola identitas merek, logo, informasi kontak gudang, dan kebijakan stok sistem</p>
        </div>
    </div>

    <!-- Alert Notifikasi -->
    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs shadow-xs">
            <div class="font-bold mb-1">Terdapat kesalahan pada input pengaturan:</div>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Formulir Pengaturan -->
    <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Seksi 1: Identitas Merek & Logo -->
        <div class="bg-[#FFFFFF] rounded-2xl border border-[#E7E0DA] p-6 shadow-xs space-y-5">
            <div class="pb-3 border-b border-[#E7E0DA]">
                <h2 class="font-heading text-base font-semibold text-[#292524]">Identitas Aplikasi & Logo</h2>
                <p class="text-xs text-[#78716C] mt-0.5">Atur nama sistem dan logo yang ditampilkan di antarmuka aplikasi</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
                <!-- Kolom Preview Logo -->
                <div class="space-y-3">
                    <label class="block text-xs font-semibold text-[#292524]">Logo Aplikasi Saat Ini</label>
                    <div class="w-full h-32 rounded-xl border border-dashed border-[#BD6745]/40 bg-[#FAF7F4] flex flex-col items-center justify-center p-3 text-center overflow-hidden">
                        @if($settings['app_logo'])
                            <img src="{{ asset('storage/' . $settings['app_logo']) }}" alt="Logo" class="max-h-20 max-w-full object-contain">
                        @else
                            <div class="w-12 h-12 rounded-xl bg-[#F5E6DE] text-[#A34E30] flex items-center justify-center font-bold text-xl mb-1">
                                {{ strtoupper(substr($settings['app_name'], 0, 1)) }}
                            </div>
                            <span class="text-[10px] text-[#78716C]">Logo Default Sistem</span>
                        @endif
                    </div>
                    <div>
                        <label for="logo" class="block text-[11px] font-medium text-[#78716C] mb-1">Ganti Logo (PNG, JPG, SVG, WebP, Maks 2MB)</label>
                        <input type="file" name="logo" id="logo" accept="image/*"
                            class="w-full text-xs text-[#78716C] file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#F5E6DE] file:text-[#A34E30] hover:file:bg-[#BD6745] hover:file:text-white file:transition cursor-pointer">
                    </div>
                </div>

                <!-- Input Nama & Slogan -->
                <div class="md:col-span-2 space-y-4">
                    <div>
                        <label for="app_name" class="block text-xs font-semibold text-[#292524] mb-1.5">
                            Nama Aplikasi / Gudang <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="app_name" id="app_name" value="{{ old('app_name', $settings['app_name']) }}" required
                            placeholder="Contoh: Stockify"
                            class="w-full px-3.5 py-2.5 text-xs bg-[#FAF7F4] border border-[#E7E0DA] rounded-xl text-[#292524] focus:bg-white focus:outline-none focus:border-[#BD6745] focus:ring-1 focus:ring-[#BD6745] transition">
                    </div>

                    <div>
                        <label for="app_tagline" class="block text-xs font-semibold text-[#292524] mb-1.5">
                            Slogan / Tagline Sistem
                        </label>
                        <input type="text" name="app_tagline" id="app_tagline" value="{{ old('app_tagline', $settings['app_tagline']) }}"
                            placeholder="Contoh: Warehouse & Supply Chain Management"
                            class="w-full px-3.5 py-2.5 text-xs bg-[#FAF7F4] border border-[#E7E0DA] rounded-xl text-[#292524] focus:bg-white focus:outline-none focus:border-[#BD6745] focus:ring-1 focus:ring-[#BD6745] transition">
                    </div>

                    <div>
                        <label for="default_min_stock" class="block text-xs font-semibold text-[#292524] mb-1.5">
                            Batas Minimum Stok Bawaan (Default Min Stock) <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative w-44">
                            <input type="number" name="default_min_stock" id="default_min_stock" min="0" value="{{ old('default_min_stock', $settings['default_min_stock']) }}" required
                                class="w-full px-3.5 py-2.5 text-xs bg-[#FAF7F4] border border-[#E7E0DA] rounded-xl text-[#292524] focus:bg-white focus:outline-none focus:border-[#BD6745] focus:ring-1 focus:ring-[#BD6745] transition">
                            <span class="absolute right-3.5 top-2.5 text-xs text-[#78716C]">Unit</span>
                        </div>
                        <span class="text-[11px] text-[#78716C] mt-1 block">Batas acuan peringatan restock saat produk baru ditambahkan tanpa batas minimum khusus.</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Seksi 2: Operasional & Kontak Gudang -->
        <div class="bg-[#FFFFFF] rounded-2xl border border-[#E7E0DA] p-6 shadow-xs space-y-4">
            <div class="pb-3 border-b border-[#E7E0DA]">
                <h2 class="font-heading text-base font-semibold text-[#292524]">Informasi Operasional & Kontak Gudang</h2>
                <p class="text-xs text-[#78716C] mt-0.5">Informasi resmi yang dicantumkan pada cetak faktur dan laporan fisik</p>
            </div>

            <div>
                <label for="warehouse_address" class="block text-xs font-semibold text-[#292524] mb-1.5">
                    Alamat Fisik Gudang / Lokasi Fasilitas
                </label>
                <textarea name="warehouse_address" id="warehouse_address" rows="2"
                    placeholder="Contoh: Kawasan Pergudangan Logistik Blok B-12, Jakarta"
                    class="w-full px-3.5 py-2.5 text-xs bg-[#FAF7F4] border border-[#E7E0DA] rounded-xl text-[#292524] focus:bg-white focus:outline-none focus:border-[#BD6745] focus:ring-1 focus:ring-[#BD6745] transition">{{ old('warehouse_address', $settings['warehouse_address']) }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="warehouse_phone" class="block text-xs font-semibold text-[#292524] mb-1.5">
                        Nomor Telepon Operasional
                    </label>
                    <input type="text" name="warehouse_phone" id="warehouse_phone" value="{{ old('warehouse_phone', $settings['warehouse_phone']) }}"
                        placeholder="+62 812-3456-7890"
                        class="w-full px-3.5 py-2.5 text-xs bg-[#FAF7F4] border border-[#E7E0DA] rounded-xl text-[#292524] focus:bg-white focus:outline-none focus:border-[#BD6745] focus:ring-1 focus:ring-[#BD6745] transition">
                </div>
                <div>
                    <label for="warehouse_email" class="block text-xs font-semibold text-[#292524] mb-1.5">
                        Alamat Email Operasional
                    </label>
                    <input type="email" name="warehouse_email" id="warehouse_email" value="{{ old('warehouse_email', $settings['warehouse_email']) }}"
                        placeholder="operasional@stockify.id"
                        class="w-full px-3.5 py-2.5 text-xs bg-[#FAF7F4] border border-[#E7E0DA] rounded-xl text-[#292524] focus:bg-white focus:outline-none focus:border-[#BD6745] focus:ring-1 focus:ring-[#BD6745] transition">
                </div>
            </div>
        </div>

        <!-- Tombol Simpan -->
        <div class="flex items-center justify-end gap-3 pt-2">
            <button type="submit"
                class="px-6 py-2.5 text-xs font-semibold bg-[#A34E30] hover:bg-[#8A3F25] text-white rounded-xl shadow-xs transition duration-150 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>Simpan Pengaturan Aplikasi</span>
            </button>
        </div>

    </form>

</div>
@endsection
