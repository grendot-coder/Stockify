@extends('layouts.dashboard')

@section('content')
<div class="space-y-6">

    <!-- Header Sambutan Berdasarkan Role -->
    <div class="bg-[#202429] rounded-2xl border border-[#343A40] p-6 shadow-xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="w-2 h-2 rounded-full bg-[#F5C842]"></span>
                <span class="text-[11px] uppercase tracking-[0.2em] text-[#F5C842] font-semibold">
                    @if($user->isAdmin())
                        Portal Administrasi & Kontrol Sistem
                    @elseif($user->isManager())
                        Pusat Otorisasi & Manajemen Logistik
                    @else
                        Stasiun Kerja Operasional Gudang
                    @endif
                </span>
            </div>
            <h1 class="font-heading text-2xl sm:text-3xl font-semibold text-[#F5F5F5] tracking-tight">
                Selamat Datang, {{ $user->name }}
            </h1>
            <p class="text-xs text-[#A1A1AA] mt-1">
                @if($user->isAdmin())
                    Otorisasi: <strong class="text-[#F5F5F5]">Administrator Sistem</strong>. Akses menyeluruh ke analitik mutasi, audit jejak aktivitas, dan manajemen pengguna.
                @elseif($user->isManager())
                    Otorisasi: <strong class="text-[#F5F5F5]">Manajer Gudang</strong>. Tinjau transaksi pending, pantau akurasi stock opname, dan verifikasi restock barang kritis.
                @else
                    Otorisasi: <strong class="text-[#F5F5F5]">Staff Operasional</strong>. Catat penerimaan barang masuk, siapkan dokumen pengeluaran, dan lakukan penghitungan fisik.
                @endif
            </p>
        </div>
        <div class="flex items-center gap-2">
            <div class="text-xs text-[#A1A1AA] bg-[#181C20] px-3.5 py-2 rounded-xl border border-[#343A40] whitespace-nowrap font-mono tabular-nums">
                {{ now()->translatedFormat('l, d F Y') }}
            </div>
            @if($user->isAdmin() || $user->isManager())
            <a href="{{ route('reports.stock') }}" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-[#F5C842] bg-[#F5C842]/15 hover:bg-[#F5C842] hover:text-white rounded-xl transition border border-[#F5C842]/30">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Ekspor Laporan
            </a>
            @endif
        </div>
    </div>

    <!-- Peringatan Stok Tipis (Warm Callout) -->
    @if($lowStockCount > 0)
    <div class="p-5 rounded-2xl bg-[#202429] border border-[#F5C842]/40 text-[#F5F5F5] shadow-xs relative overflow-hidden">
        <div class="flex items-start gap-3.5">
            <div class="w-8 h-8 rounded-lg bg-[#F5C842]/15 text-[#F5C842] flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div class="flex-1">
                <div class="flex items-center justify-between">
                    <h3 class="font-heading text-base text-[#F5F5F5] font-semibold">
                        Perhatian: Terdapat {{ $lowStockCount }} produk mendekati atau di bawah batas minimum stok.
                    </h3>
                    <a href="{{ route('products.index', ['low_stock' => 1]) }}" class="text-xs text-[#F5C842] hover:underline font-semibold whitespace-nowrap">
                        Lihat Semua &rarr;
                    </a>
                </div>
                <p class="text-xs text-[#A1A1AA] mt-0.5">Disarankan untuk segera menerbitkan pemesanan ulang (*restock*) ke supplier rekanan:</p>
                <div class="mt-3 flex flex-wrap gap-2">
                    @foreach($lowStockProducts->take(6) as $lp)
                    <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs bg-[#181C20] border border-[#343A40] text-[#F5F5F5]">
                        <span class="font-medium text-[#F5F5F5]">{{ $lp->name }}</span>
                        <span class="mx-2 text-[#343A40]">•</span>
                        <span class="text-[#F5C842] font-mono tabular-nums font-bold">{{ $lp->stock }} Unit</span>
                        <span class="text-[10px] text-[#A1A1AA] ml-1">(Min: {{ $lp->min_stock }})</span>
                    </span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- 4 Kotak Ringkasan Utama (60-30-10 Rule) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        <!-- 1. Total Jenis Barang -->
        <div class="p-5 bg-[#202429] rounded-2xl border border-[#343A40] shadow-xs flex flex-col justify-between hover:border-[#F5C842]/50 transition group">
            <span class="text-xs font-semibold text-[#A1A1AA] uppercase tracking-wider">Katalog Produk</span>
            <div class="font-heading text-3xl font-semibold tabular-nums text-[#F5F5F5] mt-2 group-hover:text-[#F5C842] transition">
                {{ number_format($totalProducts) }} <span class="font-sans text-xs font-normal text-[#A1A1AA]">SKU Terdaftar</span>
            </div>
            <p class="text-[11px] text-[#A1A1AA] mt-2">{{ $totalCategories }} Kelompok kategori aktif</p>
        </div>

        <!-- 2. Total Stok Fisik -->
        <div class="p-5 bg-[#202429] rounded-2xl border border-[#343A40] shadow-xs flex flex-col justify-between hover:border-[#F5C842]/50 transition group">
            <span class="text-xs font-semibold text-[#A1A1AA] uppercase tracking-wider">Total Fisik di Gudang</span>
            <div class="font-heading text-3xl font-semibold tabular-nums text-[#F5C842] mt-2">
                {{ number_format($totalStockQty) }} <span class="font-sans text-xs font-normal text-[#A1A1AA]">Unit</span>
            </div>
            <p class="text-[11px] text-[#A1A1AA] mt-2">Disuplai oleh {{ $totalSuppliers }} Rekanan</p>
        </div>

        <!-- 3. Nilai Modal Barang / Valuasi -->
        <div class="p-5 bg-[#202429] rounded-2xl border border-[#343A40] shadow-xs flex flex-col justify-between hover:border-[#F5C842]/50 transition group">
            <span class="text-xs font-semibold text-[#A1A1AA] uppercase tracking-wider">Total Nilai Persediaan</span>
            <div class="font-heading text-2xl font-semibold tabular-nums text-[#F5F5F5] mt-2 truncate">
                Rp {{ number_format($totalInventoryValue, 0, ',', '.') }}
            </div>
            <p class="text-[11px] text-[#A1A1AA] mt-2">Estimasi nilai modal persediaan</p>
        </div>

        <!-- 4. Opname & Akurasi / Status Stok -->
        <div class="p-5 bg-[#202429] rounded-2xl border border-[#343A40] shadow-xs flex flex-col justify-between hover:border-[#F5C842]/50 transition group">
            <span class="text-xs font-semibold text-[#A1A1AA] uppercase tracking-wider">
                @if($user->isStaff())
                    Aktivitas Hari Ini
                @else
                    Akurasi Stock Opname
                @endif
            </span>
            <div class="font-heading text-3xl font-semibold tabular-nums text-[#F5F5F5] mt-2">
                @if($user->isStaff())
                    {{ $inboundTodayCount + $outboundTodayCount }} <span class="font-sans text-xs font-normal text-[#A1A1AA]">Transaksi</span>
                @else
                    @if($totalOpnames > 0)
                        {{ round(($matchedOpnames / $totalOpnames) * 100) }}%
                        <span class="font-sans text-xs font-normal text-[#A1A1AA]">Sesuai</span>
                    @else
                        100% <span class="font-sans text-xs font-normal text-[#A1A1AA]">Terverifikasi</span>
                    @endif
                @endif
            </div>
            <p class="text-[11px] text-[#A1A1AA] mt-2">
                @if($user->isStaff())
                    {{ $inboundTodayCount }} Masuk / {{ $outboundTodayCount }} Keluar
                @else
                    {{ $matchedOpnames }} dari {{ $totalOpnames }} opname presisi
                @endif
            </p>
        </div>

    </div>

    <!-- WIDGET KHUSUS ROLE: MANAGER & STAFF -->
    @if($user->isManager())
        <!-- Bagian Khusus Manajer: Transaksi Pending Butuh Otorisasi -->
        <div class="bg-[#202429] rounded-2xl border border-amber-200/80 p-5 shadow-xs">
            <div class="flex items-center justify-between pb-3 border-b border-[#343A40]">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-400 border border-amber-500/30 flex items-center justify-center font-bold text-xs">
                        {{ $pendingTransactions->count() }}
                    </div>
                    <div>
                        <h2 class="font-heading text-lg font-semibold text-[#F5F5F5]">Transaksi Butuh Otorisasi Manajer</h2>
                        <p class="text-xs text-[#A1A1AA]">Tinjau dan setujui atau tolak transaksi yang diajukan oleh staff gudang</p>
                    </div>
                </div>
                <a href="{{ route('transactions.index', ['status' => 'pending']) }}" class="text-xs text-[#F5C842] hover:underline font-semibold">
                    Kelola Semua Pending &rarr;
                </a>
            </div>

            @if($pendingTransactions->isEmpty())
                <div class="py-6 text-center text-xs text-[#A1A1AA]">
                    <svg class="w-8 h-8 text-emerald-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Semua transaksi telah disetujui. Tidak ada transaksi yang menunggu otorisasi saat ini.
                </div>
            @else
                <div class="overflow-x-auto mt-3">
                    <table class="w-full text-xs text-left text-[#A1A1AA] tabular-nums">
                        <thead class="text-[11px] text-[#F5F5F5] uppercase tracking-wider bg-[#181C20] border-b border-[#343A40]">
                            <tr>
                                <th class="px-4 py-2.5 font-heading font-semibold">No. Invoice</th>
                                <th class="px-4 py-2.5 font-heading font-semibold">Diajukan Oleh</th>
                                <th class="px-4 py-2.5 font-heading font-semibold">Produk</th>
                                <th class="px-4 py-2.5 font-heading font-semibold">Jenis</th>
                                <th class="px-4 py-2.5 text-right font-heading font-semibold">Jumlah</th>
                                <th class="px-4 py-2.5 text-center font-heading font-semibold">Aksi Otorisasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#343A40]">
                            @foreach($pendingTransactions as $pt)
                            <tr class="hover:bg-[#181C20] transition">
                                <td class="px-4 py-3 font-mono font-medium text-[#F5F5F5]">
                                    <a href="{{ route('transactions.show', $pt->id) }}" class="hover:text-[#F5C842]">
                                        {{ $pt->invoice_number }}
                                    </a>
                                </td>
                                <td class="px-4 py-3 text-[#F5F5F5]">
                                    {{ $pt->user?->name ?? 'Staff' }}
                                    <div class="text-[10px] text-[#A1A1AA]">{{ $pt->created_at->diffForHumans() }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="font-medium text-[#F5F5F5]">{{ $pt->product?->name }}</span>
                                    <span class="block text-[10px] font-mono text-[#A1A1AA]">Stok Saat Ini: {{ $pt->product?->stock }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    @if($pt->type === 'in')
                                        <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">+ Masuk</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-[#F5C842]/15 text-[#F5C842] border border-[#F5C842]/30">- Keluar</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right font-bold text-[#F5F5F5] text-xs">
                                    {{ number_format($pt->quantity) }} Unit
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <form action="{{ route('transactions.confirm', $pt->id) }}" method="POST" onsubmit="return confirm('Setujui transaksi ini?')">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1 text-[11px] font-semibold bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition shadow-xs">
                                                Setujui
                                            </button>
                                        </form>
                                        <form action="{{ route('transactions.reject', $pt->id) }}" method="POST" onsubmit="return confirm('Tolak transaksi ini?')">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1 text-[11px] font-semibold bg-red-600 hover:bg-red-700 text-white rounded-lg transition shadow-xs">
                                                Tolak
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    @elseif($user->isStaff())
        <!-- Bagian Khusus Staff: Daftar Tugas Operasional Gudang -->
        <div class="space-y-6">

            <!-- Card Utama: Daftar Tugas Operasional -->
            <div class="bg-[#202429] rounded-2xl border border-[#343A40] p-6 shadow-xs">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-[#343A40] gap-3">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#F5C842] animate-pulse"></span>
                            <span class="text-[11px] uppercase tracking-wider text-[#F5C842] font-bold">Stasiun Pemenuhan Tugas Fisik</span>
                        </div>
                        <h2 class="font-heading text-xl font-semibold text-[#F5F5F5]">Daftar Tugas Operasional Staff Gudang</h2>
                        <p class="text-xs text-[#A1A1AA] mt-0.5">Periksa fisik penerimaan barang dari supplier dan siapkan packing pengeluaran pesanan</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">
                            {{ $pendingInbounds->count() }} Masuk Perlu Diperiksa
                        </span>
                        <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-semibold bg-[#F5C842]/15 text-[#F5C842] border border-[#F5C842]/30">
                            {{ $pendingOutbounds->count() }} Keluar Perlu Disiapkan
                        </span>
                    </div>
                </div>

                <div class="mt-6 space-y-6">

                    <!-- Tugas 1: Barang Masuk yang Perlu Diperiksa -->
                    <div class="rounded-xl border border-emerald-200/80 bg-emerald-50/20 p-4">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-xs">
                                    {{ $pendingInbounds->count() }}
                                </span>
                                <h3 class="font-heading text-base font-semibold text-[#F5F5F5]">1. Barang Masuk yang Perlu Diperiksa Fisik</h3>
                            </div>
                            <span class="text-[11px] text-[#A1A1AA]">Hitung fisik barang tiba dari supplier lalu konfirmasi penerimaan</span>
                        </div>

                        @if($pendingInbounds->isEmpty())
                            <div class="py-5 text-center text-xs text-[#A1A1AA] bg-[#202429] rounded-lg border border-[#343A40]">
                                <svg class="w-6 h-6 text-emerald-500 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Seluruh kiriman barang masuk telah selesai diperiksa. Tidak ada tugas penerimaan pending.
                            </div>
                        @else
                            <div class="overflow-x-auto bg-[#202429] rounded-xl border border-[#343A40]">
                                <table class="w-full text-xs text-left text-[#A1A1AA] tabular-nums">
                                    <thead class="text-[11px] text-[#F5F5F5] uppercase tracking-wider bg-[#181C20] border-b border-[#343A40]">
                                        <tr>
                                            <th class="px-4 py-2.5 font-heading font-semibold">No. Invoice</th>
                                            <th class="px-4 py-2.5 font-heading font-semibold">Supplier & Pengaju</th>
                                            <th class="px-4 py-2.5 font-heading font-semibold">Barang & Kategori</th>
                                            <th class="px-4 py-2.5 text-right font-heading font-semibold">Jumlah Tiba</th>
                                            <th class="px-4 py-2.5 font-heading font-semibold">Catatan Pengiriman</th>
                                            <th class="px-4 py-2.5 text-center font-heading font-semibold">Aksi Tugas</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-[#343A40]">
                                        @foreach($pendingInbounds as $inTask)
                                        <tr class="hover:bg-[#181C20] transition">
                                            <td class="px-4 py-3 font-mono font-medium text-[#F5F5F5]">
                                                <a href="{{ route('transactions.show', $inTask->id) }}" class="hover:text-[#F5C842] font-semibold">
                                                    {{ $inTask->invoice_number }}
                                                </a>
                                                <div class="text-[10px] text-[#A1A1AA] font-sans">{{ $inTask->created_at->diffForHumans() }}</div>
                                            </td>
                                            <td class="px-4 py-3 text-[#F5F5F5]">
                                                <div class="font-medium text-[#F5F5F5]">{{ $inTask->supplier?->name ?? 'Supplier Umum' }}</div>
                                                <div class="text-[10px] text-[#A1A1AA]">Input: {{ $inTask->user?->name ?? 'Staff' }}</div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="font-medium text-[#F5F5F5]">{{ $inTask->product?->name }}</div>
                                                <div class="text-[10px] font-mono text-[#A1A1AA]">SKU: {{ $inTask->product?->sku }} | Stok Saat Ini: {{ $inTask->product?->stock }}</div>
                                            </td>
                                            <td class="px-4 py-3 text-right">
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">
                                                    +{{ number_format($inTask->quantity) }} Unit
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-[#A1A1AA] max-w-[200px] truncate">
                                                {{ $inTask->notes ?: '-' }}
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <div class="flex items-center justify-center gap-1.5">
                                                    <form action="{{ route('transactions.confirm', $inTask->id) }}" method="POST" onsubmit="return confirm('Konfirmasi penerimaan fisik {{ $inTask->quantity }} unit barang ini ke gudang?')">
                                                        @csrf
                                                        <button type="submit" class="px-3 py-1.5 text-[11px] font-semibold bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition shadow-xs flex items-center gap-1">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                            Konfirmasi Diterima
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('transactions.reject', $inTask->id) }}" method="POST" onsubmit="return confirm('Tolak barang masuk ini karena ada kerusakan / ketidaksesuaian?')">
                                                        @csrf
                                                        <button type="submit" class="px-2.5 py-1.5 text-[11px] font-semibold bg-red-100 hover:bg-red-200 text-red-700 rounded-lg transition">
                                                            Tolak
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                    <!-- Tugas 2: Barang Keluar yang Perlu Disiapkan -->
                    <div class="rounded-xl border border-[#F5C842]/30 bg-[#F5C842]/10 p-4">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 rounded-lg bg-[#F5C842]/15 text-[#F5C842] flex items-center justify-center font-bold text-xs border border-[#F5C842]/30">
                                    {{ $pendingOutbounds->count() }}
                                </span>
                                <h3 class="font-heading text-base font-semibold text-[#F5F5F5]">2. Barang Keluar yang Perlu Disiapkan & Packing</h3>
                            </div>
                            <span class="text-[11px] text-[#A1A1AA]">Ambil barang dari rak gudang, packing, lalu konfirmasi pengeluaran</span>
                        </div>

                        @if($pendingOutbounds->isEmpty())
                            <div class="py-5 text-center text-xs text-[#A1A1AA] bg-[#202429] rounded-lg border border-[#343A40]">
                                <svg class="w-6 h-6 text-emerald-500 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Seluruh barang keluar telah selesai disiapkan. Tidak ada antrean packing pending.
                            </div>
                        @else
                            <div class="overflow-x-auto bg-[#202429] rounded-xl border border-[#343A40]">
                                <table class="w-full text-xs text-left text-[#A1A1AA] tabular-nums">
                                    <thead class="text-[11px] text-[#F5F5F5] uppercase tracking-wider bg-[#181C20] border-b border-[#343A40]">
                                        <tr>
                                            <th class="px-4 py-2.5 font-heading font-semibold">No. Invoice</th>
                                            <th class="px-4 py-2.5 font-heading font-semibold">Pemohon / Pelanggan</th>
                                            <th class="px-4 py-2.5 font-heading font-semibold">Barang & Rak</th>
                                            <th class="px-4 py-2.5 text-right font-heading font-semibold">Jumlah Keluar</th>
                                            <th class="px-4 py-2.5 font-heading font-semibold">Tujuan / Alasan</th>
                                            <th class="px-4 py-2.5 text-center font-heading font-semibold">Aksi Tugas</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-[#343A40]">
                                        @foreach($pendingOutbounds as $outTask)
                                        <tr class="hover:bg-[#181C20] transition">
                                            <td class="px-4 py-3 font-mono font-medium text-[#F5F5F5]">
                                                <a href="{{ route('transactions.show', $outTask->id) }}" class="hover:text-[#F5C842] font-semibold">
                                                    {{ $outTask->invoice_number }}
                                                </a>
                                                <div class="text-[10px] text-[#A1A1AA] font-sans">{{ $outTask->created_at->diffForHumans() }}</div>
                                            </td>
                                            <td class="px-4 py-3 text-[#F5F5F5]">
                                                <div class="font-medium text-[#F5F5F5]">{{ $outTask->user?->name ?? 'Staff Gudang' }}</div>
                                                <div class="text-[10px] text-[#A1A1AA]">Status: Siap Diambil</div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="font-medium text-[#F5F5F5]">{{ $outTask->product?->name }}</div>
                                                <div class="text-[10px] font-mono text-[#A1A1AA]">SKU: {{ $outTask->product?->sku }} | Tersedia: <strong class="text-[#F5F5F5]">{{ $outTask->product?->stock }} Unit</strong></div>
                                            </td>
                                            <td class="px-4 py-3 text-right">
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-[#F5C842]/15 text-[#F5C842] border border-[#F5C842]/30">
                                                    -{{ number_format($outTask->quantity) }} Unit
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-[#A1A1AA] max-w-[200px] truncate">
                                                {{ $outTask->notes ?: 'Distribusi / Penjualan' }}
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <div class="flex items-center justify-center gap-1.5">
                                                    <form action="{{ route('transactions.confirm', $outTask->id) }}" method="POST" onsubmit="return confirm('Konfirmasi bahwa barang sebanyak {{ $outTask->quantity }} unit telah diambil dan selesai disiapkan?')">
                                                        @csrf
                                                        <button type="submit" class="px-3 py-1.5 text-[11px] font-semibold bg-[#F5C842] hover:bg-[#E8B92E] text-[#101214] rounded-lg transition shadow-xs flex items-center gap-1">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                            Konfirmasi Pengeluaran
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('transactions.reject', $outTask->id) }}" method="POST" onsubmit="return confirm('Tolak atau batalkan pengeluaran barang ini?')">
                                                        @csrf
                                                        <button type="submit" class="px-2.5 py-1.5 text-[11px] font-semibold bg-red-100 hover:bg-red-200 text-red-700 rounded-lg transition">
                                                            Batalkan
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                </div>

                <!-- Shortcut Bar Operasional Cepat di Bawah -->
                <div class="mt-6 pt-4 border-t border-[#343A40] flex flex-wrap items-center justify-between gap-3">
                    <span class="text-xs text-[#A1A1AA]">Tindakan Tambahan:</span>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('transactions.inbound') }}" class="px-3 py-1.5 text-xs font-semibold text-emerald-800 bg-emerald-50 hover:bg-emerald-100 rounded-xl transition border border-emerald-200">
                            + Catat Barang Masuk Baru
                        </a>
                        <a href="{{ route('transactions.outbound') }}" class="px-3 py-1.5 text-xs font-semibold text-[#F5C842] bg-[#F5C842]/15 hover:bg-[#F5C842]/25 rounded-xl transition border border-[#F5C842]/30">
                            - Catat Permintaan Barang Keluar
                        </a>
                        <a href="{{ route('opnames.create') }}" class="px-3 py-1.5 text-xs font-semibold text-blue-800 bg-blue-50 hover:bg-blue-100 rounded-xl transition border border-blue-200">
                            📋 Input Stock Opname
                        </a>
                    </div>
                </div>
            </div>

        </div>
    @endif

    <!-- SEKSI GRAFIK VISUAL (Fase 7: ApexCharts) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- 1. Tren Mutasi 7 Hari Terakhir (Area Chart - 2 Kolom) -->
        <div class="lg:col-span-2 bg-[#202429] rounded-2xl border border-[#343A40] p-5 shadow-xs flex flex-col justify-between">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b border-[#343A40]">
                <div>
                    <h2 class="font-heading text-lg font-semibold text-[#F5F5F5]">Tren Mutasi Barang (7 Hari Terakhir)</h2>
                    <p class="text-xs text-[#A1A1AA] mt-0.5">Perbandingan kuantitas unit Barang Masuk (Inbound) vs Barang Keluar (Outbound)</p>
                </div>
                <div class="flex items-center gap-3 text-xs font-mono">
                    <span class="inline-flex items-center gap-1.5 text-emerald-700">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span> Inbound
                    </span>
                    <span class="inline-flex items-center gap-1.5 text-[#F5C842]">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#F5C842]"></span> Outbound
                    </span>
                </div>
            </div>
            <div class="pt-4">
                <div id="mutation-trend-chart"
                     class="w-full min-h-[280px]"
                     data-dates="{{ json_encode($chartDates) }}"
                     data-in="{{ json_encode($chartInbound) }}"
                     data-out="{{ json_encode($chartOutbound) }}"></div>
            </div>
        </div>

        <!-- 2. Komposisi Persediaan per Kategori (Donut Chart - 1 Kolom) -->
        <div class="bg-[#202429] rounded-2xl border border-[#343A40] p-5 shadow-xs flex flex-col justify-between">
            <div class="pb-3 border-b border-[#343A40]">
                <h2 class="font-heading text-lg font-semibold text-[#F5F5F5]">Persediaan per Kategori</h2>
                <p class="text-xs text-[#A1A1AA] mt-0.5">Proporsi volume fisik barang di rak penyimpanan</p>
            </div>
            <div class="pt-4 flex-1 flex flex-col items-center justify-center">
                @if(count($catSeries) > 0 && array_sum($catSeries) > 0)
                    <div id="category-distribution-chart"
                         class="w-full min-h-[280px]"
                         data-labels="{{ json_encode($catLabels) }}"
                         data-series="{{ json_encode($catSeries) }}"></div>
                @else
                    <div class="py-12 text-center text-xs text-[#A1A1AA]">
                        Belum ada persediaan fisik yang tercatat dalam kelompok kategori.
                    </div>
                @endif
            </div>
        </div>

    </div>

    <!-- Dua Kolom: Tabel Mutasi & Jejak Audit Aktivitas -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        <!-- Tabel Transaksi Terkini (2 Kolom) -->
        <div class="xl:col-span-2 bg-[#202429] rounded-2xl border border-[#343A40] overflow-hidden shadow-xs">
            <div class="p-5 border-b border-[#343A40] flex items-center justify-between">
                <div>
                    <h2 class="font-heading text-lg font-semibold text-[#F5F5F5]">Riwayat Mutasi Terkini</h2>
                    <p class="text-xs text-[#A1A1AA] mt-0.5">5 transaksi penerimaan dan pengeluaran barang terbaru</p>
                </div>
                <a href="{{ route('transactions.index') }}" class="text-xs text-[#F5C842] hover:underline font-semibold">
                    Semua Transaksi &rarr;
                </a>
            </div>

            @if($recentTransactions->isEmpty())
            <div class="p-10 text-center text-[#A1A1AA] text-xs">
                Belum ada rekaman transaksi barang masuk atau keluar.
            </div>
            @else
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left text-[#A1A1AA] tabular-nums">
                    <thead class="text-[11px] text-[#F5F5F5] uppercase tracking-wider bg-[#181C20] border-b border-[#343A40]">
                        <tr>
                            <th class="px-5 py-3 font-heading font-semibold">No. Invoice</th>
                            <th class="px-5 py-3 font-heading font-semibold">Barang & SKU</th>
                            <th class="px-5 py-3 font-heading font-semibold">Jenis</th>
                            <th class="px-5 py-3 text-right font-heading font-semibold">Jumlah</th>
                            <th class="px-5 py-3 text-center font-heading font-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#343A40]">
                        @foreach($recentTransactions as $tx)
                        <tr class="hover:bg-[#181C20] transition">
                            <td class="px-5 py-3.5 font-mono tabular-nums font-medium text-[#F5F5F5]">
                                <a href="{{ route('transactions.show', $tx->id) }}" class="hover:text-[#F5C842]">
                                    {{ $tx->invoice_number }}
                                </a>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="font-medium text-[#F5F5F5]">{{ $tx->product?->name ?? 'Produk Dihapus' }}</div>
                                <div class="text-[10px] font-mono tabular-nums text-[#A1A1AA]">SKU: {{ $tx->product?->sku ?? '-' }}</div>
                            </td>
                            <td class="px-5 py-3.5">
                                @if($tx->type === 'in')
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">
                                    + Masuk
                                </span>
                                @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-[#F5C842]/15 text-[#F5C842] border border-[#F5C842]/30">
                                    - Keluar
                                </span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-right font-bold text-sm text-[#F5F5F5] tabular-nums">
                                {{ number_format($tx->quantity) }} <span class="text-[10px] font-normal text-[#A1A1AA]">Unit</span>
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                @if($tx->status === 'confirmed')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">
                                    Selesai
                                </span>
                                @elseif($tx->status === 'pending')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/30">
                                    Menunggu
                                </span>
                                @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-red-50 text-red-700 border border-red-200">
                                    Ditolak
                                </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

        <!-- Kolom Kanan: Catatan Aktivitas Terakhir (1 Kolom) -->
        <div class="bg-[#202429] rounded-2xl border border-[#343A40] p-5 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3 border-b border-[#343A40]">
                    <div>
                        <h2 class="font-heading text-lg font-semibold text-[#F5F5F5]">Jejak Audit Aktivitas</h2>
                        <p class="text-xs text-[#A1A1AA] mt-0.5">Rekaman operasional terkini</p>
                    </div>
                    @if($user->isAdmin())
                    <a href="{{ route('logs.index') }}" class="text-xs text-[#F5C842] hover:underline font-semibold">
                        Semua Log &rarr;
                    </a>
                    @endif
                </div>

                <ul class="space-y-3.5 mt-4">
                    @forelse($recentActivities as $act)
                    <li class="flex items-start gap-3 text-xs">
                        <div class="w-2 h-2 rounded-full bg-[#F5C842] flex-shrink-0 mt-1.5"></div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between text-[#F5F5F5] font-medium">
                                <span class="text-xs font-semibold">{{ $act->user?->name ?? 'Sistem' }}</span>
                                <span class="text-[10px] text-[#A1A1AA] font-mono tabular-nums">{{ $act->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-[#A1A1AA] mt-0.5 text-[11px] leading-relaxed">{{ $act->description }}</p>
                        </div>
                    </li>
                    @empty
                    <li class="text-xs text-[#A1A1AA] text-center py-8">Belum ada catatan aktivitas baru.</li>
                    @endforelse
                </ul>
            </div>

            @if($user->isAdmin())
            <div class="pt-4 mt-4 border-t border-[#343A40] flex items-center justify-between text-xs text-[#A1A1AA]">
                <span>Total Pengguna: <strong class="text-[#F5F5F5] font-mono">{{ $totalUsers }}</strong></span>
                <a href="{{ route('users.index') }}" class="text-[#F5C842] hover:underline font-medium">Kelola Akun &rarr;</a>
            </div>
            @endif
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const initCharts = () => {
        if (typeof ApexCharts === 'undefined') {
            console.warn('ApexCharts belum terdefinisi, menunggu...');
            setTimeout(initCharts, 200);
            return;
        }

        // 1. Chart Tren Mutasi 7 Hari Terakhir
        const mutationEl = document.querySelector("#mutation-trend-chart");
        if (mutationEl) {
            const dates = JSON.parse(mutationEl.dataset.dates || '[]');
            const inSeries = JSON.parse(mutationEl.dataset.in || '[]');
            const outSeries = JSON.parse(mutationEl.dataset.out || '[]');

            const mutationTrendOptions = {
                series: [
                    { name: 'Barang Masuk', data: inSeries },
                    { name: 'Barang Keluar', data: outSeries }
                ],
                chart: {
                    type: 'area',
                    height: 280,
                    toolbar: { show: false },
                    fontFamily: 'IBM Plex Sans, sans-serif'
                },
                colors: ['#22C55E', '#F5C842'],
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 2 },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.35,
                        opacityTo: 0.05,
                        stops: [0, 95, 100]
                    }
                },
                xaxis: {
                    categories: dates,
                    labels: {
                        style: { colors: '#A1A1AA', fontSize: '11px', fontFamily: 'IBM Plex Sans' }
                    },
                    axisBorder: { color: '#343A40' },
                    axisTicks: { color: '#343A40' }
                },
                yaxis: {
                    labels: {
                        style: { colors: '#A1A1AA', fontSize: '11px', fontFamily: 'IBM Plex Sans' },
                        formatter: function(val) { return Math.round(val); }
                    }
                },
                grid: {
                    borderColor: '#343A40',
                    strokeDashArray: 4
                },
                legend: {
                    show: false
                },
                tooltip: {
                    theme: 'dark',
                    y: {
                        formatter: function (val) { return val + " Unit"; }
                    }
                }
            };

            new ApexCharts(mutationEl, mutationTrendOptions).render();
        }

        // 2. Chart Komposisi Kategori
        const catEl = document.querySelector("#category-distribution-chart");
        if (catEl) {
            const catLabels = JSON.parse(catEl.dataset.labels || '[]');
            const catSeries = JSON.parse(catEl.dataset.series || '[]');

            if (catSeries.length > 0 && catSeries.some(v => v > 0)) {
                const categoryOptions = {
                    series: catSeries,
                    labels: catLabels,
                    chart: {
                        type: 'donut',
                        height: 280,
                        fontFamily: 'IBM Plex Sans, sans-serif'
                    },
                    colors: ['#F5C842', '#22C55E', '#38BDF8', '#FB923C', '#A78BFA', '#94A3B8'],
                    dataLabels: { enabled: false },
                    legend: {
                        position: 'bottom',
                        labels: { colors: '#A1A1AA' },
                        fontSize: '11px',
                        fontFamily: 'IBM Plex Sans'
                    },
                    stroke: { width: 2, colors: ['#202429'] },
                    tooltip: {
                        theme: 'dark',
                        y: {
                            formatter: function(val) { return val.toLocaleString('id-ID') + " Unit"; }
                        }
                    }
                };

                new ApexCharts(catEl, categoryOptions).render();
            }
        }
    };

    initCharts();
});
</script>
@endpush