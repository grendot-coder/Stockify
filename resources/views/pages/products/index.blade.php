@extends('layouts.dashboard')

@section('content')
<div class="space-y-6">

    <!-- Header & Tombol Tambah -->
    <div class="bg-[#202429] rounded-2xl border border-[#343A40] p-6 shadow-xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="w-2 h-2 rounded-full bg-[#F5C842]"></span>
                <span class="text-[11px] uppercase tracking-[0.2em] text-[#F5C842] font-semibold">Katalog Inventaris</span>
            </div>
            <h1 class="font-heading text-2xl sm:text-3xl font-semibold text-[#F5F5F5] tracking-tight">Master Data Produk</h1>
            <p class="text-xs text-[#A1A1AA] mt-1">Daftar inventaris barang gudang, level stok fisik, dan rincian penetapan harga</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            @if(in_array(Auth::user()->role, ['admin', 'manager']))
                <a href="{{ route('products.export') }}"
                    class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-[#F5C842] bg-[#F5C842]/15 hover:bg-[#F5C842] hover:text-[#101214] rounded-xl transition border border-[#F5C842]/30">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    <span>Ekspor CSV</span>
                </a>
                <button type="button" data-modal-target="modal-import-products" data-modal-toggle="modal-import-products"
                    class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-[#F5F5F5] bg-[#2A3036] hover:bg-[#343A40] rounded-xl transition border border-[#343A40]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l4-4m0 0l4 4m-4-4v12"/></svg>
                    <span>Impor CSV</span>
                </button>
                <a href="{{ route('products.create') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-[#F5C842] hover:bg-[#E8B92E] text-[#101214] text-xs font-semibold rounded-xl shadow-xs transition duration-150 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span>Tambah Produk</span>
                </a>
            @endif
        </div>
    </div>

    <!-- Alert Notifikasi -->
    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs flex items-center gap-2 shadow-xs">
            <svg class="w-4 h-4 text-rose-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Card Tabel & Filter Pencarian -->
    <div class="bg-[#202429] rounded-2xl border border-[#343A40] shadow-xs overflow-hidden">
        
        <!-- Filter Bar -->
        <div class="p-4 sm:p-5 border-b border-[#343A40] bg-[#181C20]">
            <form action="{{ route('products.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-center">
                <!-- Search -->
                <div class="lg:col-span-2">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari SKU, nama produk, atau deskripsi..."
                        class="w-full px-3.5 py-2 text-xs bg-[#202429] border border-[#343A40] rounded-xl text-[#F5F5F5] placeholder-[#A1A1AA]/60 focus:outline-none focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842]">
                </div>

                <!-- Kategori Filter -->
                <div>
                    <select name="category_id" onchange="this.form.submit()"
                        class="w-full px-3 py-2 text-xs bg-[#202429] border border-[#343A40] rounded-xl text-[#F5F5F5] focus:outline-none focus:border-[#F5C842]">
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
                        class="w-full px-3 py-2 text-xs bg-[#202429] border border-[#343A40] rounded-xl text-[#F5F5F5] focus:outline-none focus:border-[#F5C842]">
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
                    <label class="inline-flex items-center gap-1.5 cursor-pointer text-xs text-[#A1A1AA] select-none">
                        <input type="checkbox" name="low_stock" value="1" {{ $lowStock ? 'checked' : '' }} onchange="this.form.submit()"
                            class="w-4 h-4 rounded border-[#343A40] bg-[#181C20] text-[#F5C842] focus:ring-[#F5C842]">
                        <span class="{{ $lowStock ? 'font-bold text-[#F5C842]' : '' }}">Hanya Stok Tipis</span>
                    </label>
                    @if($search || $categoryId || $supplierId || $lowStock)
                        <a href="{{ route('products.index') }}" class="text-xs text-[#A1A1AA] hover:text-[#F5C842] ml-auto underline">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Tabel Produk -->
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left text-[#A1A1AA] tabular-nums">
                <thead class="text-[11px] text-[#F5F5F5] uppercase tracking-wider bg-[#181C20] border-b border-[#343A40]">
                    <tr>
                        <th class="px-5 py-3.5 font-heading font-semibold">Produk</th>
                        <th class="px-5 py-3.5 font-heading font-semibold">Kategori</th>
                        <th class="px-5 py-3.5 text-right font-heading font-semibold">Harga Beli</th>
                        <th class="px-5 py-3.5 text-right font-heading font-semibold">Harga Jual</th>
                        <th class="px-5 py-3.5 text-center font-heading font-semibold">Status Stok</th>
                        <th class="px-5 py-3.5 text-right font-heading font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#343A40]">
                    @forelse($products as $p)
                        <tr class="hover:bg-[#2A3036] transition">
                            <!-- Info Produk + Thumbnail -->
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3.5">
                                    <div class="w-10 h-10 rounded-lg bg-[#181C20] border border-[#343A40] flex-shrink-0 flex items-center justify-center overflow-hidden">
                                        @if($p->image && file_exists(public_path('storage/' . $p->image)))
                                            <img src="{{ asset('storage/' . $p->image) }}" alt="{{ $p->name }}" class="w-full h-full object-cover">
                                        @else
                                            <svg class="w-4 h-4 text-[#A1A1AA]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-medium text-[#F5F5F5] text-xs hover:text-[#F5C842]">
                                            <a href="{{ route('products.show', $p->id) }}">{{ $p->name }}</a>
                                        </div>
                                        <div class="text-[10px] font-mono tabular-nums text-[#A1A1AA] mt-0.5">
                                            SKU: <span class="text-[#F5C842] font-semibold">{{ $p->sku }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Kategori & Supplier -->
                            <td class="px-5 py-3.5">
                                <span class="inline-block px-2 py-0.5 rounded text-[10px] font-medium bg-[#181C20] text-[#F5F5F5] border border-[#343A40]">
                                    {{ $p->category?->name ?? 'Tanpa Kategori' }}
                                </span>
                                @if($p->supplier)
                                    <div class="text-[10px] text-[#A1A1AA] mt-0.5 truncate max-w-[150px]">
                                        {{ $p->supplier->name }}
                                    </div>
                                @endif
                            </td>

                            <!-- Harga Beli -->
                            <td class="px-5 py-3.5 text-right font-medium text-[#A1A1AA]">
                                Rp {{ number_format($p->purchase_price, 0, ',', '.') }}
                            </td>

                            <!-- Harga Jual -->
                            <td class="px-5 py-3.5 text-right font-bold text-[#F5F5F5]">
                                Rp {{ number_format($p->selling_price, 0, ',', '.') }}
                            </td>

                            <!-- Status Stok -->
                            <td class="px-5 py-3.5 text-center">
                                @if($p->stock <= 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-rose-500/10 text-rose-400 border border-rose-500/30">
                                        Habis (0)
                                    </span>
                                @elseif($p->stock <= $p->min_stock)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/30" title="Batas minimum: {{ $p->min_stock }}">
                                        Menipis ({{ $p->stock }} Unit)
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">
                                        {{ $p->stock }} Unit
                                    </span>
                                @endif
                            </td>

                            <!-- Aksi -->
                            <td class="px-5 py-3.5 text-right space-x-2 whitespace-nowrap">
                                <a href="{{ route('products.show', $p->id) }}" class="text-[#A1A1AA] hover:text-[#F5C842] font-medium">
                                    Detail
                                </a>
                                @if(in_array(Auth::user()->role, ['admin', 'manager']))
                                    <a href="{{ route('products.edit', $p->id) }}" class="text-[#F5C842] hover:text-[#E8B92E] font-medium">
                                        Edit
                                    </a>
                                @endif
                                @if(Auth::user()->role === 'admin')
                                    <form action="{{ route('products.destroy', $p->id) }}" method="POST" class="inline-block"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk {{ addslashes($p->name) }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-400 hover:text-rose-300 font-medium">
                                            Hapus
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-[#A1A1AA]">
                                Tidak ada produk yang sesuai dengan kriteria pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
            <div class="p-4 border-t border-[#343A40] bg-[#181C20]">
                {{ $products->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Impor CSV Produk -->
    <div id="modal-import-products" tabindex="-1" aria-hidden="true"
        class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full bg-black/60 backdrop-blur-xs flex items-center justify-center">
        <div class="relative w-full max-w-lg max-h-full">
            <div class="relative bg-[#202429] rounded-2xl shadow-xl border border-[#343A40] overflow-hidden">
                <div class="flex items-center justify-between p-4 border-b border-[#343A40] bg-[#181C20]">
                    <div>
                        <h3 class="text-sm font-semibold text-[#F5F5F5] font-heading">Impor Massal Katalog Produk</h3>
                        <p class="text-[11px] text-[#A1A1AA]">Unggah berkas spreadsheet CSV untuk memperbarui atau menambah produk</p>
                    </div>
                    <button type="button" data-modal-hide="modal-import-products"
                        class="text-[#A1A1AA] bg-transparent hover:bg-[#2A3036] hover:text-[#F5F5F5] rounded-lg text-xs w-7 h-7 ml-auto inline-flex justify-center items-center">
                        ✕
                    </button>
                </div>

                <form action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                    @csrf

                    <div class="p-4 rounded-xl bg-[#181C20] border border-[#F5C842]/30 space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold text-[#F5F5F5]">Panduan Format File:</span>
                            <a href="{{ route('products.template') }}" class="text-xs text-[#F5C842] hover:underline font-semibold flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                Unduh Template CSV
                            </a>
                        </div>
                        <p class="text-[11px] text-[#A1A1AA]">Pastikan kolom CSV mencakup: <code class="bg-[#202429] px-1.5 py-0.5 rounded border border-[#343A40] text-[#F5F5F5] font-mono text-[10px]">sku, name, category, supplier, purchase_price, selling_price, stock, min_stock, description</code></p>
                    </div>

                    <div>
                        <label for="file" class="block text-xs font-semibold text-[#F5F5F5] mb-2">Pilih Berkas CSV (.csv)</label>
                        <input type="file" name="file" id="file" accept=".csv,text/csv" required
                            class="w-full text-xs text-[#A1A1AA] file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-[#F5C842]/15 file:text-[#F5C842] hover:file:bg-[#F5C842] hover:file:text-[#101214] file:transition cursor-pointer border border-[#343A40] rounded-xl p-1 bg-[#181C20]">
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-[#343A40]">
                        <button type="button" data-modal-hide="modal-import-products"
                            class="px-4 py-2 text-xs font-medium text-[#A1A1AA] hover:text-[#F5F5F5] hover:bg-[#2A3036] rounded-xl transition">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-5 py-2 text-xs font-semibold bg-[#F5C842] hover:bg-[#E8B92E] text-[#101214] rounded-xl shadow-xs transition">
                            Mulai Impor Produk
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
