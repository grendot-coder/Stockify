@extends('layouts.dashboard')

@section('content')
<div class="max-w-4xl mx-auto space-y-5">

    <div class="flex items-center justify-between">
        <a href="{{ route('products.index') }}" class="inline-flex items-center gap-1.5 text-xs font-medium text-[#A1A1AA] hover:text-[#F5F5F5] transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Kembali ke Katalog Produk
        </a>
    </div>

    <div class="bg-[#202429] rounded-2xl border border-[#343A40] p-6 shadow-xs">
        <span class="text-[11px] font-mono tracking-widest uppercase text-[#F5C842] font-semibold">Registrasi Inventaris</span>
        <h1 class="text-xl sm:text-2xl font-heading font-semibold text-[#F5F5F5] mt-0.5">Tambah Produk Baru</h1>
        <p class="text-xs text-[#A1A1AA] mt-1">Lengkapi informasi inventaris, modal, penetapan harga jual, persediaan fisik, dan atribut spesifikasi</p>
    </div>

    @if($errors->any())
    <div class="p-4 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs space-y-1.5 shadow-xs">
        <div class="flex items-center gap-2 font-semibold">
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

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf

        <div class="bg-[#202429] rounded-2xl border border-[#343A40] p-6 shadow-xs space-y-4">
            <h2 class="text-sm font-heading font-semibold text-[#F5F5F5] border-b border-[#343A40] pb-2.5">1. Informasi Utama Barang</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-[#F5F5F5] mb-1.5">
                        Kategori Produk <span class="text-[#F5C842]">*</span>
                    </label>
                    <select name="category_id" id="category_id" required
                        class="w-full px-3 py-2 text-xs bg-[#202429] border border-[#343A40] rounded-xl text-[#F5F5F5] focus:outline-none focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842]">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-medium text-[#F5F5F5]">
                            SKU / Kode Barang <span class="text-[#F5C842]">*</span>
                        </label>
                        <button type="button" id="btn-generate-sku" class="text-[11px] text-[#F5C842] hover:text-[#E8B92E] font-medium transition cursor-pointer">
                            Generate Otomatis
                        </button>
                    </div>
                    <input type="text" name="sku" id="sku" required value="{{ old('sku', $suggestedSku) }}"
                        class="w-full px-3 py-2 text-xs font-mono bg-[#202429] border border-[#343A40] rounded-xl text-[#F5F5F5] focus:outline-none focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842]">
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-[#F5F5F5] mb-1.5">
                    Nama Produk <span class="text-[#F5C842]">*</span>
                </label>
                <input type="text" name="name" required value="{{ old('name') }}" placeholder="Contoh: Lampu LED Philips 12W Putih"
                    class="w-full px-3 py-2 text-xs bg-[#202429] border border-[#343A40] rounded-xl text-[#F5F5F5] placeholder-[#A1A1AA]/60 focus:outline-none focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842]">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-[#F5F5F5] mb-1.5">
                        Rekanan Supplier (Pemasok)
                    </label>
                    <select name="supplier_id" id="supplier_id"
                        class="w-full px-3 py-2 text-xs bg-[#202429] border border-[#343A40] rounded-xl text-[#F5F5F5] focus:outline-none focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842]">
                        <option value="">-- Tanpa Supplier Terikat --</option>
                        @foreach($suppliers as $sup)
                        <option value="{{ $sup->id }}" {{ old('supplier_id') == $sup->id ? 'selected' : '' }}>
                            {{ $sup->name }} ({{ $sup->phone }})
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-[#F5F5F5] mb-1.5">
                        Lokasi Rak Penyimpanan
                    </label>
                    <select name="rack_location_id"
                        class="w-full px-3 py-2 text-xs bg-[#202429] border border-[#343A40] rounded-xl text-[#F5F5F5] focus:outline-none focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842]">
                        <option value="">-- Belum Ditentukan (Unassigned) --</option>
                        @foreach($rackLocations as $rack)
                        <option value="{{ $rack->id }}" {{ old('rack_location_id') == $rack->id ? 'selected' : '' }}>
                            {{ $rack->name }} ({{ $rack->zone ?: 'Zona Umum' }} - Terisi: {{ $rack->current_stock }}/{{ $rack->capacity }} Unit)
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-[#F5F5F5] mb-1.5">
                    Deskripsi / Catatan Produk
                </label>
                <textarea name="description" id="description" rows="3" placeholder="Tuliskan spesifikasi umum, lokasi rak, atau catatan lainnya..."
                    class="w-full px-3 py-2 text-xs bg-[#202429] border border-[#343A40] rounded-xl text-[#F5F5F5] placeholder-[#A1A1AA]/60 focus:outline-none focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842]">{{ old('description') }}</textarea>
            </div>
        </div>

        <div class="bg-[#202429] rounded-2xl border border-[#343A40] p-6 shadow-xs space-y-4">
            <h2 class="text-sm font-heading font-semibold text-[#F5F5F5] border-b border-[#343A40] pb-2.5">2. Penetapan Harga & Manajemen Persediaan</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-[#F5F5F5] mb-1.5">
                        Harga Beli (Modal Rp) <span class="text-[#F5C842]">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-xs text-[#A1A1AA] font-mono">Rp</span>
                        <input type="number" step="0.01" min="0" name="purchase_price" required value="{{ old('purchase_price', 0) }}"
                            class="w-full pl-9 pr-3 py-2 text-xs font-mono bg-[#202429] border border-[#343A40] rounded-xl text-[#F5F5F5] focus:outline-none focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842]">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-[#F5F5F5] mb-1.5">
                        Harga Jual (Rp) <span class="text-[#F5C842]">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-xs text-[#A1A1AA] font-mono">Rp</span>
                        <input type="number" step="0.01" min="0" name="selling_price" required value="{{ old('selling_price', 0) }}"
                            class="w-full pl-9 pr-3 py-2 text-xs font-mono bg-[#202429] border border-[#343A40] rounded-xl text-[#F5F5F5] focus:outline-none focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842]">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-[#F5F5F5] mb-1.5">
                        Jumlah Stok Awal (Unit / Pcs) <span class="text-[#F5C842]">*</span>
                    </label>
                    <input type="number" min="0" name="stock" required value="{{ old('stock', 0) }}"
                        class="w-full px-3 py-2 text-xs font-mono bg-[#202429] border border-[#343A40] rounded-xl text-[#F5F5F5] focus:outline-none focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842]">
                </div>

                <div>
                    <label class="block text-xs font-medium text-[#F5F5F5] mb-1.5">
                        Batas Minimum Stok (Peringatan Tipis) <span class="text-[#F5C842]">*</span>
                    </label>
                    <input type="number" min="0" name="min_stock" required value="{{ old('min_stock', (int) \App\Models\Setting::get('default_min_stock', 5)) }}"
                        class="w-full px-3 py-2 text-xs font-mono bg-[#202429] border border-[#343A40] rounded-xl text-[#F5F5F5] focus:outline-none focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842]">
                    <p class="text-[10px] text-[#A1A1AA] mt-1">Sistem otomatis menandai status peringatan jika stok menyentuh batas ini.</p>
                </div>
            </div>
        </div>

        <div class="bg-[#202429] rounded-2xl border border-[#343A40] p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-[#343A40] pb-2.5">
                <div>
                    <h2 class="text-sm font-heading font-semibold text-[#F5F5F5]">3. Visual / Foto Produk</h2>
                    <p class="text-[11px] text-[#A1A1AA]">Unggah foto barang untuk katalog inventaris & rekomendasi otomatis AI</p>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                <div id="image-preview-container" class="relative w-28 h-28 rounded-xl border-2 border-dashed border-[#343A40] bg-[#181C20] flex items-center justify-center overflow-hidden flex-shrink-0 group">
                    <img id="image-preview" src="#" alt="Preview" class="hidden w-full h-full object-cover">
                    <span id="image-placeholder" class="text-[#A1A1AA] text-[10px] text-center px-2">Belum ada foto</span>
                </div>
                <div class="flex-1 space-y-2">
                    <input type="file" name="image" id="product-image" accept="image/png, image/jpeg, image/jpg, image/webp"
                        class="block w-full text-xs text-[#A1A1AA] file:mr-3 file:py-2 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-[#F5C842]/15 file:text-[#F5C842] hover:file:bg-[#F5C842] hover:file:text-[#101214] transition cursor-pointer">
                    <p class="text-[10px] text-[#A1A1AA]">Format didukung: JPG, PNG, WEBP. Maksimal ukuran file 5 MB.</p>

                    <div id="ai-scan-controls" class="hidden pt-1">
                        <button type="button" id="btn-ai-analyze"
                            class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-gradient-to-r from-[#F5C842] to-[#E8B92E] text-[#101214] text-xs font-semibold shadow-md hover:brightness-110 active:scale-95 transition cursor-pointer">
                            <svg class="w-4 h-4 animate-spin hidden" id="ai-spinner" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                            <svg class="w-4 h-4" id="ai-sparkles-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z" />
                            </svg>
                            <span id="ai-btn-text">Analisis & Rekomendasi</span>
                        </button>
                    </div>
                </div>
            </div>

            <div id="ai-loading-box" class="hidden p-4 rounded-xl bg-[#181C20] border border-[#F5C842]/30 space-y-2 transition-all">
                <div class="flex items-center gap-3">
                    <div class="w-2.5 h-2.5 rounded-full bg-[#F5C842] animate-ping"></div>
                    <span class="text-xs font-medium text-[#F5F5F5]">Qwen 3.8 sedang menganalisis foto, mencocokkan kategori, supplier, dan menyusun deskripsi...</span>
                </div>
                <p class="text-[11px] text-[#A1A1AA] pl-5.5">Orang Sabar disayang Tuhan.</p>
            </div>

            <div id="ai-result-box" class="hidden p-4 rounded-xl bg-gradient-to-br from-[#1c2229] to-[#181C20] border border-emerald-500/40 space-y-3 shadow-sm transition-all">
                <div class="flex items-center justify-between border-b border-[#343A40]/80 pb-2">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        <h4 class="text-xs font-semibold text-emerald-400 font-mono">Rekomendasi AI Otomatis Diterapkan ke Formulir</h4>
                    </div>
                    <button type="button" id="btn-dismiss-ai" class="text-[11px] text-[#A1A1AA] hover:text-[#F5F5F5] transition cursor-pointer">Tutup ✕</button>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                    <div class="p-2.5 rounded-lg bg-[#202429] border border-[#343A40] flex items-center justify-between">
                        <span class="text-[#A1A1AA] text-[11px]">Kategori Rekomendasi:</span>
                        <span id="ai-res-category" class="font-medium text-[#F5C842] text-right truncate ml-2">-</span>
                    </div>
                    <div class="p-2.5 rounded-lg bg-[#202429] border border-[#343A40] flex items-center justify-between">
                        <span class="text-[#A1A1AA] text-[11px]">Supplier Rekomendasi:</span>
                        <span id="ai-res-supplier" class="font-medium text-[#F5C842] text-right truncate ml-2">-</span>
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] text-[#A1A1AA] mb-1">Deskripsi Hasil AI:</label>
                    <p id="ai-res-desc" class="text-xs text-[#E4E4E7] leading-relaxed italic bg-[#202429] p-3 rounded-lg border border-[#343A40]">-</p>
                </div>
            </div>

            <div id="ai-error-box" class="hidden p-3.5 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs space-y-1">
                <div class="flex items-center gap-2 font-semibold">
                    <svg class="w-4 h-4 text-rose-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <span>Analisis AI Gagal</span>
                </div>
                <p id="ai-error-message" class="text-[11px] text-rose-300 pl-6">Terjadi kendala saat menghubungkan ke model Qwen.</p>
            </div>
        </div>

        <div class="bg-[#202429] rounded-2xl border border-[#343A40] p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-[#343A40] pb-2.5">
                <div>
                    <h2 class="text-sm font-heading font-semibold text-[#F5F5F5]">4. Atribut Spesifikasi Tambahan (Opsional)</h2>
                    <p class="text-[11px] text-[#A1A1AA]">Tambahkan detail spesifik seperti Warna, Ukuran, Berat, atau Masa Garansi</p>
                </div>
                <button type="button" id="btn-add-attribute"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#181C20] hover:bg-[#F5C842]/15 text-[#F5C842] border border-[#343A40] text-xs font-semibold rounded-lg transition cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span>Tambah Baris</span>
                </button>
            </div>

            <div id="attributes-container" class="space-y-2.5">
                <div class="attribute-row flex items-center gap-3">
                    <div class="w-1/3">
                        <input type="text" name="attributes[0][name]" placeholder="Nama (cth: Warna)"
                            class="w-full px-3 py-1.5 text-xs bg-[#202429] border border-[#343A40] rounded-lg text-[#F5F5F5] placeholder-[#A1A1AA]/60 focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842] focus:outline-none">
                    </div>
                    <div class="flex-1">
                        <input type="text" name="attributes[0][value]" placeholder="Nilai (cth: Hitam Matte)"
                            class="w-full px-3 py-1.5 text-xs bg-[#202429] border border-[#343A40] rounded-lg text-[#F5F5F5] placeholder-[#A1A1AA]/60 focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842] focus:outline-none">
                    </div>
                    <button type="button" onclick="removeAttributeRow(this)"
                        class="p-1.5 text-rose-400 hover:text-rose-300 rounded-lg hover:bg-rose-500/15 transition cursor-pointer" title="Hapus Atribut">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-3">
            <a href="{{ route('products.index') }}"
                class="px-4 py-2.5 text-xs font-semibold text-[#A1A1AA] hover:text-[#F5F5F5] hover:bg-[#2A3036] rounded-xl transition">
                Batal
            </a>
            <button type="submit"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#F5C842] hover:bg-[#E8B92E] text-[#101214] text-xs font-semibold rounded-xl shadow-xs transition cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
                <span>Simpan Produk Baru</span>
            </button>
        </div>
    </form>

</div>

<script>
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
                imagePlaceholder.classList.add('hidden');
            }
            reader.readAsDataURL(file);
        } else {
            imagePreview.classList.add('hidden');
            imagePlaceholder.classList.remove('hidden');
        }
    });

    let attributeIndex = 1;
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
                <input type="text" name="attributes[${attributeIndex}][value]" placeholder="Nilai (cth: 1 Tahun Resmi)"
                    class="w-full px-3 py-1.5 text-xs bg-[#202429] border border-[#343A40] rounded-lg text-[#F5F5F5] placeholder-[#A1A1AA]/60 focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842] focus:outline-none">
            </div>
            <button type="button" onclick="removeAttributeRow(this)"
                class="p-1.5 text-rose-400 hover:text-rose-300 rounded-lg hover:bg-rose-500/15 transition cursor-pointer" title="Hapus Atribut">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
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

    const categorySelect = document.getElementById('category_id');
    const skuInput = document.getElementById('sku');
    const btnGenerateSku = document.getElementById('btn-generate-sku');

    function fetchSku(categoryId) {
        let url = '{{ route("products.generate-sku") }}';
        if (categoryId) {
            url += `?category_id=${categoryId}`;
        }
        fetch(url)
            .then(res => res.json())
            .then(data => {
                if (data.sku) {
                    skuInput.value = data.sku;
                }
            })
            .catch(err => console.error('Gagal mengambil SKU:', err));
    }

    categorySelect.addEventListener('change', function() {
        fetchSku(this.value);
    });

    btnGenerateSku.addEventListener('click', function() {
        fetchSku(categorySelect.value);
    });

    const aiScanControls = document.getElementById('ai-scan-controls');
    const btnAiAnalyze = document.getElementById('btn-ai-analyze');
    const aiSpinner = document.getElementById('ai-spinner');
    const aiSparklesIcon = document.getElementById('ai-sparkles-icon');
    const aiBtnText = document.getElementById('ai-btn-text');
    const aiLoadingBox = document.getElementById('ai-loading-box');
    const aiResultBox = document.getElementById('ai-result-box');
    const aiErrorBox = document.getElementById('ai-error-box');
    const aiErrorMessage = document.getElementById('ai-error-message');
    const aiResDesc = document.getElementById('ai-res-desc');
    const btnDismissAi = document.getElementById('btn-dismiss-ai');

    const descriptionTextarea = document.getElementById('description');
    const supplierSelect = document.getElementById('supplier_id');
    const aiResCategory = document.getElementById('ai-res-category');
    const aiResSupplier = document.getElementById('ai-res-supplier');

    let currentAiDesc = '';

    function downscaleImage(file, maxDimension = 800) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = new Image();
                img.onload = function() {
                    let width = img.width;
                    let height = img.height;

                    if (width > height && width > maxDimension) {
                        height = Math.round((height * maxDimension) / width);
                        width = maxDimension;
                    } else if (height > maxDimension) {
                        width = Math.round((width * maxDimension) / height);
                        height = maxDimension;
                    }

                    const canvas = document.createElement('canvas');
                    canvas.width = width;
                    canvas.height = height;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, width, height);

                    const dataUrl = canvas.toDataURL('image/jpeg', 0.85);
                    const base64 = dataUrl.split(',')[1];
                    resolve(base64);
                };
                img.onerror = reject;
                img.src = e.target.result;
            };
            reader.onerror = reject;
            reader.readAsDataURL(file);
        });
    }

    imageInput.addEventListener('change', function(e) {
        if (e.target.files && e.target.files[0]) {
            aiScanControls.classList.remove('hidden');
            aiResultBox.classList.add('hidden');
            aiErrorBox.classList.add('hidden');
        } else {
            aiScanControls.classList.add('hidden');
            aiResultBox.classList.add('hidden');
            aiErrorBox.classList.add('hidden');
        }
    });

    if (btnDismissAi) {
        btnDismissAi.addEventListener('click', function() {
            aiResultBox.classList.add('hidden');
        });
    }

    btnAiAnalyze.addEventListener('click', async function() {
        const file = imageInput.files[0];
        if (!file) {
            alert('Silakan pilih berkas foto produk terlebih dahulu.');
            return;
        }

        btnAiAnalyze.disabled = true;
        aiSpinner.classList.remove('hidden');
        aiSparklesIcon.classList.add('hidden');
        aiBtnText.textContent = 'Menganalisis Visual...';
        aiLoadingBox.classList.remove('hidden');
        aiResultBox.classList.add('hidden');
        aiErrorBox.classList.add('hidden');

        try {
            const optimizedBase64 = await downscaleImage(file, 800);

            const formData = new FormData();
            formData.append('image_base64', optimizedBase64);
            formData.append('_token', '{{ csrf_token() }}');

            const response = await fetch('{{ route("ai.analyze-image") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message || 'Gagal memproses visual foto.');
            }

            if (data.success && data.data) {
                const aiData = data.data;

                if (aiData.category_id && categorySelect) {
                    categorySelect.value = aiData.category_id;
                    fetchSku(aiData.category_id);
                    if (aiResCategory) {
                        aiResCategory.textContent = aiData.category_name || `Kategori #${aiData.category_id}`;
                    }
                } else {
                    if (aiResCategory) {
                        aiResCategory.textContent = 'Tidak Teridentifikasi';
                    }
                }

                if (aiData.supplier_id && supplierSelect) {
                    supplierSelect.value = aiData.supplier_id;
                    if (aiResSupplier) {
                        aiResSupplier.textContent = aiData.supplier_name || `Supplier #${aiData.supplier_id}`;
                    }
                } else {
                    if (aiResSupplier) {
                        aiResSupplier.textContent = 'Tanpa Supplier Terikat';
                    }
                }

                if (aiData.suggested_description) {
                    currentAiDesc = aiData.suggested_description;
                    if (descriptionTextarea) {
                        descriptionTextarea.value = currentAiDesc;
                    }

                    if (aiResDesc) {
                        aiResDesc.textContent = `"${currentAiDesc}"`;
                    }
                }

                aiResultBox.classList.remove('hidden');
            } else {
                throw new Error(data.message || 'Tidak menerima hasil rekomendasi dari AI.');
            }
        } catch (err) {
            console.error('AI Recommendation Error:', err);
            aiErrorMessage.textContent = err.message || 'Terjadi kesalahan saat memproses gambar dengan Qwen 3.8';
            aiErrorBox.classList.remove('hidden');
        } finally {
            btnAiAnalyze.disabled = false;
            aiSpinner.classList.add('hidden');
            aiSparklesIcon.classList.remove('hidden');
            aiBtnText.textContent = 'Analisis Ulang dengan AI';
            aiLoadingBox.classList.add('hidden');
        }
    });
</script>
@endsection