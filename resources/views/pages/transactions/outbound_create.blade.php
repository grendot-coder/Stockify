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
        <h1 class="font-heading text-2xl sm:text-3xl font-semibold text-[#F5F5F5]">Pengeluaran Barang Keluar</h1>
        <p class="text-xs text-[#A1A1AA] mt-1">Pencatatan barang keluar untuk penjualan, retur barang cacat, atau alokasi operasional</p>
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

    <!-- Formulir Barang Keluar -->
    <form action="{{ route('transactions.outbound.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="bg-[#202429] rounded-2xl border border-[#343A40] p-6 shadow-xs space-y-5">
            
            <!-- No. Invoice / Referensi (Auto Generate & Anti-Dupe Realtime) -->
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-semibold text-[#F5F5F5] uppercase tracking-wider">
                        No. Transaksi / Surat Pengeluaran
                    </label>
                    <button type="button" id="btn-generate-invoice" class="text-[11px] text-[#F5C842] hover:text-[#E8B92E] font-medium transition cursor-pointer flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        Auto Generate
                    </button>
                </div>
                <div class="relative">
                    <input type="text" name="invoice_number" id="invoice_number" value="{{ old('invoice_number', $suggestedInvoice) }}" placeholder="Otomatis dibuat jika dikosongkan"
                        class="w-full px-3.5 py-2.5 text-xs font-mono bg-[#202429] border border-[#343A40] rounded-xl text-[#F5F5F5] placeholder-[#A1A1AA]/60 focus:outline-none focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842] transition">
                    <div id="invoice-spinner" class="hidden absolute right-3 top-3">
                        <svg class="animate-spin h-3.5 w-3.5 text-[#F5C842]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                    </div>
                </div>
                <div id="invoice-alert-container" class="mt-2 hidden"></div>
                <p class="text-[10px] text-[#A1A1AA] mt-1">Otomatis terisi oleh sistem dan anti-duplikat. Dapat diganti nomor referensi pengeluaran internal.</p>
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
                    <p id="stock-warning" class="hidden text-[11px] text-rose-400 font-medium mt-1.5">
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
                class="px-4 py-2.5 text-xs font-semibold text-[#A1A1AA] hover:text-[#F5F5F5] hover:bg-[#2A3036] rounded-xl transition">
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

    // Auto-Generate & Anti-Dupe Realtime Invoice
    const invoiceInput = document.getElementById('invoice_number');
    const btnGenerateInvoice = document.getElementById('btn-generate-invoice');
    const invoiceAlertContainer = document.getElementById('invoice-alert-container');
    const invoiceSpinner = document.getElementById('invoice-spinner');
    const submitBtn = document.querySelector('button[type="submit"]');

    let invoiceCheckTimer = null;

    function checkInvoiceRealtime(val) {
        clearTimeout(invoiceCheckTimer);
        const trimmed = (val || '').trim();
        if (!trimmed) {
            invoiceAlertContainer.className = "mt-2 p-2 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-300 text-[11px] flex items-center gap-1.5";
            invoiceAlertContainer.innerHTML = '<span>Nomor invoice dikosongkan. Sistem akan membuatkan nomor invoice otomatis yang unik saat disimpan.</span>';
            invoiceAlertContainer.classList.remove('hidden');
            invoiceInput?.classList.remove('border-rose-500', 'focus:border-rose-500', 'border-emerald-500');
            if (submitBtn) submitBtn.disabled = false;
            return;
        }

        invoiceSpinner?.classList.remove('hidden');

        invoiceCheckTimer = setTimeout(() => {
            fetch(`{{ route('transactions.check-invoice') }}?invoice=${encodeURIComponent(trimmed)}&type=out`)
                .then(res => res.json())
                .then(data => {
                    invoiceSpinner?.classList.add('hidden');
                    invoiceAlertContainer.classList.remove('hidden');

                    if (!data.available) {
                        invoiceInput?.classList.add('border-rose-500', 'focus:border-rose-500');
                        invoiceInput?.classList.remove('border-emerald-500');
                        invoiceAlertContainer.className = "mt-2 p-2.5 rounded-xl bg-rose-500/15 border border-rose-500/40 text-rose-300 text-xs space-y-1.5";
                        invoiceAlertContainer.innerHTML = `
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-rose-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                <span>${data.message}</span>
                            </div>
                            ${data.suggested_invoice ? `
                            <div class="flex items-center gap-2 pt-1 border-t border-rose-500/20">
                                <span class="text-[10px] text-rose-300/80">Saran Anti-Bentrok:</span>
                                <button type="button" onclick="applySuggestedInvoice('${data.suggested_invoice}')" class="px-2 py-0.5 rounded-md bg-[#F5C842] text-[#101214] font-bold text-[10px] hover:bg-[#E8B92E] transition cursor-pointer">
                                    Pakai ${data.suggested_invoice}
                                </button>
                            </div>` : ''}
                        `;
                        if (submitBtn) {
                            submitBtn.disabled = true;
                            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                        }
                    } else {
                        invoiceInput?.classList.remove('border-rose-500', 'focus:border-rose-500');
                        invoiceInput?.classList.add('border-emerald-500/60');
                        invoiceAlertContainer.className = "mt-2 p-2 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-[11px] flex items-center gap-1.5";
                        invoiceAlertContainer.innerHTML = `
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>${data.message || 'Nomor invoice tersedia dan unik.'}</span>
                        `;
                        if (submitBtn) {
                            submitBtn.disabled = false;
                            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                        }
                    }
                })
                .catch(err => {
                    invoiceSpinner?.classList.add('hidden');
                    console.error('Gagal verifikasi invoice:', err);
                });
        }, 300);
    }

    window.applySuggestedInvoice = function(inv) {
        if (invoiceInput) {
            invoiceInput.value = inv;
            checkInvoiceRealtime(inv);
        }
    };

    invoiceInput?.addEventListener('input', function() {
        checkInvoiceRealtime(this.value);
    });

    btnGenerateInvoice?.addEventListener('click', function() {
        invoiceSpinner?.classList.remove('hidden');
        fetch(`{{ route('transactions.generate-invoice') }}?type=out`)
            .then(res => res.json())
            .then(data => {
                invoiceSpinner?.classList.add('hidden');
                if (data.invoice && invoiceInput) {
                    invoiceInput.value = data.invoice;
                    checkInvoiceRealtime(data.invoice);
                }
            })
            .catch(err => {
                invoiceSpinner?.classList.add('hidden');
                console.error(err);
            });
    });
</script>
@endsection