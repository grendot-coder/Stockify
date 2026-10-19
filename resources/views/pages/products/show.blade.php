@extends('layouts.dashboard')

@section('content')
<div class="max-w-5xl mx-auto space-y-5">

    <!-- Navigasi & Action Buttons -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <a href="{{ route('products.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#78716C] hover:text-[#292524] transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Kembali ke Katalog Produk
        </a>

        @if(in_array(Auth::user()->role, ['admin', 'manager']))
            <div class="flex items-center gap-2">
                <a href="{{ route('products.edit', $product->id) }}"
                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#BD6745] hover:bg-[#A55636] text-[#1C1917] text-xs font-semibold rounded-xl shadow-xs transition cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                    </svg>
                    <span>Ubah Data</span>
                </a>
                @if(Auth::user()->role === 'admin')
                    <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');" class="inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-[#FFFFFF] hover:bg-rose-50 text-rose-600 border border-[#E7E0DA] hover:border-rose-200 text-xs font-semibold rounded-xl transition cursor-pointer">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                            </svg>
                            <span>Hapus</span>
                        </button>
                    </form>
                @endif
            </div>
        @endif
    </div>

    <!-- Informasi Utama Produk -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        
        <!-- Kolom Kiri: Foto, SKU, dan Status Stok -->
        <div class="space-y-4">
            <!-- Foto Produk -->
            <div class="bg-[#FFFFFF] rounded-2xl border border-[#E7E0DA] p-5 shadow-xs text-center">
                <div class="w-full aspect-square rounded-xl bg-[#FAF7F4] border border-[#E7E0DA] flex items-center justify-center overflow-hidden mb-3">
                    @if($product->image && file_exists(public_path('storage/' . $product->image)))
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="text-center p-6">
                            <svg class="w-12 h-12 text-[#E7E0DA] mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-xs text-[#78716C]">Tidak ada foto produk</span>
                        </div>
                    @endif
                </div>

                <div class="text-xs font-mono font-bold text-[#BD6745] bg-[#F5E6DE] px-3 py-1.5 rounded-lg inline-block border border-[#BD6745]/30">
                    SKU: {{ $product->sku }}
                </div>
            </div>

            <!-- Card Status Stok -->
            <div class="bg-[#FFFFFF] rounded-2xl border border-[#E7E0DA] p-5 shadow-xs space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-mono uppercase tracking-wider text-[#78716C] font-semibold">Persediaan Gudang</span>
                    @if($product->stock <= 0)
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-rose-50 text-rose-700 border border-rose-200">Habis</span>
                    @elseif($product->stock <= $product->min_stock)
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-amber-50 text-amber-800 border border-amber-200">Menipis</span>
                    @else
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">Aman</span>
                    @endif
                </div>

                <div class="text-3xl font-editorial font-medium text-[#292524]">
                    {{ $product->stock }} <span class="text-xs font-sans text-[#78716C] font-normal">Unit / Pcs</span>
                </div>

                <div class="text-[11px] text-[#78716C] pt-2 border-t border-[#E7E0DA] flex justify-between">
                    <span>Batas Minimum Stok:</span>
                    <strong class="text-[#292524] font-mono">{{ $product->min_stock }} Unit</strong>
                </div>
            </div>

            <!-- Card Harga & Margin -->
            <div class="bg-[#FFFFFF] rounded-2xl border border-[#E7E0DA] p-5 shadow-xs space-y-2.5">
                <span class="text-[11px] font-mono uppercase tracking-wider text-[#78716C] font-semibold">Rincian Finansial</span>
                
                <div class="flex justify-between items-center text-xs">
                    <span class="text-[#78716C]">Harga Beli (Modal):</span>
                    <span class="font-mono text-[#292524]">Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</span>
                </div>

                <div class="flex justify-between items-center text-xs">
                    <span class="text-[#78716C]">Harga Jual Retail:</span>
                    <span class="font-mono font-bold text-[#292524]">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</span>
                </div>

                @php
                    $marginNominal = $product->selling_price - $product->purchase_price;
                    $marginPersen = $product->selling_price > 0 ? ($marginNominal / $product->selling_price) * 100 : 0;
                @endphp
                <div class="pt-2 border-t border-[#E7E0DA] flex justify-between items-center text-xs">
                    <span class="text-emerald-700 font-medium">Estimasi Margin:</span>
                    <span class="font-mono font-bold text-emerald-700">
                        Rp {{ number_format($marginNominal, 0, ',', '.') }} ({{ number_format($marginPersen, 1) }}%)
                    </span>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Rincian Produk, Supplier, dan Atribut -->
        <div class="md:col-span-2 space-y-4">
            
            <!-- Profil Barang -->
            <div class="bg-[#FFFFFF] rounded-2xl border border-[#E7E0DA] p-6 shadow-xs space-y-4">
                <div>
                    <div class="flex items-center gap-2 mb-1.5">
                        <span class="px-2.5 py-0.5 rounded text-[11px] font-semibold bg-[#F5E6DE] text-[#BD6745] border border-[#BD6745]/30">
                            {{ $product->category?->name ?? 'Tanpa Kategori' }}
                        </span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-editorial font-medium text-[#292524]">{{ $product->name }}</h1>
                </div>

                <!-- Deskripsi -->
                <div>
                    <h3 class="text-xs font-semibold text-[#292524] mb-1.5">Deskripsi & Catatan:</h3>
                    <p class="text-xs text-[#292524] leading-relaxed bg-[#FAF7F4] p-3.5 rounded-xl border border-[#E7E0DA]">
                        {{ $product->description ?: 'Tidak ada catatan atau deskripsi tambahan untuk produk ini.' }}
                    </p>
                </div>

                <!-- Info Supplier -->
                <div class="pt-3 border-t border-[#E7E0DA]">
                    <h3 class="text-xs font-semibold text-[#292524] mb-2.5">Informasi Rekanan Pemasok:</h3>
                    @if($product->supplier)
                        <div class="bg-[#FAF7F4] p-3.5 rounded-xl border border-[#E7E0DA] grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                            <div>
                                <span class="text-[#78716C] text-[11px]">Nama Supplier:</span>
                                <div class="font-semibold text-[#292524] mt-0.5">{{ $product->supplier->name }}</div>
                            </div>
                            <div>
                                <span class="text-[#78716C] text-[11px]">Kontak Telepon:</span>
                                <div class="font-mono font-semibold text-[#292524] mt-0.5">{{ $product->supplier->phone }}</div>
                            </div>
                            @if($product->supplier->email)
                                <div>
                                    <span class="text-[#78716C] text-[11px]">Email:</span>
                                    <div class="text-[#292524] mt-0.5">{{ $product->supplier->email }}</div>
                                </div>
                            @endif
                            @if($product->supplier->address)
                                <div class="sm:col-span-2">
                                    <span class="text-[#78716C] text-[11px]">Alamat:</span>
                                    <div class="text-[#292524] mt-0.5">{{ $product->supplier->address }}</div>
                                </div>
                            @endif
                        </div>
                    @else
                        <p class="text-xs text-[#78716C] italic">Belum ada rekanan supplier yang terikat dengan barang ini.</p>
                    @endif
                </div>
            </div>

            <!-- Tabel Spesifikasi & Atribut Tambahan -->
            <div class="bg-[#FFFFFF] rounded-2xl border border-[#E7E0DA] p-6 shadow-xs space-y-3">
                <h3 class="text-xs font-semibold text-[#292524] uppercase tracking-wider">Atribut & Spesifikasi Barang</h3>
                
                @if($product->attributes->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-xs text-left text-[#78716C]">
                            <thead class="bg-[#FAF7F4] text-[11px] text-[#292524] uppercase tracking-wider border-b border-[#E7E0DA]">
                                <tr>
                                    <th class="px-4 py-2.5 w-1/3">Nama Atribut</th>
                                    <th class="px-4 py-2.5">Nilai / Spesifikasi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#E7E0DA]">
                                @foreach($product->attributes as $attr)
                                    <tr class="hover:bg-[#FAF7F4] transition">
                                        <td class="px-4 py-2.5 text-[#78716C]">{{ $attr->attribute_name }}</td>
                                        <td class="px-4 py-2.5 text-[#292524] font-semibold">{{ $attr->attribute_value }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-xs text-[#78716C] italic py-1">Belum ada atribut spesifikasi khusus.</p>
                @endif
            </div>

            <!-- Riwayat Transaksi Stok Terakhir -->
            <div class="bg-[#FFFFFF] rounded-2xl border border-[#E7E0DA] p-6 shadow-xs space-y-3">
                <div class="flex items-center justify-between">
                    <h3 class="text-xs font-semibold text-[#292524] uppercase tracking-wider">10 Riwayat Mutasi Stok Terakhir</h3>
                    <span class="text-[11px] text-[#78716C]">Pembaruan otomatis dari modul transaksi</span>
                </div>

                @if($product->stockTransactions->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-xs text-left text-[#78716C]">
                            <thead class="bg-[#FAF7F4] text-[11px] text-[#292524] uppercase tracking-wider border-b border-[#E7E0DA]">
                                <tr>
                                    <th class="px-4 py-2.5">No. Transaksi</th>
                                    <th class="px-4 py-2.5">Jenis</th>
                                    <th class="px-4 py-2.5 text-right">Jumlah</th>
                                    <th class="px-4 py-2.5">Tanggal</th>
                                    <th class="px-4 py-2.5">Catatan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#E7E0DA]">
                                @foreach($product->stockTransactions as $trx)
                                    <tr class="hover:bg-[#FAF7F4] transition">
                                        <td class="px-4 py-2.5 font-mono font-medium text-[#292524]">{{ $trx->invoice_number }}</td>
                                        <td class="px-4 py-2.5">
                                            @if($trx->type === 'in' || $trx->type === 'inbound')
                                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                    Masuk
                                                </span>
                                            @else
                                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-[#F5E6DE] text-[#BD6745] border border-[#BD6745]/30">
                                                    Keluar
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-2.5 text-right font-mono font-bold {{ ($trx->type === 'in' || $trx->type === 'inbound') ? 'text-emerald-700' : 'text-[#BD6745]' }}">
                                            {{ ($trx->type === 'in' || $trx->type === 'inbound') ? '+' : '-' }}{{ $trx->quantity }}
                                        </td>
                                        <td class="px-4 py-2.5 text-[#78716C] font-mono">{{ $trx->created_at->format('d M Y H:i') }}</td>
                                        <td class="px-4 py-2.5 text-[#78716C] truncate max-w-xs">{{ $trx->notes ?: '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-xs text-[#78716C] italic py-2">Belum ada riwayat transaksi mutasi stok untuk produk ini.</p>
                @endif
            </div>

        </div>

    </div>

</div>
@endsection
