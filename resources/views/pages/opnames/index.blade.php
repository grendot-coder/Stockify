@extends('layouts.dashboard')

@section('content')
<div class="space-y-6">

    <!-- Header & Tombol Mulai Opname -->
    <div class="bg-[#202429] rounded-2xl border border-[#343A40] p-6 shadow-xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="w-2 h-2 rounded-full bg-[#F5C842]"></span>
                <span class="text-[11px] uppercase tracking-[0.2em] text-[#F5C842] font-semibold">Audit Fisik Persediaan</span>
            </div>
            <h1 class="font-heading text-2xl sm:text-3xl font-semibold text-[#F5F5F5] tracking-tight">Stock Opname Gudang</h1>
            <p class="text-xs text-[#A1A1AA] mt-1">Pemeriksaan fisik stok aktual, kalkulasi selisih persediaan, dan rekonsiliasi sistem</p>
        </div>
        <div>
            <a href="{{ route('opnames.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#F5C842] hover:bg-[#E8B92E] text-[#101214] text-xs font-bold uppercase tracking-wider rounded-xl shadow-xs transition cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>+ Input Perhitungan Fisik</span>
            </a>
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

    @if(session('error') || $errors->any())
        <div class="p-4 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs flex items-center gap-2 shadow-xs">
            <svg class="w-4 h-4 text-rose-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            <span>{{ session('error') ?? $errors->first() }}</span>
        </div>
    @endif

    <!-- 4 Kotak Ringkasan Metrik Opname -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5">
        <div class="bg-[#202429] rounded-2xl border border-[#343A40] p-4 shadow-xs">
            <div class="text-[10px] font-semibold text-[#A1A1AA] uppercase tracking-wider">Total Sesi Opname</div>
            <div class="font-heading text-2xl font-semibold tabular-nums text-[#F5F5F5] mt-1">{{ number_format($stats['total']) }}</div>
            <div class="text-[10px] text-[#A1A1AA] mt-0.5">Seluruh rekaman audit</div>
        </div>
        <div class="bg-[#202429] rounded-2xl border border-[#343A40] p-4 shadow-xs">
            <div class="text-[10px] font-semibold text-emerald-400 uppercase tracking-wider">Stok Sesuai (0 Selisih)</div>
            <div class="font-heading text-2xl font-semibold tabular-nums text-emerald-400 mt-1">{{ number_format($stats['matched']) }}</div>
            <div class="text-[10px] text-[#A1A1AA] mt-0.5">Fisik cocok dengan sistem</div>
        </div>
        <div class="bg-[#202429] rounded-2xl border border-[#343A40] p-4 shadow-xs">
            <div class="text-[10px] font-semibold text-rose-400 uppercase tracking-wider">Selisih Defisit (Kurang)</div>
            <div class="font-heading text-2xl font-semibold tabular-nums text-rose-400 mt-1">{{ number_format($stats['deficit']) }}</div>
            <div class="text-[10px] text-[#A1A1AA] mt-0.5">Potensi hilang / rusak</div>
        </div>
        <div class="bg-[#202429] rounded-2xl border border-[#343A40] p-4 shadow-xs">
            <div class="text-[10px] font-semibold text-amber-400 uppercase tracking-wider">Selisih Surplus (Lebih)</div>
            <div class="font-heading text-2xl font-semibold tabular-nums text-amber-400 mt-1">{{ number_format($stats['surplus']) }}</div>
            <div class="text-[10px] text-[#A1A1AA] mt-0.5">Belum tercatat masuk</div>
        </div>
    </div>

    <!-- Filter Bar & Card Tabel -->
    <div class="bg-[#202429] rounded-2xl border border-[#343A40] shadow-xs overflow-hidden">
        
        <!-- Filter Bar -->
        <div class="p-4 sm:p-5 border-b border-[#343A40] bg-[#181C20]">
            <form action="{{ route('opnames.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-center">
                <!-- Search -->
                <div class="lg:col-span-2">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama barang, SKU, catatan, atau operator..."
                        class="w-full px-3.5 py-2 text-xs bg-[#202429] border border-[#343A40] rounded-xl text-[#F5F5F5] placeholder-[#A1A1AA]/60 focus:outline-none focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842]">
                </div>

                <!-- Filter Tanggal Mulai -->
                <div>
                    <input type="date" name="date_from" value="{{ $dateFrom }}" title="Tanggal Mulai"
                        class="w-full px-3 py-2 text-xs bg-[#202429] border border-[#343A40] rounded-xl text-[#F5F5F5] focus:outline-none focus:border-[#F5C842]">
                </div>

                <!-- Filter Tanggal Selesai -->
                <div>
                    <input type="date" name="date_to" value="{{ $dateTo }}" title="Tanggal Selesai"
                        class="w-full px-3 py-2 text-xs bg-[#202429] border border-[#343A40] rounded-xl text-[#F5F5F5] focus:outline-none focus:border-[#F5C842]">
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
                    @if($search || $dateFrom || $dateTo || $productId)
                        <a href="{{ route('opnames.index') }}" class="text-xs text-[#A1A1AA] hover:text-[#F5C842] whitespace-nowrap underline font-medium">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Tabel Riwayat Stock Opname -->
        <div class="overflow-x-auto" tabindex="0" role="region" aria-label="Tabel Riwayat Stock Opname">
            <table class="w-full text-xs text-left text-[#A1A1AA] tabular-nums">
                <thead class="text-[11px] text-[#F5F5F5] uppercase tracking-wider bg-[#181C20] border-b border-[#343A40] sticky top-0 z-10">
                    <tr>
                        <th scope="col" class="px-5 py-3.5 font-heading font-semibold">Tanggal & ID</th>
                        <th scope="col" class="px-5 py-3.5 font-heading font-semibold">Produk / SKU</th>
                        <th scope="col" class="px-5 py-3.5 text-right font-heading font-semibold">Stok Sistem</th>
                        <th scope="col" class="px-5 py-3.5 text-right font-heading font-semibold">Stok Fisik</th>
                        <th scope="col" class="px-5 py-3.5 text-center font-heading font-semibold">Selisih</th>
                        <th scope="col" class="px-5 py-3.5 font-heading font-semibold">Petugas Audit</th>
                        <th scope="col" class="px-5 py-3.5 text-right font-heading font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#343A40]">
                    @forelse($opnames as $op)
                        <tr class="hover:bg-[#2A3036]/60 transition">
                            <!-- Tanggal & ID -->
                            <td class="px-5 py-3.5">
                                <div class="font-bold text-[#F5F5F5]">
                                    #OPN-{{ str_pad($op->id, 5, '0', STR_PAD_LEFT) }}
                                </div>
                                <div class="text-[10px] text-[#A1A1AA] font-mono mt-0.5">
                                    {{ $op->opname_date ? $op->opname_date->format('d M Y') : '-' }}
                                </div>
                            </td>

                            <!-- Info Produk -->
                            <td class="px-5 py-3.5">
                                <div class="font-medium text-[#F5F5F5] text-xs">
                                    <a href="{{ route('products.show', $op->product_id) }}" class="hover:text-[#F5C842] transition">
                                        {{ $op->product?->name ?? 'Produk Dihapus' }}
                                    </a>
                                </div>
                                <div class="text-[10px] font-mono text-[#A1A1AA] mt-0.5">
                                    SKU: <span class="text-[#F5C842] font-semibold">{{ $op->product?->sku ?? '-' }}</span>
                                </div>
                            </td>

                            <!-- Stok Sistem -->
                            <td class="px-5 py-3.5 text-right font-medium text-[#A1A1AA]">
                                {{ number_format($op->system_stock) }} Unit
                            </td>

                            <!-- Stok Fisik -->
                            <td class="px-5 py-3.5 text-right font-bold text-[#F5F5F5]">
                                {{ number_format($op->physical_stock) }} Unit
                            </td>

                            <!-- Selisih -->
                            <td class="px-5 py-3.5 text-center whitespace-nowrap">
                                @if($op->difference === 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">
                                        Cocok (0)
                                    </span>
                                @elseif($op->difference > 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/30">
                                        +{{ number_format($op->difference) }} (Surplus)
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-rose-500/10 text-rose-400 border border-rose-500/30">
                                        {{ number_format($op->difference) }} (Defisit)
                                    </span>
                                @endif
                            </td>

                            <!-- Petugas -->
                            <td class="px-5 py-3.5">
                                <div class="font-medium text-[#F5F5F5]">{{ $op->user?->name ?? 'Sistem' }}</div>
                                <div class="text-[10px] text-[#A1A1AA] capitalize">{{ $op->user?->role ?? '-' }}</div>
                            </td>

                            <!-- Aksi -->
                            <td class="px-5 py-3.5 text-right whitespace-nowrap">
                                <a href="{{ route('opnames.show', $op->id) }}"
                                    class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold text-[#F5F5F5] bg-[#181C20] hover:bg-[#2A3036] hover:text-[#F5C842] border border-[#343A40] hover:border-[#F5C842]/40 rounded-lg transition">
                                    <svg class="w-3.5 h-3.5 text-[#F5C842]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <span>Bukti Audit</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-[#A1A1AA]">
                                <div class="max-w-xs mx-auto space-y-3">
                                    <div class="w-12 h-12 rounded-2xl bg-[#181C20] border border-[#343A40] text-[#F5C842] mx-auto flex items-center justify-center">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </div>
                                    <p class="font-medium text-[#F5F5F5] text-sm">Belum ada rekaman Stock Opname</p>
                                    <p class="text-xs text-[#A1A1AA]">Mulai lakukan pencocokan fisik persediaan barang gudang dengan menekan tombol di atas.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($opnames->hasPages())
            <div class="p-4 border-t border-[#343A40] bg-[#181C20]">
                {{ $opnames->links() }}
            </div>
        @endif

    </div>

</div>
@endsection
