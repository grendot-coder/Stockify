@extends('layouts.dashboard')

@section('content')
<div class="space-y-6">

    <!-- Header Laporan Mutasi Transaksi -->
    <div class="bg-[#202429] rounded-2xl border border-[#343A40] p-6 shadow-xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="w-2 h-2 rounded-full bg-[#F5C842]"></span>
                <span class="text-[11px] uppercase tracking-[0.2em] text-[#F5C842] font-semibold">Pelaporan & Audit Mutasi</span>
            </div>
            <h1 class="font-heading text-2xl sm:text-3xl font-semibold text-[#F5F5F5] tracking-tight">
                Laporan Riwayat Mutasi Barang
            </h1>
            <p class="text-xs text-[#A1A1AA] mt-1">
                Rekapitulasi log mutasi arus barang masuk (*inbound*) dan barang keluar (*outbound*) beserta status verifikasi.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <!-- Unduh CSV -->
            <a href="{{ request()->fullUrlWithQuery(['export' => 'csv']) }}" 
               class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-[#F5F5F5] bg-[#202429] hover:bg-[#181C20] rounded-xl border border-[#343A40] transition shadow-xs">
                <svg class="w-4 h-4 text-[#059669]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Unduh CSV / Excel
            </a>
            <!-- Cetak PDF -->
            <a href="{{ request()->fullUrlWithQuery(['export' => 'print']) }}" target="_blank"
               class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-[#1C1917] bg-[#F5C842] hover:bg-[#a65434] rounded-xl transition shadow-xs text-white">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Cetak / Simpan PDF
            </a>
        </div>
    </div>

    <!-- Ringkasan Agregat Laporan (3 Cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="p-5 bg-[#202429] rounded-2xl border border-[#343A40] shadow-xs">
            <span class="text-xs font-semibold text-[#A1A1AA] uppercase tracking-wider">Total Rekaman Transaksi</span>
            <div class="font-heading text-3xl font-semibold tabular-nums text-[#F5F5F5] mt-2">
                {{ number_format($totalTransactions) }} <span class="font-sans text-xs font-normal text-[#A1A1AA]">Mutasi</span>
            </div>
            <p class="text-[11px] text-[#A1A1AA] mt-1">Sesuai rentang waktu & filter terpilih</p>
        </div>

        <div class="p-5 bg-[#202429] rounded-2xl border border-[#343A40] shadow-xs">
            <span class="text-xs font-semibold text-[#A1A1AA] uppercase tracking-wider">Total Barang Masuk (Confirmed)</span>
            <div class="font-heading text-3xl font-semibold tabular-nums text-emerald-400 mt-2">
                +{{ number_format($inboundQty) }} <span class="font-sans text-xs font-normal text-[#A1A1AA]">Unit</span>
            </div>
            <p class="text-[11px] text-[#A1A1AA] mt-1">Akumulasi penerimaan terverifikasi</p>
        </div>

        <div class="p-5 bg-[#202429] rounded-2xl border border-[#343A40] shadow-xs">
            <span class="text-xs font-semibold text-[#A1A1AA] uppercase tracking-wider">Total Barang Keluar (Confirmed)</span>
            <div class="font-heading text-3xl font-semibold tabular-nums text-[#F5C842] mt-2">
                -{{ number_format($outboundQty) }} <span class="font-sans text-xs font-normal text-[#A1A1AA]">Unit</span>
            </div>
            <p class="text-[11px] text-[#A1A1AA] mt-1">Akumulasi pengeluaran terverifikasi</p>
        </div>
    </div>

    <!-- Panel Filter & Pencarian Rentang Tanggal -->
    <div class="bg-[#202429] rounded-2xl border border-[#343A40] p-5 shadow-xs">
        <form action="{{ route('reports.transactions') }}" method="GET" class="space-y-4">
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3 items-end">
                <!-- Dari Tanggal -->
                <div>
                    <label for="date_from" class="block text-xs font-semibold text-[#F5F5F5] mb-1">Dari Tanggal</label>
                    <input type="date" name="date_from" id="date_from" value="{{ $dateFrom }}"
                           class="w-full text-xs px-3 py-2 rounded-xl border border-[#343A40] bg-[#181C20] focus:bg-[#202429] focus:outline-none focus:border-[#F5C842] text-[#F5F5F5]">
                </div>

                <!-- Sampai Tanggal -->
                <div>
                    <label for="date_to" class="block text-xs font-semibold text-[#F5F5F5] mb-1">Sampai Tanggal</label>
                    <input type="date" name="date_to" id="date_to" value="{{ $dateTo }}"
                           class="w-full text-xs px-3 py-2 rounded-xl border border-[#343A40] bg-[#181C20] focus:bg-[#202429] focus:outline-none focus:border-[#F5C842] text-[#F5F5F5]">
                </div>

                <!-- Jenis Mutasi -->
                <div>
                    <label for="type" class="block text-xs font-semibold text-[#F5F5F5] mb-1">Jenis Mutasi</label>
                    <select name="type" id="type" class="w-full text-xs px-3 py-2 rounded-xl border border-[#343A40] bg-[#181C20] focus:bg-[#202429] focus:outline-none focus:border-[#F5C842] text-[#F5F5F5]">
                        <option value="">Semua Jenis</option>
                        <option value="in" {{ $type === 'in' ? 'selected' : '' }}>Barang Masuk (Inbound)</option>
                        <option value="out" {{ $type === 'out' ? 'selected' : '' }}>Barang Keluar (Outbound)</option>
                    </select>
                </div>

                <!-- Status Transaksi -->
                <div>
                    <label for="status" class="block text-xs font-semibold text-[#F5F5F5] mb-1">Status Verifikasi</label>
                    <select name="status" id="status" class="w-full text-xs px-3 py-2 rounded-xl border border-[#343A40] bg-[#181C20] focus:bg-[#202429] focus:outline-none focus:border-[#F5C842] text-[#F5F5F5]">
                        <option value="">Semua Status</option>
                        <option value="confirmed" {{ $status === 'confirmed' ? 'selected' : '' }}>Selesai / Terkonfirmasi</option>
                        <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Menunggu Otorisasi</option>
                        <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>

                <!-- Filter Produk Tertentu -->
                <div>
                    <label for="product_id" class="block text-xs font-semibold text-[#F5F5F5] mb-1">Filter Produk</label>
                    <select name="product_id" id="product_id" class="w-full text-xs px-3 py-2 rounded-xl border border-[#343A40] bg-[#181C20] focus:bg-[#202429] focus:outline-none focus:border-[#F5C842] text-[#F5F5F5]">
                        <option value="">Semua Produk</option>
                        @foreach($products as $prod)
                            <option value="{{ $prod->id }}" {{ $productId == $prod->id ? 'selected' : '' }}>
                                {{ $prod->name }} ({{ $prod->sku }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tombol Aksi -->
                <div class="flex items-center gap-2">
                    <button type="submit" class="flex-1 py-2 px-3 text-xs font-semibold text-[#101214] bg-[#F5C842] hover:bg-[#E8B92E] rounded-xl transition shadow-xs">
                        Filter
                    </button>
                    <a href="{{ route('reports.transactions') }}" class="py-2 px-3 text-xs font-semibold text-[#A1A1AA] bg-[#181C20] hover:bg-[#2A3036] rounded-xl border border-[#343A40] transition text-center">
                        Reset
                    </a>
                </div>
            </div>

            <!-- Baris Pencarian Kata Kunci Invoice / Catatan -->
            <div class="pt-2 border-t border-[#343A40]/60">
                <input type="text" name="search" value="{{ $search }}" placeholder="Pencarian cepat berdasarkan nomor invoice, catatan transaksi, atau SKU..."
                       class="w-full text-xs px-3.5 py-2 rounded-xl border border-[#343A40] bg-[#181C20] focus:bg-[#202429] focus:outline-none focus:border-[#F5C842] text-[#F5F5F5] placeholder-[#A1A1AA]">
            </div>

        </form>
    </div>

    <!-- Tabel Data Laporan Mutasi -->
    <div class="bg-[#202429] rounded-2xl border border-[#343A40] overflow-hidden shadow-xs">
        <div class="p-4 border-b border-[#343A40] flex items-center justify-between text-xs text-[#A1A1AA]">
            <span>Menampilkan catatan pergerakan stok barang</span>
            <span class="font-mono tabular-nums">Total: {{ $transactions->total() }} Mutasi</span>
        </div>

        @if($transactions->isEmpty())
            <div class="p-12 text-center text-xs text-[#A1A1AA]">
                Tidak ada data riwayat transaksi mutasi yang cocok dengan kriteria filter.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left text-[#A1A1AA] tabular-nums">
                    <thead class="text-[11px] text-[#F5F5F5] uppercase tracking-wider bg-[#181C20] border-b border-[#343A40]">
                        <tr>
                            <th class="px-5 py-3 font-heading font-semibold">No. Invoice</th>
                            <th class="px-5 py-3 font-heading font-semibold">Waktu Transaksi</th>
                            <th class="px-5 py-3 font-heading font-semibold">Jenis</th>
                            <th class="px-5 py-3 font-heading font-semibold">Produk & SKU</th>
                            <th class="px-5 py-3 text-right font-heading font-semibold">Jumlah</th>
                            <th class="px-5 py-3 font-heading font-semibold">Operator & Otorisator</th>
                            <th class="px-5 py-3 text-center font-heading font-semibold">Status</th>
                            <th class="px-5 py-3 font-heading font-semibold">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#343A40]">
                        @foreach($transactions as $tx)
                        <tr class="hover:bg-[#181C20] transition">
                            <td class="px-5 py-3.5 font-mono tabular-nums font-semibold text-[#F5F5F5]">
                                <a href="{{ route('transactions.show', $tx->id) }}" class="hover:text-[#F5C842]">
                                    {{ $tx->invoice_number }}
                                </a>
                            </td>
                            <td class="px-5 py-3.5 font-mono text-[#A1A1AA] whitespace-nowrap">
                                <div>{{ $tx->created_at->format('d/m/Y') }}</div>
                                <div class="text-[10px]">{{ $tx->created_at->format('H:i') }} WIB</div>
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
                            <td class="px-5 py-3.5">
                                <div class="font-medium text-[#F5F5F5]">{{ $tx->product?->name ?? 'Produk Dihapus' }}</div>
                                <div class="text-[10px] font-mono text-[#A1A1AA]">SKU: {{ $tx->product?->sku ?? '-' }}</div>
                            </td>
                            <td class="px-5 py-3.5 text-right font-bold text-sm text-[#F5F5F5]">
                                {{ number_format($tx->quantity) }} <span class="text-[10px] font-normal text-[#A1A1AA]">Unit</span>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="text-[#F5F5F5] font-medium">{{ $tx->user?->name ?? 'Staff' }}</div>
                                @if($tx->confirmedByUser)
                                    <div class="text-[10px] text-emerald-400">Verifikator: {{ $tx->confirmedByUser->name }}</div>
                                @endif
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
                            <td class="px-5 py-3.5 text-[#A1A1AA] max-w-xs truncate text-[11px]">
                                {{ $tx->notes ?: '-' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="p-4 border-t border-[#343A40]">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
