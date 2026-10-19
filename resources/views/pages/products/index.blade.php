@extends('layouts.dashboard')

@section('content')
<div class="space-y-6">

    <!-- Header & Tombol Tambah -->
    <div class="bg-[#FFFFFF] rounded-2xl border border-[#E7E0DA] p-6 shadow-xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="w-2 h-2 rounded-full bg-[#BD6745]"></span>
                <span class="text-[11px] uppercase tracking-[0.2em] text-[#BD6745] font-semibold">Katalog Inventaris</span>
            </div>
            <h1 class="font-heading text-2xl sm:text-3xl font-semibold text-[#292524] tracking-tight">Master Data Produk</h1>
            <p class="text-xs text-[#78716C] mt-1">Daftar inventaris barang gudang, level stok fisik, dan rincian penetapan harga</p>
        </div>
        @if(in_array(Auth::user()->role, ['admin', 'manager']))
            <a href="{{ route('products.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#BD6745] hover:bg-[#A55636] text-[#1C1917] text-xs font-bold uppercase tracking-wider rounded-xl shadow-xs transition cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Tambah Produk Baru</span>
            </a>
        @endif
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

    @if(session('error'))
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-center gap-2 shadow-xs">
            <svg class="w-4 h-4 text-rose-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Card Tabel & Filter Pencarian -->
    <div class="bg-[#FFFFFF] rounded-2xl border border-[#E7E0DA] shadow-xs overflow-hidden">
        
        <!-- Filter Bar -->
        <div class="p-4 sm:p-5 border-b border-[#E7E0DA] bg-[#FAF7F4]">
            <form action="{{ route('products.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-center">
                <!-- Search -->
                <div class="lg:col-span-2">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari SKU, nama produk, atau deskripsi..."
                        class="w-full px-3.5 py-2 text-xs bg-[#FFFFFF] border border-[#E7E0DA] rounded-xl text-[#292524] placeholder-[#78716C]/60 focus:outline-none focus:border-[#BD6745] focus:ring-1 focus:ring-[#BD6745]">
                </div>

                <!-- Kategori Filter -->
                <div>
                    <select name="category_id" onchange="this.form.submit()"
                        class="w-full px-3 py-2 text-xs bg-[#FFFFFF] border border-[#E7E0DA] rounded-xl text-[#292524] focus:outline-none focus:border-[#BD6745]">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $c)
                            <option value="{{ $c->id }}" {{ $categoryId == $c->id ? 'selected' : '' }}>
                                {{ $c->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Supplier Filter -->
                <div>
                    <select name="supplier_id" onchange="this.form.submit()"
                        class="w-full px-3 py-2 text-xs bg-[#FFFFFF] border border-[#E7E0DA] rounded-xl text-[#292524] focus:outline-none focus:border-[#BD6745]">
                        <option value="">Semua Supplier</option>
                        @foreach($suppliers as $s)
                            <option value="{{ $s->id }}" {{ $supplierId == $s->id ? 'selected' : '' }}>
                                {{ $s->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Stok Kritis & Reset -->
                <div class="flex items-center gap-2">
                    <label class="inline-flex items-center gap-1.5 cursor-pointer text-xs text-[#78716C] select-none">
                        <input type="checkbox" name="low_stock" value="1" {{ $lowStock ? 'checked' : '' }} onchange="this.form.submit()"
                            class="w-4 h-4 rounded border-[#E7E0DA] text-[#BD6745] focus:ring-[#BD6745]">
                        <span class="{{ $lowStock ? 'font-bold text-[#BD6745]' : '' }}">Hanya Stok Tipis</span>
                    </label>
                    @if($search || $categoryId || $supplierId || $lowStock)
                        <a href="{{ route('products.index') }}" class="text-xs text-[#78716C] hover:text-[#BD6745] ml-auto underline">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Tabel Produk -->
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left text-[#78716C] tabular-nums">
                <thead class="text-[11px] text-[#292524] uppercase tracking-wider bg-[#FAF7F4] border-b border-[#E7E0DA]">
                    <tr>
                        <th class="px-5 py-3.5 font-heading font-semibold">Produk</th>
                        <th class="px-5 py-3.5 font-heading font-semibold">Kategori</th>
                        <th class="px-5 py-3.5 text-right font-heading font-semibold">Harga Beli</th>
                        <th class="px-5 py-3.5 text-right font-heading font-semibold">Harga Jual</th>
                        <th class="px-5 py-3.5 text-center font-heading font-semibold">Status Stok</th>
                        <th class="px-5 py-3.5 text-right font-heading font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E7E0DA]">
                    @forelse($products as $p)
                        <tr class="hover:bg-[#FAF7F4] transition">
                            <!-- Info Produk + Thumbnail -->
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3.5">
                                    <div class="w-10 h-10 rounded-lg bg-[#FAF7F4] border border-[#E7E0DA] flex-shrink-0 flex items-center justify-center overflow-hidden">
                                        @if($p->image && file_exists(public_path('storage/' . $p->image)))
                                            <img src="{{ asset('storage/' . $p->image) }}" alt="{{ $p->name }}" class="w-full h-full object-cover">
                                        @else
                                            <svg class="w-4 h-4 text-[#78716C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-medium text-[#292524] text-xs hover:text-[#BD6745]">
                                            <a href="{{ route('products.show', $p->id) }}">{{ $p->name }}</a>
                                        </div>
                                        <div class="text-[10px] font-mono tabular-nums text-[#78716C] mt-0.5">
                                            SKU: <span class="text-[#BD6745] font-semibold">{{ $p->sku }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Kategori & Supplier -->
                            <td class="px-5 py-3.5">
                                <span class="inline-block px-2 py-0.5 rounded text-[10px] font-medium bg-[#FAF7F4] text-[#292524] border border-[#E7E0DA]">
                                    {{ $p->category?->name ?? 'Tanpa Kategori' }}
                                </span>
                                @if($p->supplier)
                                    <div class="text-[10px] text-[#78716C] mt-0.5 truncate max-w-[150px]">
                                        {{ $p->supplier->name }}
                                    </div>
                                @endif
                            </td>

                            <!-- Harga Beli -->
                            <td class="px-5 py-3.5 text-right font-medium text-[#78716C]">
                                Rp {{ number_format($p->purchase_price, 0, ',', '.') }}
                            </td>

                            <!-- Harga Jual -->
                            <td class="px-5 py-3.5 text-right font-bold text-[#292524]">
                                Rp {{ number_format($p->selling_price, 0, ',', '.') }}
                            </td>

                            <!-- Status Stok -->
                            <td class="px-5 py-3.5 text-center">
                                @if($p->stock <= 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                        Habis (0)
                                    </span>
                                @elseif($p->stock <= $p->min_stock)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-amber-50 text-amber-800 border border-amber-200" title="Batas minimum: {{ $p->min_stock }}">
                                        Menipis ({{ $p->stock }} Unit)
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                        {{ $p->stock }} Unit
                                    </span>
                                @endif
                            </td>

                            <!-- Aksi -->
                            <td class="px-5 py-3.5 text-right space-x-2 whitespace-nowrap">
                                <a href="{{ route('products.show', $p->id) }}" class="text-[#78716C] hover:text-[#BD6745] font-medium">
                                    Detail
                                </a>
                                @if(in_array(Auth::user()->role, ['admin', 'manager']))
                                    <a href="{{ route('products.edit', $p->id) }}" class="text-[#BD6745] hover:text-[#A55636] font-medium">
                                        Edit
                                    </a>
                                @endif
                                @if(Auth::user()->role === 'admin')
                                    <form action="{{ route('products.destroy', $p->id) }}" method="POST" class="inline-block"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk {{ addslashes($p->name) }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-600 hover:text-rose-800 font-medium">
                                            Hapus
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-[#78716C]">
                                Tidak ada produk yang sesuai dengan kriteria pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
            <div class="p-4 border-t border-[#E7E0DA] bg-[#FAF7F4]">
                {{ $products->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
