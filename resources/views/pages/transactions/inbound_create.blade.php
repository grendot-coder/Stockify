@extends('layouts.dashboard')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <a href="{{ route('transactions.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#A1A1AA] hover:text-[#F5F5F5] transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Kembali ke Riwayat Transaksi
        </a>
    </div>

    <div class="bg-[#202429] rounded-2xl border border-[#343A40] p-6 shadow-xs">
        <div class="flex items-center gap-2 mb-1">
            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded text-[10px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 uppercase tracking-wider">
                Inbound Transaction
            </span>
        </div>
        <h1 class="font-heading text-2xl sm:text-3xl font-semibold text-[#F5F5F5]">Penerimaan Barang Masuk</h1>
        <p class="text-xs text-[#A1A1AA] mt-1">Pencatatan pasokan inventaris baru dari supplier rekanan atau transfer masuk gudang</p>
    </div>

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

    <form action="{{ route('transactions.inbound.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="bg-[#202429] rounded-2xl border border-[#343A40] p-6 shadow-xs space-y-5">
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-semibold text-[#F5F5F5] uppercase tracking-wider">
                            No. Invoice / Surat Jalan
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
                    <p class="text-[10px] text-[#A1A1AA] mt-1">Otomatis terisi oleh sistem dan anti-duplikat. Dapat diganti nomor surat jalan fisik supplier.</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#F5F5F5] mb-1.5 uppercase tracking-wider">
                        Rekanan Supplier
                    </label>
                    <div class="w-full px-3.5 py-2.5 text-xs bg-[#181C20] border border-[#343A40] rounded-xl text-[#A1A1AA] flex items-center justify-between">
                        <span id="supplier-display-name">Pilih barang untuk melihat supplier</span>
                        <span class="text-[10px] text-[#A1A1AA]/60 font-mono">Otomatis</span>
                    </div>
                    <p class="text-[10px] text-[#A1A1AA] mt-1">Mengikuti supplier yang terdaftar pada produk terpilih.</p>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-[#F5F5F5] mb-1.5 uppercase tracking-wider">
                    Pilih Produk / Barang <span class="text-[#F5C842]">*</span>
                </label>
                <select name="product_id" id="product_id" required
                    class="w-full px-3.5 py-2.5 text-xs bg-[#202429] border border-[#343A40] rounded-xl text-[#F5F5F5] focus:outline-none focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842]">
                    <option value="">-- Pilih Barang yang Diterima --</option>
                    @foreach($products as $p)
                    <option value="{{ $p->id }}" 
                        data-stock="{{ $p->stock }}" 
                        data-sku="{{ $p->sku }}"
                        data-supplier="{{ $p->supplier?->name ?? 'Tanpa Supplier Terikat' }}"
                        {{ old('product_id') == $p->id ? 'selected' : '' }}>
                        {{ $p->name }} (SKU: {{ $p->sku }}) â€” Stok Fisik Saat Ini: {{ $p->stock }} Unit
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-[#F5F5F5] mb-1.5 uppercase tracking-wider">
                        Jumlah Kuantitas Masuk <span class="text-[#F5C842]">*</span>
                    </label>
                    <input type="number" min="1" name="quantity" required value="{{ old('quantity', 1) }}"
                        class="w-full px-3.5 py-2.5 text-xs bg-[#202429] border border-[#343A40] rounded-xl text-[#F5F5F5] focus:outline-none focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842]">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#F5F5F5] mb-1.5 uppercase tracking-wider">
                        Alur Verifikasi Transaksi Inbound
                    </label>
                    <input type="hidden" name="status" value="confirmed">
                    <div class="p-2.5 rounded-xl bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span class="text-xs font-medium text-emerald-400">Otomatis Disetujui & Stok Langsung Bertambah</span>
                        </div>
                        <span class="px-2 py-0.5 rounded text-[9px] font-mono font-bold bg-emerald-500/20 text-emerald-300">TANPA KONFIRMASI MANUAL</span>
                    </div>
                    <p class="text-[10px] text-[#A1A1AA] mt-1">Stok fisik produk di rak gudang otomatis terupdate seketika.</p>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-[#F5F5F5] mb-1.5 uppercase tracking-wider">
                    Catatan / Nomor DO / Keterangan Masuk
                </label>
                <textarea name="notes" rows="3" placeholder="Contoh: Pengiriman batch PO-091, kondisi fisik baik dan segel utuh..."
                    class="w-full px-3.5 py-2.5 text-xs bg-[#202429] border border-[#343A40] rounded-xl text-[#F5F5F5] placeholder-[#A1A1AA]/60 focus:outline-none focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842]">{{ old('notes') }}</textarea>
            </div>

        </div>

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

<script>
    const productSelect = document.getElementById('product_id');
    const supplierDisplay = document.getElementById('supplier-display-name');

    function updateSupplierDisplay() {
        if (!productSelect || !supplierDisplay) return;
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        if (selectedOption && selectedOption.value) {
            const supplierName = selectedOption.getAttribute('data-supplier');
            supplierDisplay.textContent = supplierName || 'Tanpa Supplier Terikat';
            supplierDisplay.className = 'text-[#F5F5F5] font-medium';
        } else {
            supplierDisplay.textContent = 'Pilih barang untuk melihat supplier';
            supplierDisplay.className = 'text-[#A1A1AA]';
        }
    }

    if (productSelect) {
        productSelect.addEventListener('change', updateSupplierDisplay);
        updateSupplierDisplay();
    }

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
            fetch(`{{ route('transactions.check-invoice') }}?invoice=${encodeURIComponent(trimmed)}&type=in`)
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
        fetch(`{{ route('transactions.generate-invoice') }}?type=in`)
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
