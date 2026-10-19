@extends('layouts.dashboard')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <!-- Header & Navigasi Kembali -->
    <div class="flex items-center justify-between">
        <a href="{{ route('opnames.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#78716C] hover:text-[#292524] transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Kembali ke Daftar Opname
        </a>
        <span class="text-[11px] font-mono uppercase tracking-wider text-[#BD6745] font-semibold bg-[#F5E6DE] px-2.5 py-1 rounded-lg">
            Sesi Rekonsiliasi Gudang
        </span>
    </div>

    <div>
        <h1 class="font-heading text-2xl sm:text-3xl font-semibold text-[#292524] tracking-tight">Formulir Stock Opname</h1>
        <p class="text-xs text-[#78716C] mt-1">Lakukan penghitungan fisik langsung di rak gudang untuk memvalidasi akurasi persediaan sistem.</p>
    </div>

    <!-- Alert Notifikasi Error -->
    @if(session('error') || $errors->any())
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-center gap-2 shadow-xs">
            <svg class="w-4 h-4 text-rose-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            <span>{{ session('error') ?? $errors->first() }}</span>
        </div>
    @endif

    <!-- Formulir Utama -->
    <form action="{{ route('opnames.store') }}" method="POST" class="bg-[#FFFFFF] rounded-2xl border border-[#E7E0DA] p-6 sm:p-8 shadow-xs space-y-6">
        @csrf

        <!-- 1. Pemilihan Produk -->
        <div>
            <label for="product_id" class="block text-xs font-semibold text-[#292524] mb-1.5 uppercase tracking-wider">
                Pilih Produk / Barang <span class="text-[#BD6745]">*</span>
            </label>
            <select name="product_id" id="product_id" required
                class="w-full px-3.5 py-2.5 text-xs bg-[#FFFFFF] border border-[#E7E0DA] rounded-xl text-[#292524] focus:outline-none focus:border-[#BD6745] focus:ring-1 focus:ring-[#BD6745]">
                <option value="">-- Cari atau Pilih Produk Gudang --</option>
                @foreach($products as $p)
                    <option value="{{ $p->id }}" 
                        data-stock="{{ $p->stock }}" 
                        data-sku="{{ $p->sku }}"
                        data-category="{{ $p->category?->name ?? '-' }}"
                        {{ old('product_id', request('product_id')) == $p->id ? 'selected' : '' }}>
                        {{ $p->sku }} — {{ $p->name }} (Tersedia: {{ $p->stock }} Unit)
                    </option>
                @endforeach
            </select>
            <p class="text-[11px] text-[#78716C] mt-1.5">Pilih item barang yang baru saja dihitung fisiknya di lokasi rak gudang.</p>
        </div>

        <!-- 2. Kotak Komparasi & Kalkulasi Selisih Realtime -->
        <div id="comparison-card" class="bg-[#FAF7F4] border border-[#E7E0DA] rounded-xl p-5 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-[#E7E0DA]">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-[#BD6745]"></span>
                    <h3 class="font-heading font-semibold text-xs text-[#292524] uppercase tracking-wider">Komparasi Fisik vs Sistem</h3>
                </div>
                <span id="diff-status-pill" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-gray-100 text-gray-700">
                    Menunggu Pilihan Produk
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <!-- Stok Sistem -->
                <div class="bg-[#FFFFFF] p-3.5 rounded-xl border border-[#E7E0DA] text-center">
                    <span class="text-[10px] font-semibold text-[#78716C] uppercase tracking-wider">Stok Sistem Saat Ini</span>
                    <div id="display-system-stock" class="font-heading text-2xl font-bold tabular-nums text-[#292524] mt-1">-</div>
                    <span id="display-sku" class="text-[10px] font-mono text-[#78716C]">SKU: -</span>
                </div>

                <!-- Input Stok Fisik -->
                <div class="bg-[#FFFFFF] p-3.5 rounded-xl border-2 border-[#BD6745]/40 text-center relative">
                    <label for="physical_stock" class="block text-[10px] font-bold text-[#BD6745] uppercase tracking-wider">
                        Hasil Hitung Fisik <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" name="physical_stock" id="physical_stock" min="0" required
                        value="{{ old('physical_stock') }}" placeholder="0"
                        class="w-full text-center font-heading text-2xl font-bold tabular-nums text-[#292524] mt-1 border-0 border-b border-[#E7E0DA] focus:border-[#BD6745] focus:ring-0 p-0 bg-transparent">
                    <span class="text-[10px] text-[#78716C]">Unit Fisik di Rak</span>
                </div>

                <!-- Hasil Selisih -->
                <div class="bg-[#FFFFFF] p-3.5 rounded-xl border border-[#E7E0DA] text-center">
                    <span class="text-[10px] font-semibold text-[#78716C] uppercase tracking-wider">Selisih Persediaan</span>
                    <div id="display-difference" class="font-heading text-2xl font-bold tabular-nums text-gray-400 mt-1">0</div>
                    <span id="display-diff-label" class="text-[10px] text-[#78716C]">Perbedaan (Fisik - Sistem)</span>
                </div>
            </div>
        </div>

        <!-- 3. Tanggal Opname & Catatan -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label for="opname_date" class="block text-xs font-semibold text-[#292524] mb-1.5 uppercase tracking-wider">
                    Tanggal Pelaksanaan Opname <span class="text-[#BD6745]">*</span>
                </label>
                <input type="date" name="opname_date" id="opname_date" required
                    value="{{ old('opname_date', date('Y-m-d')) }}"
                    class="w-full px-3.5 py-2.5 text-xs bg-[#FFFFFF] border border-[#E7E0DA] rounded-xl text-[#292524] focus:outline-none focus:border-[#BD6745] focus:ring-1 focus:ring-[#BD6745]">
            </div>

            <div>
                <label class="block text-xs font-semibold text-[#292524] mb-1.5 uppercase tracking-wider">
                    Petugas Pelaksana
                </label>
                <input type="text" disabled value="{{ Auth::user()->name }} ({{ ucfirst(Auth::user()->role) }})"
                    class="w-full px-3.5 py-2.5 text-xs bg-[#FAF7F4] border border-[#E7E0DA] rounded-xl text-[#78716C] cursor-not-allowed">
            </div>
        </div>

        <div>
            <label for="notes" class="block text-xs font-semibold text-[#292524] mb-1.5 uppercase tracking-wider">
                Catatan Analisis Selisih / Keterangan Kondisi Barang
            </label>
            <textarea name="notes" id="notes" rows="3" placeholder="Contoh: Ditemukan 2 unit rusak di palet B3, atau barang belum dicatat saat penerimaan..."
                class="w-full px-3.5 py-2.5 text-xs bg-[#FFFFFF] border border-[#E7E0DA] rounded-xl text-[#292524] placeholder-[#78716C]/60 focus:outline-none focus:border-[#BD6745] focus:ring-1 focus:ring-[#BD6745]">{{ old('notes') }}</textarea>
        </div>

        <!-- 4. Checkbox Opsi Penyesuaian Stok Sistem (Khusus Admin / Manager) -->
        @if(in_array(Auth::user()->role, ['admin', 'manager']))
        <div class="p-4 rounded-xl bg-[#FAF7F4] border border-[#E7E0DA] flex items-start gap-3">
            <input type="checkbox" name="adjust_stock" id="adjust_stock" value="1" {{ old('adjust_stock') ? 'checked' : '' }}
                class="mt-0.5 rounded border-[#E7E0DA] text-[#BD6745] focus:ring-[#BD6745] cursor-pointer">
            <label for="adjust_stock" class="text-xs text-[#292524] cursor-pointer">
                <span class="font-bold text-[#292524]">Sinkronkan & Sesuaikan Stok Sistem Gudang Secara Otomatis</span>
                <p class="text-[11px] text-[#78716C] mt-0.5 leading-relaxed">
                    Jika dicentang, angka stok sistem pada produk akan langsung diperbarui menjadi sama persis dengan angka stok fisik aktual. Transaksi penyesuaian otomatis akan dibuat untuk audit log.
                </p>
            </label>
        </div>
        @else
        <div class="p-3.5 rounded-xl bg-gray-50 border border-gray-200 text-gray-500 text-xs flex items-center gap-2">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>Sebagai Staff Gudang, hasil opname akan direkam sebagai laporan audit. Rekonsiliasi penyesuaian stok sistem diotorisasi oleh Manajer Gudang.</span>
        </div>
        @endif

        <!-- Action Buttons -->
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-[#E7E0DA]">
            <a href="{{ route('opnames.index') }}"
                class="px-4 py-2.5 text-xs font-semibold text-[#78716C] hover:text-[#292524] hover:bg-[#FAF7F4] rounded-xl transition">
                Batal
            </a>
            <button type="submit"
                class="inline-flex items-center gap-2 px-6 py-3 bg-[#BD6745] hover:bg-[#A55636] text-[#1C1917] text-xs font-bold uppercase tracking-wider rounded-xl shadow-xs transition cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
                <span>Simpan Hasil Opname</span>
            </button>
        </div>

    </form>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const productSelect = document.getElementById('product_id');
        const physicalInput = document.getElementById('physical_stock');
        const systemDisplay = document.getElementById('display-system-stock');
        const skuDisplay = document.getElementById('display-sku');
        const diffDisplay = document.getElementById('display-difference');
        const diffStatusPill = document.getElementById('diff-status-pill');
        const diffLabel = document.getElementById('display-diff-label');

        function recalculate() {
            const selected = productSelect.options[productSelect.selectedIndex];
            if (!selected || !selected.value) {
                systemDisplay.textContent = '-';
                skuDisplay.textContent = 'SKU: -';
                diffDisplay.textContent = '0';
                diffDisplay.className = 'font-heading text-2xl font-bold tabular-nums text-gray-400 mt-1';
                diffStatusPill.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-gray-100 text-gray-700';
                diffStatusPill.textContent = 'Menunggu Pilihan Produk';
                diffLabel.textContent = 'Perbedaan (Fisik - Sistem)';
                return;
            }

            const systemStock = parseInt(selected.getAttribute('data-stock')) || 0;
            const sku = selected.getAttribute('data-sku') || '-';
            systemDisplay.textContent = systemStock.toLocaleString('id-ID') + ' Unit';
            skuDisplay.textContent = 'SKU: ' + sku;

            const physicalVal = physicalInput.value.trim();
            if (physicalVal === '') {
                diffDisplay.textContent = '0';
                diffDisplay.className = 'font-heading text-2xl font-bold tabular-nums text-gray-400 mt-1';
                diffStatusPill.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-amber-50 text-amber-800 border border-amber-200';
                diffStatusPill.textContent = 'Masukkan Angka Fisik';
                diffLabel.textContent = 'Hitung fisik di rak gudang';
                return;
            }

            const physicalStock = parseInt(physicalVal) || 0;
            const difference = physicalStock - systemStock;

            if (difference === 0) {
                diffDisplay.textContent = '0 Unit';
                diffDisplay.className = 'font-heading text-2xl font-bold tabular-nums text-emerald-700 mt-1';
                diffStatusPill.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200';
                diffStatusPill.textContent = 'Cocok / Sesuai Sempurna';
                diffLabel.textContent = 'Tidak ada selisih stok';
            } else if (difference > 0) {
                diffDisplay.textContent = '+' + difference.toLocaleString('id-ID') + ' Unit';
                diffDisplay.className = 'font-heading text-2xl font-bold tabular-nums text-amber-700 mt-1';
                diffStatusPill.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-amber-50 text-amber-800 border border-amber-200';
                diffStatusPill.textContent = 'Surplus (Lebih Banyak)';
                diffLabel.textContent = 'Stok fisik lebih banyak dari sistem';
            } else {
                diffDisplay.textContent = difference.toLocaleString('id-ID') + ' Unit';
                diffDisplay.className = 'font-heading text-2xl font-bold tabular-nums text-rose-700 mt-1';
                diffStatusPill.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-rose-50 text-rose-800 border border-rose-200';
                diffStatusPill.textContent = 'Defisit (Kurang)';
                diffLabel.textContent = 'Stok fisik lebih sedikit dari sistem';
            }
        }

        productSelect.addEventListener('change', recalculate);
        physicalInput.addEventListener('input', recalculate);

        // Jalankan initial check jika ada old values
        recalculate();
    });
</script>
@endsection
