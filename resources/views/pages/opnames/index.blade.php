@extends('layouts.dashboard')

@section('content')
<div class="space-y-6">

    <!-- Header & Tombol Mulai Opname -->
    <div class="bg-[#FFFFFF] rounded-2xl border border-[#E7E0DA] p-6 shadow-xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="w-2 h-2 rounded-full bg-[#BD6745]"></span>
                <span class="text-[11px] uppercase tracking-[0.2em] text-[#BD6745] font-semibold">Audit Fisik Persediaan</span>
            </div>
            <h1 class="font-heading text-2xl sm:text-3xl font-semibold text-[#292524] tracking-tight">Stock Opname Gudang</h1>
            <p class="text-xs text-[#78716C] mt-1">Pemeriksaan fisik stok aktual, kalkulasi selisih persediaan, dan rekonsiliasi sistem</p>
        </div>
        <div>
            <a href="{{ route('opnames.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#BD6745] hover:bg-[#A55636] text-[#1C1917] text-xs font-bold uppercase tracking-wider rounded-xl shadow-xs transition cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>+ Input Perhitungan Fisik</span>
            </a>
        </div>
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

    @if(session('error') || $errors->any())
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-center gap-2 shadow-xs">
            <svg class="w-4 h-4 text-rose-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            <span>{{ session('error') ?? $errors->first() }}</span>
        </div>
    @endif

    <!-- 4 Kotak Ringkasan Metrik Opname -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5">
        <div class="bg-[#FFFFFF] rounded-2xl border border-[#E7E0DA] p-4 shadow-xs">
            <div class="text-[10px] font-semibold text-[#78716C] uppercase tracking-wider">Total Sesi Opname</div>
            <div class="font-heading text-2xl font-semibold tabular-nums text-[#292524] mt-1">{{ number_format($stats['total']) }}</div>
            <div class="text-[10px] text-[#78716C] mt-0.5">Seluruh rekaman audit</div>
        </div>
        <div class="bg-[#FFFFFF] rounded-2xl border border-[#E7E0DA] p-4 shadow-xs">
            <div class="text-[10px] font-semibold text-emerald-700 uppercase tracking-wider">Stok Sesuai (0 Selisih)</div>
            <div class="font-heading text-2xl font-semibold tabular-nums text-emerald-700 mt-1">{{ number_format($stats['matched']) }}</div>
            <div class="text-[10px] text-[#78716C] mt-0.5">Fisik cocok dengan sistem</div>
        </div>
        <div class="bg-[#FFFFFF] rounded-2xl border border-[#E7E0DA] p-4 shadow-xs">
            <div class="text-[10px] font-semibold text-rose-700 uppercase tracking-wider">Selisih Defisit (Kurang)</div>
            <div class="font-heading text-2xl font-semibold tabular-nums text-rose-700 mt-1">{{ number_format($stats['deficit']) }}</div>
            <div class="text-[10px] text-[#78716C] mt-0.5">Potensi hilang / rusak</div>
        </div>
        <div class="bg-[#FFFFFF] rounded-2xl border border-[#E7E0DA] p-4 shadow-xs">
            <div class="text-[10px] font-semibold text-amber-700 uppercase tracking-wider">Selisih Surplus (Lebih)</div>
            <div class="font-heading text-2xl font-semibold tabular-nums text-amber-700 mt-1">{{ number_format($stats['surplus']) }}</div>
            <div class="text-[10px] text-[#78716C] mt-0.5">Belum tercatat masuk</div>
        </div>
    </div>

    <!-- Filter Bar & Card Tabel -->
    <div class="bg-[#FFFFFF] rounded-2xl border border-[#E7E0DA] shadow-xs overflow-hidden">
        
        <!-- Filter Bar -->
        <div class="p-4 sm:p-5 border-b border-[#E7E0DA] bg-[#FAF7F4]">
            <form action="{{ route('opnames.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-center">
                <!-- Search -->
                <div class="lg:col-span-2">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama barang, SKU, catatan, atau operator..."
                        class="w-full px-3.5 py-2 text-xs bg-[#FFFFFF] border border-[#E7E0DA] rounded-xl text-[#292524] placeholder-[#78716C]/60 focus:outline-none focus:border-[#BD6745] focus:ring-1 focus:ring-[#BD6745]">
                </div>

                <!-- Filter Tanggal Mulai -->
                <div>
                    <input type="date" name="date_from" value="{{ $dateFrom }}" title="Tanggal Mulai"
                        class="w-full px-3 py-2 text-xs bg-[#FFFFFF] border border-[#E7E0DA] rounded-xl text-[#292524] focus:outline-none focus:border-[#BD6745]">
                </div>

                <!-- Filter Tanggal Selesai -->
                <div>
                    <input type="date" name="date_to" value="{{ $dateTo }}" title="Tanggal Selesai"
                        class="w-full px-3 py-2 text-xs bg-[#FFFFFF] border border-[#E7E0DA] rounded-xl text-[#292524] focus:outline-none focus:border-[#BD6745]">
                </div>

                <!-- Filter Produk & Reset -->
                <div class="flex items-center gap-2">
                    <select name="product_id" onchange="this.form.submit()"
                        class="w-full px-3 py-2 text-xs bg-[#FFFFFF] border border-[#E7E0DA] rounded-xl text-[#292524] focus:outline-none focus:border-[#BD6745]">
                        <option value="">Semua Produk</option>
                        @foreach($products as $prd)
                            <option value="{{ $prd->id }}" {{ $productId == $prd->id ? 'selected' : '' }}>
                                {{ Str::limit($prd->name, 25) }}
                            </option>
                        @endforeach
                    </select>
                    @if($search || $dateFrom || $dateTo || $productId)
                        <a href="{{ route('opnames.index') }}" class="text-xs text-[#78716C] hover:text-[#BD6745] whitespace-nowrap underline font-medium">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Tabel Riwayat Stock Opname -->
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left text-[#78716C] tabular-nums">
                <thead class="text-[11px] text-[#292524] uppercase tracking-wider bg-[#FAF7F4] border-b border-[#E7E0DA]">
                    <tr>
                        <th class="px-5 py-3.5 font-heading font-semibold">Tanggal & ID</th>
                        <th class="px-5 py-3.5 font-heading font-semibold">Produk / SKU</th>
                        <th class="px-5 py-3.5 text-right font-heading font-semibold">Stok Sistem</th>
                        <th class="px-5 py-3.5 text-right font-heading font-semibold">Stok Fisik</th>
                        <th class="px-5 py-3.5 text-center font-heading font-semibold">Selisih</th>
                        <th class="px-5 py-3.5 font-heading font-semibold">Petugas Audit</th>
                        <th class="px-5 py-3.5 text-right font-heading font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E7E0DA]">
                    @forelse($opnames as $op)
                        <tr class="hover:bg-[#FAF7F4] transition">
                            <!-- Tanggal & ID -->
                            <td class="px-5 py-3.5">
                                <div class="font-bold text-[#292524]">
                                    #OPN-{{ str_pad($op->id, 5, '0', STR_PAD_LEFT) }}
                                </div>
                                <div class="text-[10px] text-[#78716C] font-mono mt-0.5">
                                    {{ $op->opname_date ? $op->opname_date->format('d M Y') : '-' }}
                                </div>
                            </td>

                            <!-- Info Produk -->
                            <td class="px-5 py-3.5">
                                <div class="font-medium text-[#292524] text-xs">
                                    <a href="{{ route('products.show', $op->product_id) }}" class="hover:text-[#BD6745]">
                                        {{ $op->product?->name ?? 'Produk Dihapus' }}
                                    </a>
                                </div>
                                <div class="text-[10px] font-mono text-[#78716C] mt-0.5">
                                    SKU: <span class="text-[#BD6745] font-semibold">{{ $op->product?->sku ?? '-' }}</span>
                                </div>
                            </td>

                            <!-- Stok Sistem -->
                            <td class="px-5 py-3.5 text-right font-medium text-[#78716C]">
                                {{ number_format($op->system_stock) }} Unit
                            </td>

                            <!-- Stok Fisik -->
                            <td class="px-5 py-3.5 text-right font-bold text-[#292524]">
                                {{ number_format($op->physical_stock) }} Unit
                            </td>

                            <!-- Selisih -->
                            <td class="px-5 py-3.5 text-center">
                                @if($op->difference === 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                        Cocok (0)
                                    </span>
                                @elseif($op->difference > 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-amber-50 text-amber-800 border border-amber-200">
                                        +{{ number_format($op->difference) }} (Surplus)
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-rose-50 text-rose-800 border border-rose-200">
                                        {{ number_format($op->difference) }} (Defisit)
                                    </span>
                                @endif
                            </td>

                            <!-- Petugas -->
                            <td class="px-5 py-3.5">
                                <div class="font-medium text-[#292524]">{{ $op->user?->name ?? 'Sistem' }}</div>
                                <div class="text-[10px] text-[#78716C] capitalize">{{ $op->user?->role ?? '-' }}</div>
                            </td>

                            <!-- Aksi -->
                            <td class="px-5 py-3.5 text-right">
                                <a href="{{ route('opnames.show', $op->id) }}"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-[#292524] bg-[#FAF7F4] hover:bg-[#F5E6DE] hover:text-[#BD6745] border border-[#E7E0DA] rounded-lg transition shadow-2xs">
                                    <svg class="w-3.5 h-3.5 text-[#BD6745]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <span>Bukti Audit</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-[#78716C]">
                                <div class="max-w-xs mx-auto space-y-3">
                                    <div class="w-12 h-12 rounded-2xl bg-[#FAF7F4] border border-[#E7E0DA] text-[#BD6745] mx-auto flex items-center justify-center">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </div>
                                    <p class="font-medium text-[#292524] text-sm">Belum ada rekaman Stock Opname</p>
                                    <p class="text-xs text-[#78716C]">Mulai lakukan pencocokan fisik persediaan barang gudang dengan menekan tombol di atas.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($opnames->hasPages())
            <div class="p-4 border-t border-[#E7E0DA] bg-[#FAF7F4]">
                {{ $opnames->links() }}
            </div>
        @endif

    </div>

</div>
@endsection
