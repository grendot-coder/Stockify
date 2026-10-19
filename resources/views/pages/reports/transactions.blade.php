@extends('layouts.dashboard')

@section('content')
<div class="space-y-6">

    <!-- Header Laporan Mutasi Transaksi -->
    <div class="bg-[#FFFFFF] rounded-2xl border border-[#E7E0DA] p-6 shadow-xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="w-2 h-2 rounded-full bg-[#BD6745]"></span>
                <span class="text-[11px] uppercase tracking-[0.2em] text-[#BD6745] font-semibold">Pelaporan & Audit Mutasi</span>
            </div>
            <h1 class="font-heading text-2xl sm:text-3xl font-semibold text-[#292524] tracking-tight">
                Laporan Riwayat Mutasi Barang
            </h1>
            <p class="text-xs text-[#78716C] mt-1">
                Rekapitulasi log mutasi arus barang masuk (*inbound*) dan barang keluar (*outbound*) beserta status verifikasi.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <!-- Unduh CSV -->
            <a href="{{ request()->fullUrlWithQuery(['export' => 'csv']) }}" 
               class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-[#292524] bg-[#FFFFFF] hover:bg-[#FAF7F4] rounded-xl border border-[#E7E0DA] transition shadow-xs">
                <svg class="w-4 h-4 text-[#059669]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Unduh CSV / Excel
            </a>
            <!-- Cetak PDF -->
            <a href="{{ request()->fullUrlWithQuery(['export' => 'print']) }}" target="_blank"
               class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-[#1C1917] bg-[#BD6745] hover:bg-[#a65434] rounded-xl transition shadow-xs text-white">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Cetak / Simpan PDF
            </a>
        </div>
    </div>

    <!-- Ringkasan Agregat Laporan (3 Cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="p-5 bg-[#FFFFFF] rounded-2xl border border-[#E7E0DA] shadow-xs">
            <span class="text-xs font-semibold text-[#78716C] uppercase tracking-wider">Total Rekaman Transaksi</span>
            <div class="font-heading text-3xl font-semibold tabular-nums text-[#292524] mt-2">
                {{ number_format($totalTransactions) }} <span class="font-sans text-xs font-normal text-[#78716C]">Mutasi</span>
            </div>
            <p class="text-[11px] text-[#78716C] mt-1">Sesuai rentang waktu & filter terpilih</p>
        </div>

        <div class="p-5 bg-[#FFFFFF] rounded-2xl border border-[#E7E0DA] shadow-xs">
            <span class="text-xs font-semibold text-[#78716C] uppercase tracking-wider">Total Barang Masuk (Confirmed)</span>
            <div class="font-heading text-3xl font-semibold tabular-nums text-emerald-700 mt-2">
                +{{ number_format($inboundQty) }} <span class="font-sans text-xs font-normal text-[#78716C]">Unit</span>
            </div>
            <p class="text-[11px] text-[#78716C] mt-1">Akumulasi penerimaan terverifikasi</p>
        </div>

        <div class="p-5 bg-[#FFFFFF] rounded-2xl border border-[#E7E0DA] shadow-xs">
            <span class="text-xs font-semibold text-[#78716C] uppercase tracking-wider">Total Barang Keluar (Confirmed)</span>
            <div class="font-heading text-3xl font-semibold tabular-nums text-[#BD6745] mt-2">
                -{{ number_format($outboundQty) }} <span class="font-sans text-xs font-normal text-[#78716C]">Unit</span>
            </div>
            <p class="text-[11px] text-[#78716C] mt-1">Akumulasi pengeluaran terverifikasi</p>
        </div>
    </div>

    <!-- Panel Filter & Pencarian Rentang Tanggal -->
    <div class="bg-[#FFFFFF] rounded-2xl border border-[#E7E0DA] p-5 shadow-xs">
        <form action="{{ route('reports.transactions') }}" method="GET" class="space-y-4">
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3 items-end">
                <!-- Dari Tanggal -->
                <div>
                    <label for="date_from" class="block text-xs font-semibold text-[#292524] mb-1">Dari Tanggal</label>
                    <input type="date" name="date_from" id="date_from" value="{{ $dateFrom }}"
                           class="w-full text-xs px-3 py-2 rounded-xl border border-[#E7E0DA] bg-[#FAF7F4] focus:bg-white focus:outline-none focus:border-[#BD6745] text-[#292524]">
                </div>

                <!-- Sampai Tanggal -->
                <div>
                    <label for="date_to" class="block text-xs font-semibold text-[#292524] mb-1">Sampai Tanggal</label>
                    <input type="date" name="date_to" id="date_to" value="{{ $dateTo }}"
                           class="w-full text-xs px-3 py-2 rounded-xl border border-[#E7E0DA] bg-[#FAF7F4] focus:bg-white focus:outline-none focus:border-[#BD6745] text-[#292524]">
                </div>

                <!-- Jenis Mutasi -->
                <div>
                    <label for="type" class="block text-xs font-semibold text-[#292524] mb-1">Jenis Mutasi</label>
                    <select name="type" id="type" class="w-full text-xs px-3 py-2 rounded-xl border border-[#E7E0DA] bg-[#FAF7F4] focus:bg-white focus:outline-none focus:border-[#BD6745] text-[#292524]">
                        <option value="">Semua Jenis</option>
                        <option value="in" {{ $type === 'in' ? 'selected' : '' }}>Barang Masuk (Inbound)</option>
                        <option value="out" {{ $type === 'out' ? 'selected' : '' }}>Barang Keluar (Outbound)</option>
                    </select>
                </div>

                <!-- Status Transaksi -->
                <div>
                    <label for="status" class="block text-xs font-semibold text-[#292524] mb-1">Status Verifikasi</label>
                    <select name="status" id="status" class="w-full text-xs px-3 py-2 rounded-xl border border-[#E7E0DA] bg-[#FAF7F4] focus:bg-white focus:outline-none focus:border-[#BD6745] text-[#292524]">
                        <option value="">Semua Status</option>
                        <option value="confirmed" {{ $status === 'confirmed' ? 'selected' : '' }}>Selesai / Terkonfirmasi</option>
                        <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Menunggu Otorisasi</option>
                        <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>

                <!-- Filter Produk Tertentu -->
                <div>
                    <label for="product_id" class="block text-xs font-semibold text-[#292524] mb-1">Filter Produk</label>
                    <select name="product_id" id="product_id" class="w-full text-xs px-3 py-2 rounded-xl border border-[#E7E0DA] bg-[#FAF7F4] focus:bg-white focus:outline-none focus:border-[#BD6745] text-[#292524]">
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
                    <button type="submit" class="flex-1 py-2 px-3 text-xs font-semibold text-white bg-[#BD6745] hover:bg-[#a65434] rounded-xl transition shadow-xs">
                        Filter
                    </button>
                    <a href="{{ route('reports.transactions') }}" class="py-2 px-3 text-xs font-semibold text-[#78716C] bg-[#FAF7F4] hover:bg-[#E7E0DA] rounded-xl border border-[#E7E0DA] transition text-center">
                        Reset
                    </a>
                </div>
            </div>

            <!-- Baris Pencarian Kata Kunci Invoice / Catatan -->
            <div class="pt-2 border-t border-[#E7E0DA]/60">
                <input type="text" name="search" value="{{ $search }}" placeholder="Pencarian cepat berdasarkan nomor invoice, catatan transaksi, atau SKU..."
                       class="w-full text-xs px-3.5 py-2 rounded-xl border border-[#E7E0DA] bg-[#FAF7F4] focus:bg-white focus:outline-none focus:border-[#BD6745] text-[#292524] placeholder-[#78716C]">
            </div>

        </form>
    </div>

    <!-- Tabel Data Laporan Mutasi -->
    <div class="bg-[#FFFFFF] rounded-2xl border border-[#E7E0DA] overflow-hidden shadow-xs">
        <div class="p-4 border-b border-[#E7E0DA] flex items-center justify-between text-xs text-[#78716C]">
            <span>Menampilkan catatan pergerakan stok barang</span>
            <span class="font-mono tabular-nums">Total: {{ $transactions->total() }} Mutasi</span>
        </div>

        @if($transactions->isEmpty())
            <div class="p-12 text-center text-xs text-[#78716C]">
                Tidak ada data riwayat transaksi mutasi yang cocok dengan kriteria filter.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left text-[#78716C] tabular-nums">
                    <thead class="text-[11px] text-[#292524] uppercase tracking-wider bg-[#FAF7F4] border-b border-[#E7E0DA]">
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
                    <tbody class="divide-y divide-[#E7E0DA]">
                        @foreach($transactions as $tx)
                        <tr class="hover:bg-[#FAF7F4] transition">
                            <td class="px-5 py-3.5 font-mono tabular-nums font-semibold text-[#292524]">
                                <a href="{{ route('transactions.show', $tx->id) }}" class="hover:text-[#BD6745]">
                                    {{ $tx->invoice_number }}
                                </a>
                            </td>
                            <td class="px-5 py-3.5 font-mono text-[#78716C] whitespace-nowrap">
                                <div>{{ $tx->created_at->format('d/m/Y') }}</div>
                                <div class="text-[10px]">{{ $tx->created_at->format('H:i') }} WIB</div>
                            </td>
                            <td class="px-5 py-3.5">
                                @if($tx->type === 'in')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        + Masuk
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-[#F5E6DE] text-[#BD6745] border border-[#BD6745]/30">
                                        - Keluar
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="font-medium text-[#292524]">{{ $tx->product?->name ?? 'Produk Dihapus' }}</div>
                                <div class="text-[10px] font-mono text-[#78716C]">SKU: {{ $tx->product?->sku ?? '-' }}</div>
                            </td>
                            <td class="px-5 py-3.5 text-right font-bold text-sm text-[#292524]">
                                {{ number_format($tx->quantity) }} <span class="text-[10px] font-normal text-[#78716C]">Unit</span>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="text-[#292524] font-medium">{{ $tx->user?->name ?? 'Staff' }}</div>
                                @if($tx->confirmedByUser)
                                    <div class="text-[10px] text-emerald-700">Verifikator: {{ $tx->confirmedByUser->name }}</div>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                @if($tx->status === 'confirmed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Selesai
                                    </span>
                                @elseif($tx->status === 'pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                        Menunggu
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-red-50 text-red-700 border border-red-200">
                                        Ditolak
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-[#78716C] max-w-xs truncate text-[11px]">
                                {{ $tx->notes ?: '-' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="p-4 border-t border-[#E7E0DA]">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
