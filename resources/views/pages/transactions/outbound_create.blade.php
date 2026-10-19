@extends('layouts.dashboard')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <!-- Breadcrumb & Navigasi Kembali -->
    <div class="flex items-center justify-between">
        <a href="{{ route('transactions.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#A1A1AA] hover:text-[#F5F5F5] transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Kembali ke Riwayat Transaksi
        </a>
    </div>

    <!-- Header Judul -->
    <div class="bg-[#202429] rounded-2xl border border-[#343A40] p-6 shadow-xs">
        <div class="flex items-center gap-2 mb-1">
            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded text-[10px] font-semibold bg-[#F5C842]/15 text-[#F5C842] border border-[#F5C842]/30 uppercase tracking-wider">
                Outbound Transaction
            </span>
        </div>
        <h1 class="font-editorial text-2xl sm:text-3xl font-medium text-[#F5F5F5]">Pengeluaran Barang Keluar</h1>
        <p class="text-xs text-[#A1A1AA] mt-1">Pencatatan barang keluar untuk penjualan, retur barang cacat, atau alokasi operasional</p>
    </div>

    <!-- Alert Kesalahan Validasi -->
    @if($errors->any())
    <div class="p-4 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs shadow-xs">
        <div class="flex items-center gap-2 font-semibold mb-1">
            <svg class="w-4 h-4 text-rose-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            <span>Mohon periksa kembali formulir berikut:</span>
        </div>
        <ul class="list-disc pl-5 space-y-0.5 text-[11px] text-rose-700">
            @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Formulir Barang Keluar -->
    <form action="{{ route('transactions.outbound.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="bg-[#202429] rounded-2xl border border-[#343A40] p-6 shadow-xs space-y-5">
            
            <!-- No. Invoice / Referensi -->
            <div>
                <label class="block text-xs font-semibold text-[#F5F5F5] mb-1.5 uppercase tracking-wider">
                    No. Transaksi / Surat Pengeluaran <span class="text-[#F5C842]">*</span>
                </label>
                <input type="text" name="invoice_number" required value="{{ old('invoice_number', $suggestedInvoice) }}"
                    class="w-full px-3.5 py-2.5 text-xs font-mono bg-[#202429] border border-[#343A40] rounded-xl text-[#F5F5F5] placeholder-[#A1A1AA]/60 focus:outline-none focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842]">
            </div>

            <!-- Pilih Produk & Real-time Stock Helper -->
            <div>
                <label class="block text-xs font-semibold text-[#F5F5F5] mb-1.5 uppercase tracking-wider">
                    Pilih Produk / Barang yang Dikeluarkan <span class="text-[#F5C842]">*</span>
                </label>
                <select name="product_id" id="product_id" required
                    class="w-full px-3.5 py-2.5 text-xs bg-[#202429] border border-[#343A40] rounded-xl text-[#F5F5F5] focus:outline-none focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842]">
                    <option value="">-- Pilih Barang dari Gudang --</option>
                    @foreach($products as $p)
                    <option value="{{ $p->id }}" data-stock="{{ $p->stock }}" {{ old('product_id') == $p->id ? 'selected' : '' }}>
                        {{ $p->name }} (SKU: {{ $p->sku }}) — Stok Tersedia: {{ $p->stock }} Unit
                    </option>
                    @endforeach
                </select>

                <!-- Box Info Stok Terpilih -->
                <div id="stock-info-box" class="hidden mt-2.5 p-3.5 bg-[#181C20] rounded-xl border border-[#343A40] text-xs flex items-center justify-between">
                    <span class="text-[#A1A1AA]">Sisa stok fisik di gudang:</span>
                    <strong id="current-stock-label" class="text-sm font-bold text-[#F5C842] font-mono">0 Unit</strong>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Kuantitas Keluar -->
                <div>
                    <label class="block text-xs font-semibold text-[#F5F5F5] mb-1.5 uppercase tracking-wider">
                        Jumlah Kuantitas Keluar <span class="text-[#F5C842]">*</span>
                    </label>
                    <input type="number" min="1" name="quantity" id="quantity_input" required value="{{ old('quantity', 1) }}"
                        class="w-full px-3.5 py-2.5 text-xs bg-[#202429] border border-[#343A40] rounded-xl text-[#F5F5F5] focus:outline-none focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842]">
                    <p id="stock-warning" class="hidden text-[11px] text-rose-600 font-medium mt-1.5">
                        Jumlah pengeluaran melebihi sisa stok di gudang!
                    </p>
                </div>

                <!-- Status Alur Transaksi -->
                <div>
                    <label class="block text-xs font-semibold text-[#F5F5F5] mb-1.5 uppercase tracking-wider">
                        Status Verifikasi Transaksi <span class="text-[#F5C842]">*</span>
                    </label>
                    <select name="status" required
                        class="w-full px-3.5 py-2.5 text-xs bg-[#202429] border border-[#343A40] rounded-xl text-[#F5F5F5] focus:outline-none focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842]">
                        <option value="confirmed" {{ old('status', 'confirmed') === 'confirmed' ? 'selected' : '' }}>
                            Langsung Dikonfirmasi (Stok Berkurang)
                        </option>
                        <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>
                            Menunggu Persetujuan Manajer (Pending)
                        </option>
                    </select>
                </div>
            </div>

            <!-- Catatan / Alasan Pengeluaran -->
            <div>
                <label class="block text-xs font-semibold text-[#F5F5F5] mb-1.5 uppercase tracking-wider">
                    Alasan / Keterangan Pengeluaran Barang <span class="text-[#F5C842]">*</span>
                </label>
                <textarea name="notes" id="notes_input" rows="3" required placeholder="Contoh: Penjualan pesanan customer #INV-881, atau Rusak saat pemindahan rak gudang..."
                    class="w-full px-3.5 py-2.5 text-xs bg-[#202429] border border-[#343A40] rounded-xl text-[#F5F5F5] placeholder-[#A1A1AA]/60 focus:outline-none focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842]">{{ old('notes') }}</textarea>
                
                <!-- Quick Suggestion Chips -->
                <div class="flex flex-wrap items-center gap-1.5 mt-2.5">
                    <span class="text-[10px] text-[#A1A1AA]">Pilihan cepat:</span>
                    <button type="button" onclick="setQuickNote('Penjualan langsung ke pelanggan')"
                        class="px-2.5 py-1 text-[10px] rounded-lg bg-[#181C20] hover:bg-[#F5C842]/15 border border-[#343A40] hover:border-[#F5C842] text-[#A1A1AA] hover:text-[#F5C842] transition cursor-pointer">Penjualan Pelanggan</button>
                    <button type="button" onclick="setQuickNote('Barang rusak / cacat fisik pabrik (Retur)')"
                        class="px-2.5 py-1 text-[10px] rounded-lg bg-[#181C20] hover:bg-[#F5C842]/15 border border-[#343A40] hover:border-[#F5C842] text-[#A1A1AA] hover:text-[#F5C842] transition cursor-pointer">Barang Rusak / Retur</button>
                    <button type="button" onclick="setQuickNote('Pemakaian operasional inventaris internal')"
                        class="px-2.5 py-1 text-[10px] rounded-lg bg-[#181C20] hover:bg-[#F5C842]/15 border border-[#343A40] hover:border-[#F5C842] text-[#A1A1AA] hover:text-[#F5C842] transition cursor-pointer">Operasional Internal</button>
                </div>
            </div>

        </div>

        <!-- Tombol Aksi Simpan Soft Terracotta -->
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('transactions.index') }}"
                class="px-4 py-2.5 text-xs font-semibold text-[#A1A1AA] hover:text-[#F5F5F5] hover:bg-[#181C20] rounded-xl transition">
                Batal
            </a>
            <button type="submit"
                class="inline-flex items-center gap-2 px-6 py-3 bg-[#F5C842] hover:bg-[#E8B92E] text-[#101214] text-xs font-bold uppercase tracking-wider rounded-xl shadow-xs transition cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
                <span>Simpan Barang Keluar</span>
            </button>
        </div>
    </form>

</div>

<script>
    const productSelect = document.getElementById('product_id');
    const stockBox = document.getElementById('stock-info-box');
    const stockLabel = document.getElementById('current-stock-label');
    const qtyInput = document.getElementById('quantity_input');
    const warningText = document.getElementById('stock-warning');
    const notesInput = document.getElementById('notes_input');
    let maxAvailableStock = 0;

    function updateStockDisplay() {
        const selectedOpt = productSelect.options[productSelect.selectedIndex];
        if (selectedOpt && selectedOpt.value) {
            maxAvailableStock = parseInt(selectedOpt.getAttribute('data-stock')) || 0;
            stockLabel.textContent = maxAvailableStock + ' Unit';
            stockBox.classList.remove('hidden');
            validateQuantity();
        } else {
            stockBox.classList.add('hidden');
            maxAvailableStock = 0;
            warningText.classList.add('hidden');
        }
    }

    function validateQuantity() {
        const enteredQty = parseInt(qtyInput.value) || 0;
        if (enteredQty > maxAvailableStock && maxAvailableStock > 0) {
            warningText.classList.remove('hidden');
            qtyInput.classList.add('border-rose-500', 'focus:border-rose-500');
        } else {
            warningText.classList.add('hidden');
            qtyInput.classList.remove('border-rose-500', 'focus:border-rose-500');
        }
    }

    function setQuickNote(text) {
        notesInput.value = text;
    }

    productSelect.addEventListener('change', updateStockDisplay);
    qtyInput.addEventListener('input', validateQuantity);
    updateStockDisplay();
</script>
@endsection