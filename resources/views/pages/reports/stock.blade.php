@extends('layouts.dashboard')

@section('content')
<div class="space-y-6">

    <!-- Header Laporan Stok -->
    <div class="bg-[#FFFFFF] rounded-2xl border border-[#E7E0DA] p-6 shadow-xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="w-2 h-2 rounded-full bg-[#BD6745]"></span>
                <span class="text-[11px] uppercase tracking-[0.2em] text-[#BD6745] font-semibold">Pelaporan & Audit Inventaris</span>
            </div>
            <h1 class="font-heading text-2xl sm:text-3xl font-semibold text-[#292524] tracking-tight">
                Laporan Persediaan & Valuasi Stok
            </h1>
            <p class="text-xs text-[#78716C] mt-1">
                Ringkasan kuantitas fisik, batas minimum pengingat restock, serta kalkulasi total nilai modal inventaris gudang.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <!-- Tombol Ekspor CSV -->
            <a href="{{ request()->fullUrlWithQuery(['export' => 'csv']) }}" 
               class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-[#292524] bg-[#FFFFFF] hover:bg-[#FAF7F4] rounded-xl border border-[#E7E0DA] transition shadow-xs">
                <svg class="w-4 h-4 text-[#059669]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Unduh CSV / Excel
            </a>
            <!-- Tombol Cetak / PDF -->
            <a href="{{ request()->fullUrlWithQuery(['export' => 'print']) }}" target="_blank"
               class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-[#1C1917] bg-[#BD6745] hover:bg-[#a65434] rounded-xl transition shadow-xs text-white">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Cetak / Simpan PDF
            </a>
        </div>
    </div>

    <!-- Ringkasan Agregat Laporan (3 Cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="p-5 bg-[#FFFFFF] rounded-2xl border border-[#E7E0DA] shadow-xs">
            <span class="text-xs font-semibold text-[#78716C] uppercase tracking-wider">Total Item Produk</span>
            <div class="font-heading text-3xl font-semibold tabular-nums text-[#292524] mt-2">
                {{ number_format($totalItems) }} <span class="font-sans text-xs font-normal text-[#78716C]">SKU</span>
            </div>
            <p class="text-[11px] text-[#78716C] mt-1">Sesuai dengan kriteria filter saat ini</p>
        </div>

        <div class="p-5 bg-[#FFFFFF] rounded-2xl border border-[#E7E0DA] shadow-xs">
            <span class="text-xs font-semibold text-[#78716C] uppercase tracking-wider">Total Unit Fisik</span>
            <div class="font-heading text-3xl font-semibold tabular-nums text-[#BD6745] mt-2">
                {{ number_format($totalQty) }} <span class="font-sans text-xs font-normal text-[#78716C]">Unit</span>
            </div>
            <p class="text-[11px] text-[#78716C] mt-1">Kuantitas stok aktual di rak gudang</p>
        </div>

        <div class="p-5 bg-[#FFFFFF] rounded-2xl border border-[#E7E0DA] shadow-xs">
            <span class="text-xs font-semibold text-[#78716C] uppercase tracking-wider">Total Valuasi Nilai Modal</span>
            <div class="font-heading text-2xl font-semibold tabular-nums text-[#292524] mt-2 truncate">
                Rp {{ number_format($totalValuation, 0, ',', '.') }}
            </div>
            <p class="text-[11px] text-[#78716C] mt-1">Estimasi nilai modal (Stok &times; Harga Beli)</p>
        </div>
    </div>

    <!-- Panel Filter & Pencarian -->
    <div class="bg-[#FFFFFF] rounded-2xl border border-[#E7E0DA] p-5 shadow-xs">
        <form action="{{ route('reports.stock') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-end">
            
            <!-- Pencarian SKU / Nama -->
            <div>
                <label for="search" class="block text-xs font-semibold text-[#292524] mb-1">Cari Produk / SKU</label>
                <input type="text" name="search" id="search" value="{{ $search }}" placeholder="Ketik nama atau SKU..."
                       class="w-full text-xs px-3 py-2 rounded-xl border border-[#E7E0DA] bg-[#FAF7F4] focus:bg-white focus:outline-none focus:border-[#BD6745] text-[#292524] placeholder-[#78716C]">
            </div>

            <!-- Filter Kategori -->
            <div>
                <label for="category_id" class="block text-xs font-semibold text-[#292524] mb-1">Kategori</label>
                <select name="category_id" id="category_id" class="w-full text-xs px-3 py-2 rounded-xl border border-[#E7E0DA] bg-[#FAF7F4] focus:bg-white focus:outline-none focus:border-[#BD6745] text-[#292524]">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Supplier -->
            <div>
                <label for="supplier_id" class="block text-xs font-semibold text-[#292524] mb-1">Supplier Rekanan</label>
                <select name="supplier_id" id="supplier_id" class="w-full text-xs px-3 py-2 rounded-xl border border-[#E7E0DA] bg-[#FAF7F4] focus:bg-white focus:outline-none focus:border-[#BD6745] text-[#292524]">
                    <option value="">Semua Supplier</option>
                    @foreach($suppliers as $sup)
                        <option value="{{ $sup->id }}" {{ $supplierId == $sup->id ? 'selected' : '' }}>{{ $sup->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Status Ketersediaan -->
            <div>
                <label for="status" class="block text-xs font-semibold text-[#292524] mb-1">Status Stok</label>
                <select name="status" id="status" class="w-full text-xs px-3 py-2 rounded-xl border border-[#E7E0DA] bg-[#FAF7F4] focus:bg-white focus:outline-none focus:border-[#BD6745] text-[#292524]">
                    <option value="">Semua Status Stok</option>
                    <option value="safe" {{ $stockStatus === 'safe' ? 'selected' : '' }}>Aman (Di Atas Minimum)</option>
                    <option value="low" {{ $stockStatus === 'low' ? 'selected' : '' }}>Tipis (&le; Batas Minimum)</option>
                    <option value="out" {{ $stockStatus === 'out' ? 'selected' : '' }}>Habis (0 Unit)</option>
                </select>
            </div>

            <!-- Tombol Filter & Reset -->
            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 py-2 px-3 text-xs font-semibold text-white bg-[#BD6745] hover:bg-[#a65434] rounded-xl transition shadow-xs">
                    Filter Data
                </button>
                <a href="{{ route('reports.stock') }}" class="py-2 px-3 text-xs font-semibold text-[#78716C] bg-[#FAF7F4] hover:bg-[#E7E0DA] rounded-xl border border-[#E7E0DA] transition text-center">
                    Reset
                </a>
            </div>

        </form>
    </div>

    <!-- Tabel Data Laporan Stok -->
    <div class="bg-[#FFFFFF] rounded-2xl border border-[#E7E0DA] overflow-hidden shadow-xs">
        <div class="p-4 border-b border-[#E7E0DA] flex items-center justify-between text-xs text-[#78716C]">
            <span>Menampilkan data persediaan barang terdaftar</span>
            <span class="font-mono tabular-nums">Total: {{ $products->total() }} Data</span>
        </div>

        @if($products->isEmpty())
            <div class="p-12 text-center text-xs text-[#78716C]">
                Tidak ada data stok produk yang sesuai dengan kriteria filter yang Anda tentukan.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left text-[#78716C] tabular-nums">
                    <thead class="text-[11px] text-[#292524] uppercase tracking-wider bg-[#FAF7F4] border-b border-[#E7E0DA]">
                        <tr>
                            <th class="px-5 py-3 font-heading font-semibold">SKU Produk</th>
                            <th class="px-5 py-3 font-heading font-semibold">Nama Produk</th>
                            <th class="px-5 py-3 font-heading font-semibold">Kategori</th>
                            <th class="px-5 py-3 font-heading font-semibold">Supplier</th>
                            <th class="px-5 py-3 text-right font-heading font-semibold">Stok Fisik</th>
                            <th class="px-5 py-3 text-right font-heading font-semibold">Min. Stok</th>
                            <th class="px-5 py-3 text-right font-heading font-semibold">Harga Beli</th>
                            <th class="px-5 py-3 text-right font-heading font-semibold">Total Valuasi</th>
                            <th class="px-5 py-3 text-center font-heading font-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E7E0DA]">
                        @foreach($products as $p)
                        @php
                            $isOut = $p->stock <= 0;
                            $isLow = !$isOut && $p->stock <= $p->min_stock;
                            $valuation = $p->stock * $p->purchase_price;
                        @endphp
                        <tr class="hover:bg-[#FAF7F4] transition">
                            <td class="px-5 py-3.5 font-mono tabular-nums font-semibold text-[#292524]">
                                <a href="{{ route('products.show', $p->id) }}" class="hover:text-[#BD6745]">
                                    {{ $p->sku }}
                                </a>
                            </td>
                            <td class="px-5 py-3.5 font-medium text-[#292524]">
                                {{ $p->name }}
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="px-2 py-0.5 rounded text-[10px] bg-[#FAF7F4] border border-[#E7E0DA] text-[#292524]">
                                    {{ $p->category?->name ?? '-' }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-[#292524]">
                                {{ $p->supplier?->name ?? '-' }}
                            </td>
                            <td class="px-5 py-3.5 text-right font-bold text-[#292524]">
                                {{ number_format($p->stock) }} Unit
                            </td>
                            <td class="px-5 py-3.5 text-right text-[#78716C] font-mono">
                                {{ number_format($p->min_stock) }}
                            </td>
                            <td class="px-5 py-3.5 text-right font-mono text-[#78716C]">
                                Rp {{ number_format($p->purchase_price, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-3.5 text-right font-bold font-mono text-[#292524]">
                                Rp {{ number_format($valuation, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                @if($isOut)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-red-50 text-red-700 border border-red-200">
                                        Habis
                                    </span>
                                @elseif($isLow)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                        Stok Tipis
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Aman
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="p-4 border-t border-[#E7E0DA]">
                {{ $products->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
