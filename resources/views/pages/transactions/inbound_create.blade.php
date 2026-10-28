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
            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded text-[10px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 uppercase tracking-wider">
                Inbound Transaction
            </span>
        </div>
        <h1 class="font-heading text-2xl sm:text-3xl font-semibold text-[#F5F5F5]">Penerimaan Barang Masuk</h1>
        <p class="text-xs text-[#A1A1AA] mt-1">Pencatatan pasokan inventaris baru dari supplier rekanan atau transfer masuk gudang</p>
    </div>

    <!-- Alert Kesalahan Validasi -->
    @if($errors->any())
    <div class="p-4 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs shadow-xs">
        <div class="flex items-center gap-2 font-semibold mb-1">
            <svg class="w-4 h-4 text-rose-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            <span>Mohon periksa kembali formulir berikut:</span>
        </div>
        <ul class="list-disc pl-5 space-y-0.5 text-[11px] text-rose-300">
            @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Formulir Barang Masuk -->
    <form action="{{ route('transactions.inbound.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="bg-[#202429] rounded-2xl border border-[#343A40] p-6 shadow-xs space-y-5">
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- No. Invoice / Referensi -->
                <div>
                    <label class="block text-xs font-semibold text-[#F5F5F5] mb-1.5 uppercase tracking-wider">
                        No. Invoice / Surat Jalan <span class="text-[#F5C842]">*</span>
                    </label>
                    <input type="text" name="invoice_number" required value="{{ old('invoice_number', $suggestedInvoice) }}"
                        class="w-full px-3.5 py-2.5 text-xs font-mono bg-[#202429] border border-[#343A40] rounded-xl text-[#F5F5F5] placeholder-[#A1A1AA]/60 focus:outline-none focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842]">
                    <p class="text-[10px] text-[#A1A1AA] mt-1">Nomor invoice supplier atau kode acuan otomatis.</p>
                </div>

                <!-- Supplier -->
                <div>
                    <label class="block text-xs font-semibold text-[#F5F5F5] mb-1.5 uppercase tracking-wider">
                        Rekanan Supplier
                    </label>
                    <select name="supplier_id"
                        class="w-full px-3.5 py-2.5 text-xs bg-[#202429] border border-[#343A40] rounded-xl text-[#F5F5F5] focus:outline-none focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842]">
                        <option value="">-- Tanpa Supplier Terikat --</option>
                        @foreach($suppliers as $sup)
                        <option value="{{ $sup->id }}" {{ old('supplier_id') == $sup->id ? 'selected' : '' }}>
                            {{ $sup->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Pilih Produk -->
            <div>
                <label class="block text-xs font-semibold text-[#F5F5F5] mb-1.5 uppercase tracking-wider">
                    Pilih Produk / Barang <span class="text-[#F5C842]">*</span>
                </label>
                <select name="product_id" id="product_id" required
                    class="w-full px-3.5 py-2.5 text-xs bg-[#202429] border border-[#343A40] rounded-xl text-[#F5F5F5] focus:outline-none focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842]">
                    <option value="">-- Pilih Barang yang Diterima --</option>
                    @foreach($products as $p)
                    <option value="{{ $p->id }}" data-stock="{{ $p->stock }}" data-sku="{{ $p->sku }}" {{ old('product_id') == $p->id ? 'selected' : '' }}>
                        {{ $p->name }} (SKU: {{ $p->sku }}) — Stok Fisik: {{ $p->stock }} Unit
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Kuantitas Masuk -->
                <div>
                    <label class="block text-xs font-semibold text-[#F5F5F5] mb-1.5 uppercase tracking-wider">
                        Jumlah Kuantitas Masuk <span class="text-[#F5C842]">*</span>
                    </label>
                    <input type="number" min="1" name="quantity" required value="{{ old('quantity', 1) }}"
                        class="w-full px-3.5 py-2.5 text-xs bg-[#202429] border border-[#343A40] rounded-xl text-[#F5F5F5] focus:outline-none focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842]">
                </div>

                <!-- Status Alur Transaksi -->
                <div>
                    <label class="block text-xs font-semibold text-[#F5F5F5] mb-1.5 uppercase tracking-wider">
                        Status Verifikasi Transaksi <span class="text-[#F5C842]">*</span>
                    </label>
                    <select name="status" required
                        class="w-full px-3.5 py-2.5 text-xs bg-[#202429] border border-[#343A40] rounded-xl text-[#F5F5F5] focus:outline-none focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842]">
                        <option value="confirmed" {{ old('status', 'confirmed') === 'confirmed' ? 'selected' : '' }}>
                            Langsung Dikonfirmasi (Stok Bertambah)
                        </option>
                        <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>
                            Menunggu Persetujuan Manajer (Pending)
                        </option>
                    </select>
                </div>
            </div>

            <!-- Catatan / Keterangan -->
            <div>
                <label class="block text-xs font-semibold text-[#F5F5F5] mb-1.5 uppercase tracking-wider">
                    Catatan / Nomor DO / Keterangan Masuk
                </label>
                <textarea name="notes" rows="3" placeholder="Contoh: Pengiriman batch PO-091, kondisi fisik baik dan segel utuh..."
                    class="w-full px-3.5 py-2.5 text-xs bg-[#202429] border border-[#343A40] rounded-xl text-[#F5F5F5] placeholder-[#A1A1AA]/60 focus:outline-none focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842]">{{ old('notes') }}</textarea>
            </div>

        </div>

        <!-- Tombol Aksi Simpan Soft Terracotta -->
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('transactions.index') }}"
                class="px-4 py-2.5 text-xs font-semibold text-[#A1A1AA] hover:text-[#F5F5F5] hover:bg-[#2A3036] rounded-xl transition">
                Batal
            </a>
            <button type="submit"
                class="inline-flex items-center gap-2 px-6 py-3 bg-[#F5C842] hover:bg-[#E8B92E] text-[#101214] text-xs font-bold uppercase tracking-wider rounded-xl shadow-xs transition cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
                <span>Simpan Barang Masuk</span>
            </button>
        </div>
    </form>

</div>
@endsection