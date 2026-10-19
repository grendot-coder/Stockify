@extends('layouts.dashboard')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <!-- Breadcrumb & Navigasi Kembali -->
    <div class="flex items-center justify-between">
        <a href="{{ route('transactions.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#78716C] hover:text-[#292524] transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Kembali ke Riwayat Transaksi
        </a>
    </div>

    <!-- Header Judul -->
    <div class="bg-[#FFFFFF] rounded-2xl border border-[#E7E0DA] p-6 shadow-xs">
        <div class="flex items-center gap-2 mb-1">
            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase tracking-wider">
                Inbound Transaction
            </span>
        </div>
        <h1 class="font-editorial text-2xl sm:text-3xl font-medium text-[#292524]">Penerimaan Barang Masuk</h1>
        <p class="text-xs text-[#78716C] mt-1">Pencatatan pasokan inventaris baru dari supplier rekanan atau transfer masuk gudang</p>
    </div>

    <!-- Alert Kesalahan Validasi -->
    @if($errors->any())
    <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs shadow-xs">
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

    <!-- Formulir Barang Masuk -->
    <form action="{{ route('transactions.inbound.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="bg-[#FFFFFF] rounded-2xl border border-[#E7E0DA] p-6 shadow-xs space-y-5">
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- No. Invoice / Referensi -->
                <div>
                    <label class="block text-xs font-semibold text-[#292524] mb-1.5 uppercase tracking-wider">
                        No. Invoice / Surat Jalan <span class="text-[#BD6745]">*</span>
                    </label>
                    <input type="text" name="invoice_number" required value="{{ old('invoice_number', $suggestedInvoice) }}"
                        class="w-full px-3.5 py-2.5 text-xs font-mono bg-[#FFFFFF] border border-[#E7E0DA] rounded-xl text-[#292524] placeholder-[#78716C]/60 focus:outline-none focus:border-[#BD6745] focus:ring-1 focus:ring-[#BD6745]">
                    <p class="text-[10px] text-[#78716C] mt-1">Nomor invoice supplier atau kode acuan otomatis.</p>
                </div>

                <!-- Supplier -->
                <div>
                    <label class="block text-xs font-semibold text-[#292524] mb-1.5 uppercase tracking-wider">
                        Rekanan Supplier
                    </label>
                    <select name="supplier_id"
                        class="w-full px-3.5 py-2.5 text-xs bg-[#FFFFFF] border border-[#E7E0DA] rounded-xl text-[#292524] focus:outline-none focus:border-[#BD6745] focus:ring-1 focus:ring-[#BD6745]">
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
                <label class="block text-xs font-semibold text-[#292524] mb-1.5 uppercase tracking-wider">
                    Pilih Produk / Barang <span class="text-[#BD6745]">*</span>
                </label>
                <select name="product_id" id="product_id" required
                    class="w-full px-3.5 py-2.5 text-xs bg-[#FFFFFF] border border-[#E7E0DA] rounded-xl text-[#292524] focus:outline-none focus:border-[#BD6745] focus:ring-1 focus:ring-[#BD6745]">
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
                    <label class="block text-xs font-semibold text-[#292524] mb-1.5 uppercase tracking-wider">
                        Jumlah Kuantitas Masuk <span class="text-[#BD6745]">*</span>
                    </label>
                    <input type="number" min="1" name="quantity" required value="{{ old('quantity', 1) }}"
                        class="w-full px-3.5 py-2.5 text-xs bg-[#FFFFFF] border border-[#E7E0DA] rounded-xl text-[#292524] focus:outline-none focus:border-[#BD6745] focus:ring-1 focus:ring-[#BD6745]">
                </div>

                <!-- Status Alur Transaksi -->
                <div>
                    <label class="block text-xs font-semibold text-[#292524] mb-1.5 uppercase tracking-wider">
                        Status Verifikasi Transaksi <span class="text-[#BD6745]">*</span>
                    </label>
                    <select name="status" required
                        class="w-full px-3.5 py-2.5 text-xs bg-[#FFFFFF] border border-[#E7E0DA] rounded-xl text-[#292524] focus:outline-none focus:border-[#BD6745] focus:ring-1 focus:ring-[#BD6745]">
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
                <label class="block text-xs font-semibold text-[#292524] mb-1.5 uppercase tracking-wider">
                    Catatan / Nomor DO / Keterangan Masuk
                </label>
                <textarea name="notes" rows="3" placeholder="Contoh: Pengiriman batch PO-091, kondisi fisik baik dan segel utuh..."
                    class="w-full px-3.5 py-2.5 text-xs bg-[#FFFFFF] border border-[#E7E0DA] rounded-xl text-[#292524] placeholder-[#78716C]/60 focus:outline-none focus:border-[#BD6745] focus:ring-1 focus:ring-[#BD6745]">{{ old('notes') }}</textarea>
            </div>

        </div>

        <!-- Tombol Aksi Simpan Soft Terracotta -->
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('transactions.index') }}"
                class="px-4 py-2.5 text-xs font-semibold text-[#78716C] hover:text-[#292524] hover:bg-[#FAF7F4] rounded-xl transition">
                Batal
            </a>
            <button type="submit"
                class="inline-flex items-center gap-2 px-6 py-3 bg-[#BD6745] hover:bg-[#A55636] text-[#1C1917] text-xs font-bold uppercase tracking-wider rounded-xl shadow-xs transition cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
                <span>Simpan Barang Masuk</span>
            </button>
        </div>
    </form>

</div>
@endsection