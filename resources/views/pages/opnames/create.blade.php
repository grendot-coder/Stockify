@extends('layouts.dashboard')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <!-- Header & Navigasi Kembali -->
    <div class="flex items-center justify-between">
        <a href="{{ route('opnames.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#A1A1AA] hover:text-[#F5F5F5] transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Kembali ke Daftar Opname
        </a>
        <span class="text-[11px] font-mono uppercase tracking-wider text-[#F5C842] font-semibold bg-[#F5C842]/15 px-2.5 py-1 rounded-lg">
            Sesi Rekonsiliasi Gudang
        </span>
    </div>

    <div>
        <h1 class="font-heading text-2xl sm:text-3xl font-semibold text-[#F5F5F5] tracking-tight">Formulir Stock Opname</h1>
        <p class="text-xs text-[#A1A1AA] mt-1">Lakukan penghitungan fisik langsung di rak gudang untuk memvalidasi akurasi persediaan sistem.</p>
    </div>

    <!-- Alert Notifikasi Error -->
    @if(session('error') || $errors->any())
        <div class="p-4 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs flex items-center gap-2 shadow-xs">
            <svg class="w-4 h-4 text-rose-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            <span>{{ session('error') ?? $errors->first() }}</span>
        </div>
    @endif

    <!-- Formulir Utama -->
    <form action="{{ route('opnames.store') }}" method="POST" class="bg-[#202429] rounded-2xl border border-[#343A40] p-6 sm:p-8 shadow-xs space-y-6">
        @csrf

        <!-- 1. Pemilihan Produk -->
        <div>
            <label for="product_id" class="block text-xs font-semibold text-[#F5F5F5] mb-1.5 uppercase tracking-wider">
                Pilih Produk / Barang <span class="text-[#F5C842]">*</span>
            </label>
            <select name="product_id" id="product_id" required
                class="w-full px-3.5 py-2.5 text-xs bg-[#202429] border border-[#343A40] rounded-xl text-[#F5F5F5] focus:outline-none focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842]">
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
            <p class="text-[11px] text-[#A1A1AA] mt-1.5">Pilih item barang yang baru saja dihitung fisiknya di lokasi rak gudang.</p>
        </div>

        <!-- 2. Kotak Komparasi & Kalkulasi Selisih Realtime -->
        <div id="comparison-card" class="bg-[#181C20] border border-[#343A40] rounded-xl p-5 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-[#343A40]">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-[#F5C842]"></span>
                    <h3 class="font-heading font-semibold text-xs text-[#F5F5F5] uppercase tracking-wider">Komparasi Fisik vs Sistem</h3>
                </div>
                <span id="diff-status-pill" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-[#181C20] text-[#A1A1AA] border border-[#343A40]">
                    Menunggu Pilihan Produk
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <!-- Stok Sistem -->
                <div class="bg-[#202429] p-3.5 rounded-xl border border-[#343A40] text-center">
                    <span class="text-[10px] font-semibold text-[#A1A1AA] uppercase tracking-wider">Stok Sistem Saat Ini</span>
                    <div id="display-system-stock" class="font-heading text-2xl font-bold tabular-nums text-[#F5F5F5] mt-1">-</div>
                    <span id="display-sku" class="text-[10px] font-mono text-[#A1A1AA]">SKU: -</span>
                </div>

                <!-- Input Stok Fisik -->
                <div class="bg-[#202429] p-3.5 rounded-xl border-2 border-[#F5C842]/40 text-center relative">
                    <label for="physical_stock" class="block text-[10px] font-bold text-[#F5C842] uppercase tracking-wider">
                        Hasil Hitung Fisik <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" name="physical_stock" id="physical_stock" min="0" required
                        value="{{ old('physical_stock') }}" placeholder="0"
                        class="w-full text-center font-heading text-2xl font-bold tabular-nums text-[#F5F5F5] mt-1 border-0 border-b border-[#343A40] focus:border-[#F5C842] focus:ring-0 p-0 bg-transparent">
                    <span class="text-[10px] text-[#A1A1AA]">Unit Fisik di Rak</span>
                </div>

                <!-- Hasil Selisih -->
                <div class="bg-[#202429] p-3.5 rounded-xl border border-[#343A40] text-center">
                    <span class="text-[10px] font-semibold text-[#A1A1AA] uppercase tracking-wider">Selisih Persediaan</span>
                    <div id="display-difference" class="font-heading text-2xl font-bold tabular-nums text-gray-400 mt-1">0</div>
                    <span id="display-diff-label" class="text-[10px] text-[#A1A1AA]">Perbedaan (Fisik - Sistem)</span>
                </div>
            </div>
        </div>

        <!-- 3. Tanggal Opname & Catatan -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label for="opname_date" class="block text-xs font-semibold text-[#F5F5F5] mb-1.5 uppercase tracking-wider">
                    Tanggal Pelaksanaan Opname <span class="text-[#F5C842]">*</span>
                </label>
                <input type="date" name="opname_date" id="opname_date" required
                    value="{{ old('opname_date', date('Y-m-d')) }}"
                    class="w-full px-3.5 py-2.5 text-xs bg-[#202429] border border-[#343A40] rounded-xl text-[#F5F5F5] focus:outline-none focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842]">
            </div>

            <div>
                <label class="block text-xs font-semibold text-[#F5F5F5] mb-1.5 uppercase tracking-wider">
                    Petugas Pelaksana
                </label>
                <input type="text" disabled value="{{ Auth::user()->name }} ({{ ucfirst(Auth::user()->role) }})"
                    class="w-full px-3.5 py-2.5 text-xs bg-[#181C20] border border-[#343A40] rounded-xl text-[#A1A1AA] cursor-not-allowed">
            </div>
        </div>

        <div>
            <label for="notes" class="block text-xs font-semibold text-[#F5F5F5] mb-1.5 uppercase tracking-wider">
                Catatan Analisis Selisih / Keterangan Kondisi Barang
            </label>
            <textarea name="notes" id="notes" rows="3" placeholder="Contoh: Ditemukan 2 unit rusak di palet B3, atau barang belum dicatat saat penerimaan..."
                class="w-full px-3.5 py-2.5 text-xs bg-[#202429] border border-[#343A40] rounded-xl text-[#F5F5F5] placeholder-[#A1A1AA]/60 focus:outline-none focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842]">{{ old('notes') }}</textarea>
        </div>

        <!-- 4. Checkbox Opsi Penyesuaian Stok Sistem (Khusus Admin / Manager) -->
        @if(in_array(Auth::user()->role, ['admin', 'manager']))
        <div class="p-4 rounded-xl bg-[#181C20] border border-[#343A40] flex items-start gap-3">
            <input type="checkbox" name="adjust_stock" id="adjust_stock" value="1" {{ old('adjust_stock') ? 'checked' : '' }}
                class="mt-0.5 rounded border-[#343A40] text-[#F5C842] focus:ring-[#F5C842] cursor-pointer">
            <label for="adjust_stock" class="text-xs text-[#F5F5F5] cursor-pointer">
                <span class="font-bold text-[#F5F5F5]">Sinkronkan & Sesuaikan Stok Sistem Gudang Secara Otomatis</span>
                <p class="text-[11px] text-[#A1A1AA] mt-0.5 leading-relaxed">
                    Jika dicentang, angka stok sistem pada produk akan langsung diperbarui menjadi sama persis dengan angka stok fisik aktual. Transaksi penyesuaian otomatis akan dibuat untuk audit log.
                </p>
            </label>
        </div>
        @else
        <div class="p-3.5 rounded-xl bg-[#181C20] border border-[#343A40] text-[#A1A1AA] text-xs flex items-center gap-2">
            <svg class="w-4 h-4 text-[#F5C842] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>Sebagai Staff Gudang, hasil opname akan direkam sebagai laporan audit. Rekonsiliasi penyesuaian stok sistem diotorisasi oleh Manajer Gudang.</span>
        </div>
        @endif

        <!-- Action Buttons -->
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-[#343A40]">
            <a href="{{ route('opnames.index') }}"
                class="px-4 py-2.5 text-xs font-semibold text-[#A1A1AA] hover:text-[#F5F5F5] hover:bg-[#2A3036] rounded-xl transition">
                Batal
            </a>
            <button type="submit"
                class="inline-flex items-center gap-2 px-6 py-3 bg-[#F5C842] hover:bg-[#E8B92E] text-[#101214] text-xs font-bold uppercase tracking-wider rounded-xl shadow-xs transition cursor-pointer">
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
                diffStatusPill.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-[#181C20] text-[#A1A1AA] border border-[#343A40]';
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
                diffStatusPill.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/30';
                diffStatusPill.textContent = 'Masukkan Angka Fisik';
                diffLabel.textContent = 'Hitung fisik di rak gudang';
                return;
            }

            const physicalStock = parseInt(physicalVal) || 0;
            const difference = physicalStock - systemStock;

            if (difference === 0) {
                diffDisplay.textContent = '0 Unit';
                diffDisplay.className = 'font-heading text-2xl font-bold tabular-nums text-emerald-400 mt-1';
                diffStatusPill.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30';
                diffStatusPill.textContent = 'Cocok / Sesuai Sempurna';
                diffLabel.textContent = 'Tidak ada selisih stok';
            } else if (difference > 0) {
                diffDisplay.textContent = '+' + difference.toLocaleString('id-ID') + ' Unit';
                diffDisplay.className = 'font-heading text-2xl font-bold tabular-nums text-amber-400 mt-1';
                diffStatusPill.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/30';
                diffStatusPill.textContent = 'Surplus (Lebih Banyak)';
                diffLabel.textContent = 'Stok fisik lebih banyak dari sistem';
            } else {
                diffDisplay.textContent = difference.toLocaleString('id-ID') + ' Unit';
                diffDisplay.className = 'font-heading text-2xl font-bold tabular-nums text-rose-400 mt-1';
                diffStatusPill.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-rose-500/10 text-rose-400 border border-rose-500/30';
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
