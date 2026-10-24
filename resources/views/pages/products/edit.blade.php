@extends('layouts.dashboard')

@section('content')
<div class="max-w-4xl mx-auto space-y-5">

    <!-- Breadcrumb & Navigasi Kembali -->
    <div class="flex items-center justify-between">
        <a href="{{ route('products.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#A1A1AA] hover:text-[#F5F5F5] transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Kembali ke Daftar Produk
        </a>
        <a href="{{ route('products.show', $product->id) }}" class="text-xs text-[#F5C842] hover:text-[#E8B92E] font-semibold transition">
            Lihat Halaman Detail Produk &rarr;
        </a>
    </div>

    <!-- Header Judul -->
    <div class="bg-[#202429] rounded-2xl border border-[#343A40] p-6 shadow-xs">
        <span class="text-[11px] font-mono tracking-widest uppercase text-[#F5C842] font-semibold">Pembaruan Inventaris</span>
        <h1 class="text-xl sm:text-2xl font-editorial font-medium text-[#F5F5F5] mt-0.5">Ubah Data Produk</h1>
        <p class="text-xs text-[#A1A1AA] mt-1">Perbarui informasi, harga modal, batas minimum stok, atau spesifikasi barang: <span class="font-semibold text-[#F5F5F5]">{{ $product->name }}</span></p>
    </div>

    <!-- Alert Kesalahan Validasi -->
    @if($errors->any())
        <div class="p-4 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs space-y-1.5 shadow-xs">
            <div class="flex items-center gap-2 font-semibold">
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

    <!-- Form Edit Produk -->
    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf
        @method('PUT')

        <!-- 1. Informasi Utama -->
        <div class="bg-[#202429] rounded-2xl border border-[#343A40] p-6 shadow-xs space-y-4">
            <h2 class="text-sm font-editorial font-medium text-[#F5F5F5] border-b border-[#343A40] pb-2.5">1. Informasi Utama Barang</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Kategori -->
                <div>
                    <label class="block text-xs font-semibold text-[#F5F5F5] mb-1.5">
                        Kategori Produk <span class="text-[#F5C842]">*</span>
                    </label>
                    <select name="category_id" id="category_id" required
                        class="w-full px-3 py-2 text-xs bg-[#202429] border border-[#343A40] rounded-xl text-[#F5F5F5] focus:outline-none focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842]">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- SKU -->
                <div>
                    <label class="block text-xs font-semibold text-[#F5F5F5] mb-1.5">
                        SKU / Kode Barang <span class="text-[#F5C842]">*</span>
                    </label>
                    <input type="text" name="sku" id="sku" required value="{{ old('sku', $product->sku) }}"
                        class="w-full px-3 py-2 text-xs font-mono bg-[#202429] border border-[#343A40] rounded-xl text-[#F5F5F5] focus:outline-none focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842]">
                </div>
            </div>

            <!-- Nama Produk -->
            <div>
                <label class="block text-xs font-semibold text-[#F5F5F5] mb-1.5">
                    Nama Produk <span class="text-[#F5C842]">*</span>
                </label>
                <input type="text" name="name" required value="{{ old('name', $product->name) }}"
                    class="w-full px-3 py-2 text-xs bg-[#202429] border border-[#343A40] rounded-xl text-[#F5F5F5] focus:outline-none focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842]">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Rekanan Supplier -->
                <div>
                    <label class="block text-xs font-semibold text-[#F5F5F5] mb-1.5">
                        Rekanan Supplier (Pemasok)
                    </label>
                    <select name="supplier_id"
                        class="w-full px-3 py-2 text-xs bg-[#202429] border border-[#343A40] rounded-xl text-[#F5F5F5] focus:outline-none focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842]">
                        <option value="">-- Tanpa Supplier Terikat --</option>
                        @foreach($suppliers as $sup)
                            <option value="{{ $sup->id }}" {{ old('supplier_id', $product->supplier_id) == $sup->id ? 'selected' : '' }}>
                                {{ $sup->name }} ({{ $sup->phone }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Lokasi Rak Gudang (Pindah Rak) -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-semibold text-[#F5F5F5]">
                            Lokasi Rak Gudang
                        </label>
                        @if($product->rackLocation)
                            <span class="text-[10px] font-mono text-[#F5C842] font-semibold">Saat ini: {{ $product->rackLocation->name }}</span>
                        @else
                            <span class="text-[10px] font-mono text-[#A1A1AA]">Belum dialokasikan</span>
                        @endif
                    </div>
                    <select name="rack_location_id"
                        class="w-full px-3 py-2 text-xs bg-[#202429] border border-[#343A40] rounded-xl text-[#F5F5F5] focus:outline-none focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842]">
                        <option value="">-- Belum Ditentukan (Unassigned) --</option>
                        @foreach($rackLocations as $rack)
                            <option value="{{ $rack->id }}" {{ old('rack_location_id', $product->rack_location_id) == $rack->id ? 'selected' : '' }}>
                                {{ $rack->name }} ({{ $rack->zone ?: 'Zona Umum' }} - Terisi: {{ $rack->current_stock }}/{{ $rack->capacity }} Unit)
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Deskripsi -->
            <div>
                <label class="block text-xs font-semibold text-[#F5F5F5] mb-1.5">
                    Deskripsi / Catatan Produk
                </label>
                <textarea name="description" rows="3"
                    class="w-full px-3 py-2 text-xs bg-[#202429] border border-[#343A40] rounded-xl text-[#F5F5F5] focus:outline-none focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842]">{{ old('description', $product->description) }}</textarea>
            </div>
        </div>

        <!-- 2. Harga & Manajemen Stok -->
        <div class="bg-[#202429] rounded-2xl border border-[#343A40] p-6 shadow-xs space-y-4">
            <h2 class="text-sm font-editorial font-medium text-[#F5F5F5] border-b border-[#343A40] pb-2.5">2. Penetapan Harga & Manajemen Persediaan</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Harga Beli -->
                <div>
                    <label class="block text-xs font-semibold text-[#F5F5F5] mb-1.5">
                        Harga Beli (Modal Rp) <span class="text-[#F5C842]">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-xs text-[#A1A1AA] font-mono">Rp</span>
                        <input type="number" step="0.01" min="0" name="purchase_price" required value="{{ old('purchase_price', $product->purchase_price) }}"
                            class="w-full pl-9 pr-3 py-2 text-xs font-mono bg-[#202429] border border-[#343A40] rounded-xl text-[#F5F5F5] focus:outline-none focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842]">
                    </div>
                </div>

                <!-- Harga Jual -->
                <div>
                    <label class="block text-xs font-semibold text-[#F5F5F5] mb-1.5">
                        Harga Jual (Rp) <span class="text-[#F5C842]">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-xs text-[#A1A1AA] font-mono">Rp</span>
                        <input type="number" step="0.01" min="0" name="selling_price" required value="{{ old('selling_price', $product->selling_price) }}"
                            class="w-full pl-9 pr-3 py-2 text-xs font-mono bg-[#202429] border border-[#343A40] rounded-xl text-[#F5F5F5] focus:outline-none focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842]">
                    </div>
                </div>

                <!-- Stok Saat Ini -->
                <div>
                    <label class="block text-xs font-semibold text-[#F5F5F5] mb-1.5">
                        Jumlah Stok Saat Ini (Unit / Pcs) <span class="text-[#F5C842]">*</span>
                    </label>
                    <input type="number" min="0" name="stock" required value="{{ old('stock', $product->stock) }}"
                        class="w-full px-3 py-2 text-xs font-mono bg-[#202429] border border-[#343A40] rounded-xl text-[#F5F5F5] focus:outline-none focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842]">
                    <p class="text-[10px] text-[#A1A1AA] mt-1">Catatan: Penyesuaian stok reguler dianjurkan melalui modul Transaksi atau Stock Opname.</p>
                </div>

                <!-- Batas Minimum Stok -->
                <div>
                    <label class="block text-xs font-semibold text-[#F5F5F5] mb-1.5">
                        Batas Minimum Stok (Peringatan Tipis) <span class="text-[#F5C842]">*</span>
                    </label>
                    <input type="number" min="0" name="min_stock" required value="{{ old('min_stock', $product->min_stock) }}"
                        class="w-full px-3 py-2 text-xs font-mono bg-[#202429] border border-[#343A40] rounded-xl text-[#F5F5F5] focus:outline-none focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842]">
                </div>
            </div>
        </div>

        <!-- 3. Foto Produk -->
        <div class="bg-[#202429] rounded-2xl border border-[#343A40] p-6 shadow-xs space-y-4">
            <h2 class="text-sm font-editorial font-medium text-[#F5F5F5] border-b border-[#343A40] pb-2.5">3. Visual / Foto Produk</h2>

            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                <div id="image-preview-container" class="w-24 h-24 rounded-xl border border-[#343A40] bg-[#181C20] flex items-center justify-center overflow-hidden flex-shrink-0">
                    @if($product->image && file_exists(public_path('storage/' . $product->image)))
                        <img id="image-preview" src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        <span id="image-placeholder" class="hidden text-[#A1A1AA] text-[10px] text-center px-2">Belum ada foto</span>
                    @else
                        <img id="image-preview" src="#" alt="Preview" class="hidden w-full h-full object-cover">
                        <span id="image-placeholder" class="text-[#A1A1AA] text-[10px] text-center px-2">Belum ada foto</span>
                    @endif
                </div>
                <div class="flex-1 space-y-1.5">
                    <input type="file" name="image" id="product-image" accept="image/png, image/jpeg, image/jpg, image/webp"
                        class="block w-full text-xs text-[#A1A1AA] file:mr-3 file:py-2 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-[#F5C842]/15 file:text-[#F5C842] hover:file:bg-[#F5C842] hover:file:text-[#101214] transition">
                    <p class="text-[10px] text-[#A1A1AA]">Pilih berkas baru jika ingin mengganti foto saat ini. Format: JPG, PNG, WEBP (Maks 2 MB).</p>
                </div>
            </div>
        </div>

        <!-- 4. Atribut Tambahan (Varian/Spesifikasi) -->
        <div class="bg-[#202429] rounded-2xl border border-[#343A40] p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-[#343A40] pb-2.5">
                <div>
                    <h2 class="text-sm font-editorial font-medium text-[#F5F5F5]">4. Atribut Spesifikasi Tambahan</h2>
                    <p class="text-[11px] text-[#A1A1AA]">Detail spesifik produk seperti Warna, Ukuran, Berat, atau Masa Garansi</p>
                </div>
                <button type="button" id="btn-add-attribute"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#181C20] hover:bg-[#F5C842]/15 text-[#F5C842] border border-[#343A40] text-xs font-semibold rounded-lg transition cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span>+ Tambah Baris</span>
                </button>
            </div>

            <div id="attributes-container" class="space-y-2.5">
                @php
                    $existingAttrs = old('attributes', $product->attributes->map(fn($a) => ['name' => $a->attribute_name, 'value' => $a->attribute_value])->toArray());
                    if (empty($existingAttrs)) {
                        $existingAttrs = [['name' => '', 'value' => '']];
                    }
                @endphp

                @foreach($existingAttrs as $idx => $attr)
                    <div class="attribute-row flex items-center gap-3">
                        <div class="w-1/3">
                            <input type="text" name="attributes[{{ $idx }}][name]" value="{{ $attr['name'] ?? '' }}" placeholder="Nama (cth: Warna)"
                                class="w-full px-3 py-1.5 text-xs bg-[#202429] border border-[#343A40] rounded-lg text-[#F5F5F5] placeholder-[#A1A1AA]/60 focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842] focus:outline-none">
                        </div>
                        <div class="flex-1">
                            <input type="text" name="attributes[{{ $idx }}][value]" value="{{ $attr['value'] ?? '' }}" placeholder="Nilai (cth: Hitam Matte)"
                                class="w-full px-3 py-1.5 text-xs bg-[#202429] border border-[#343A40] rounded-lg text-[#F5F5F5] placeholder-[#A1A1AA]/60 focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842] focus:outline-none">
                        </div>
                        <button type="button" onclick="removeAttributeRow(this)"
                            class="p-1.5 text-[#A1A1AA] hover:text-rose-600 rounded-lg hover:bg-rose-500/10 transition cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Tombol Aksi Simpan -->
        <div class="flex items-center justify-end gap-3 pt-3">
            <a href="{{ route('products.index') }}"
                class="px-4 py-2.5 text-xs font-semibold text-[#A1A1AA] hover:text-[#F5F5F5] hover:bg-[#181C20] rounded-xl transition">
                Batal
            </a>
            <button type="submit"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#F5C842] hover:bg-[#E8B92E] text-[#101214] text-xs font-semibold rounded-xl shadow-xs transition cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
                <span>Perbarui Data Produk</span>
            </button>
        </div>
    </form>

</div>

<script>
    // Live Image Preview
    const imageInput = document.getElementById('product-image');
    const imagePreview = document.getElementById('image-preview');
    const imagePlaceholder = document.getElementById('image-placeholder');

    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(evt) {
                imagePreview.src = evt.target.result;
                imagePreview.classList.remove('hidden');
                if (imagePlaceholder) imagePlaceholder.classList.add('hidden');
            }
            reader.readAsDataURL(file);
        }
    });

    // Dynamic Attribute Rows
    let attributeIndex = {{ count($existingAttrs) }};
    const btnAddAttr = document.getElementById('btn-add-attribute');
    const container = document.getElementById('attributes-container');

    btnAddAttr.addEventListener('click', function() {
        const row = document.createElement('div');
        row.className = 'attribute-row flex items-center gap-3';
        row.innerHTML = `
            <div class="w-1/3">
                <input type="text" name="attributes[${attributeIndex}][name]" placeholder="Nama (cth: Garansi)"
                    class="w-full px-3 py-1.5 text-xs bg-[#202429] border border-[#343A40] rounded-lg text-[#F5F5F5] placeholder-[#A1A1AA]/60 focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842] focus:outline-none">
            </div>
            <div class="flex-1">
                <input type="text" name="attributes[${attributeIndex}][value]" placeholder="Nilai (cth: 2 Tahun Garansi)"
                    class="w-full px-3 py-1.5 text-xs bg-[#202429] border border-[#343A40] rounded-lg text-[#F5F5F5] placeholder-[#A1A1AA]/60 focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842] focus:outline-none">
            </div>
            <button type="button" onclick="removeAttributeRow(this)"
                class="p-1.5 text-[#A1A1AA] hover:text-rose-600 rounded-lg hover:bg-rose-500/10 transition cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </button>
        `;
        container.appendChild(row);
        attributeIndex++;
    });

    function removeAttributeRow(button) {
        const rows = container.querySelectorAll('.attribute-row');
        if (rows.length > 1) {
            button.closest('.attribute-row').remove();
        } else {
            const inputs = button.closest('.attribute-row').querySelectorAll('input');
            inputs.forEach(i => i.value = '');
        }
    }
</script>
@endsection
