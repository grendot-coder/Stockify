@extends('layouts.dashboard')

@section('content')
<div class="space-y-6">

    <!-- Header & Tombol Aksi Transaksi -->
    <div class="bg-[#202429] rounded-2xl border border-[#343A40] p-6 shadow-xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="w-2 h-2 rounded-full bg-[#F5C842]"></span>
                <span class="text-[11px] uppercase tracking-[0.2em] text-[#F5C842] font-semibold">Alur Persediaan</span>
            </div>
            <h1 class="font-heading text-2xl sm:text-3xl font-semibold text-[#F5F5F5] tracking-tight">Manajemen Transaksi Stok</h1>
            <p class="text-xs text-[#A1A1AA] mt-1">Catat dan pantau seluruh mutasi barang masuk (Inbound) dan barang keluar (Outbound)</p>
        </div>
        <div class="flex items-center gap-2.5">
            <a href="{{ route('transactions.inbound') }}"
                class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-[#F5C842] hover:bg-[#E8B92E] text-[#101214] text-xs font-bold uppercase tracking-wider rounded-xl shadow-xs transition cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>+ Barang Masuk</span>
            </a>
            <a href="{{ route('transactions.outbound') }}"
                class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-[#181C20] hover:bg-[#F5C842]/15 border border-[#343A40] hover:border-[#F5C842] text-[#F5F5F5] text-xs font-semibold rounded-xl transition cursor-pointer">
                <svg class="w-4 h-4 text-[#F5C842]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                </svg>
                <span>- Barang Keluar</span>
            </a>
        </div>
    </div>

    <!-- Alert Notifikasi -->
    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error') || $errors->any())
        <div class="p-4 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs flex items-center gap-2 shadow-xs">
            <svg class="w-4 h-4 text-rose-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            <span>{{ session('error') ?? $errors->first() }}</span>
        </div>
    @endif

    <!-- Mini Ringkasan Metrik Transaksi -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5">
        <div class="bg-[#202429] rounded-2xl border border-[#343A40] p-4 shadow-xs">
            <div class="text-[10px] font-semibold text-[#A1A1AA] uppercase tracking-wider">Total Mutasi</div>
            <div class="font-heading text-2xl font-semibold tabular-nums text-[#F5F5F5] mt-1">{{ number_format($stats['total']) }}</div>
            <div class="text-[10px] text-[#A1A1AA] mt-0.5">Semua riwayat transaksi</div>
        </div>
        <div class="bg-[#202429] rounded-2xl border border-[#343A40] p-4 shadow-xs">
            <div class="text-[10px] font-semibold text-[#F5C842] uppercase tracking-wider">Barang Masuk</div>
            <div class="font-heading text-2xl font-semibold tabular-nums text-[#F5C842] mt-1">{{ number_format($stats['inbound_count']) }}</div>
            <div class="text-[10px] text-[#A1A1AA] mt-0.5">Transaksi Inbound sukses</div>
        </div>
        <div class="bg-[#202429] rounded-2xl border border-[#343A40] p-4 shadow-xs">
            <div class="text-[10px] font-semibold text-[#F5F5F5] uppercase tracking-wider">Barang Keluar</div>
            <div class="font-heading text-2xl font-semibold tabular-nums text-[#F5F5F5] mt-1">{{ number_format($stats['outbound_count']) }}</div>
            <div class="text-[10px] text-[#A1A1AA] mt-0.5">Transaksi Outbound sukses</div>
        </div>
        <div class="bg-[#202429] rounded-2xl border border-[#343A40] p-4 shadow-xs">
            <div class="text-[10px] font-semibold text-[#A1A1AA] uppercase tracking-wider">Menunggu Persetujuan</div>
            <div class="font-heading text-2xl font-semibold tabular-nums text-[#F5C842] mt-1">{{ number_format($stats['pending_count']) }}</div>
            <div class="text-[10px] text-[#A1A1AA] mt-0.5">Butuh konfirmasi manajer</div>
        </div>
    </div>

    <!-- Card Tabel & Filter Pencarian -->
    <div class="bg-[#202429] rounded-2xl border border-[#343A40] shadow-xs overflow-hidden">
        
        <!-- Filter Bar -->
        <div class="p-4 sm:p-5 border-b border-[#343A40] bg-[#181C20]">
            <form action="{{ route('transactions.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-center">
                <!-- Search -->
                <div class="lg:col-span-2">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari No. Invoice, nama barang, supplier..."
                        class="w-full px-3.5 py-2 text-xs bg-[#202429] border border-[#343A40] rounded-xl text-[#F5F5F5] placeholder-[#A1A1AA]/60 focus:outline-none focus:border-[#F5C842]">
                </div>

                <!-- Filter Tipe -->
                <div>
                    <select name="type" onchange="this.form.submit()"
                        class="w-full px-3 py-2 text-xs bg-[#202429] border border-[#343A40] rounded-xl text-[#F5F5F5] focus:outline-none focus:border-[#F5C842]">
                        <option value="">Semua Tipe (In & Out)</option>
                        <option value="in" {{ $type === 'in' ? 'selected' : '' }}>Hanya Barang Masuk (In)</option>
                        <option value="out" {{ $type === 'out' ? 'selected' : '' }}>Hanya Barang Keluar (Out)</option>
                    </select>
                </div>

                <!-- Filter Status -->
                <div>
                    <select name="status" onchange="this.form.submit()"
                        class="w-full px-3 py-2 text-xs bg-[#202429] border border-[#343A40] rounded-xl text-[#F5F5F5] focus:outline-none focus:border-[#F5C842]">
                        <option value="">Semua Status</option>
                        <option value="confirmed" {{ $status === 'confirmed' ? 'selected' : '' }}>Disetujui (Confirmed)</option>
                        <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Menunggu (Pending)</option>
                        <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Ditolak (Rejected)</option>
                    </select>
                </div>

                <!-- Filter Produk & Reset -->
                <div class="flex items-center gap-2">
                    <select name="product_id" onchange="this.form.submit()"
                        class="w-full px-3 py-2 text-xs bg-[#202429] border border-[#343A40] rounded-xl text-[#F5F5F5] focus:outline-none focus:border-[#F5C842]">
                        <option value="">Semua Produk</option>
                        @foreach($products as $prd)
                            <option value="{{ $prd->id }}" {{ $productId == $prd->id ? 'selected' : '' }}>
                                {{ Str::limit($prd->name, 25) }}
                            </option>
                        @endforeach
                    </select>
                    @if($search || $type || $status || $productId)
                        <a href="{{ route('transactions.index') }}" class="text-xs text-[#A1A1AA] hover:text-[#F5C842] whitespace-nowrap underline">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Tabel Transaksi -->
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left text-[#A1A1AA] tabular-nums">
                <thead class="text-[11px] text-[#F5F5F5] uppercase tracking-wider bg-[#181C20] border-b border-[#343A40]">
                    <tr>
                        <th class="px-5 py-3.5 font-heading font-semibold">No. Invoice & Waktu</th>
                        <th class="px-5 py-3.5 font-heading font-semibold">Tipe</th>
                        <th class="px-5 py-3.5 font-heading font-semibold">Produk / Barang</th>
                        <th class="px-5 py-3.5 text-right font-heading font-semibold">Kuantitas</th>
                        <th class="px-5 py-3.5 font-heading font-semibold">Supplier / Keterangan</th>
                        <th class="px-5 py-3.5 text-center font-heading font-semibold">Status</th>
                        <th class="px-5 py-3.5 text-right font-heading font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#343A40]">
                    @forelse($transactions as $trx)
                        <tr class="hover:bg-[#181C20] transition">
                            <!-- Invoice & Waktu -->
                            <td class="px-5 py-3.5">
                                <a href="{{ route('transactions.show', $trx->id) }}" class="font-mono font-medium text-[#F5F5F5] hover:text-[#F5C842]">
                                    {{ $trx->invoice_number }}
                                </a>
                                <div class="text-[10px] text-[#A1A1AA] mt-0.5 font-mono">
                                    {{ $trx->created_at->format('d M Y, H:i') }}
                                </div>
                            </td>

                            <!-- Tipe -->
                            <td class="px-5 py-3.5">
                                @if($trx->type === 'in')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded text-[10px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">
                                        + Masuk
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded text-[10px] font-semibold bg-[#F5C842]/15 text-[#F5C842] border border-[#F5C842]/30">
                                        - Keluar
                                    </span>
                                @endif
                            </td>

                            <!-- Produk -->
                            <td class="px-5 py-3.5">
                                <div class="font-medium text-[#F5F5F5]">
                                    <a href="{{ route('products.show', $trx->product_id) }}" class="hover:text-[#F5C842]">
                                        {{ $trx->product?->name ?? 'Produk Dihapus' }}
                                    </a>
                                </div>
                                <div class="text-[10px] text-[#A1A1AA] font-mono mt-0.5">
                                    SKU: {{ $trx->product?->sku ?? '-' }}
                                </div>
                            </td>

                            <!-- Kuantitas -->
                            <td class="px-5 py-3.5 text-right">
                                <span class="font-bold text-sm {{ $trx->type === 'in' ? 'text-emerald-700' : 'text-[#F5C842]' }}">
                                    {{ $trx->type === 'in' ? '+' : '-' }}{{ number_format($trx->quantity) }}
                                </span>
                                <span class="text-[10px] text-[#A1A1AA] block">Unit</span>
                            </td>

                            <!-- Supplier / Keterangan -->
                            <td class="px-5 py-3.5 max-w-xs">
                                @if($trx->supplier)
                                    <div class="font-medium text-[#F5F5F5]">{{ $trx->supplier->name }}</div>
                                @endif
                                <div class="text-[11px] text-[#A1A1AA] truncate" title="{{ $trx->notes }}">
                                    {{ $trx->notes ?: '-' }}
                                </div>
                                <div class="text-[10px] text-[#A1A1AA] mt-0.5">
                                    Oleh: {{ $trx->user?->name ?? 'Sistem' }}
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="px-5 py-3.5 text-center">
                                @if($trx->status === 'confirmed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">
                                        Disetujui
                                    </span>
                                @elseif($trx->status === 'pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/30">
                                        Menunggu
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-rose-50 text-rose-800 border border-rose-200">
                                        Ditolak
                                    </span>
                                @endif
                            </td>

                            <!-- Aksi -->
                            <td class="px-5 py-3.5 text-right space-x-1.5 whitespace-nowrap">
                                <a href="{{ route('transactions.show', $trx->id) }}" class="text-[#A1A1AA] hover:text-[#F5C842] font-medium">
                                    Rincian
                                </a>
                                @if($trx->status === 'pending' && in_array(Auth::user()->role, ['admin', 'manager']))
                                    <form action="{{ route('transactions.confirm', $trx->id) }}" method="POST" class="inline-block"
                                        onsubmit="return confirm('Setujui transaksi ini? Stok barang akan disesuaikan otomatis.');">
                                        @csrf
                                        <button type="submit" class="text-[#F5C842] hover:text-[#E8B92E] font-semibold ml-1 cursor-pointer">
                                            Setujui
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-[#A1A1AA]">
                                Belum ada data transaksi stok yang sesuai kriteria pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transactions->hasPages())
            <div class="p-4 border-t border-[#343A40] bg-[#181C20]">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
