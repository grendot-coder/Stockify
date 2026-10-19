@extends('layouts.dashboard')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Navigasi & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <a href="{{ route('opnames.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#78716C] hover:text-[#292524] transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Kembali ke Daftar Stock Opname
        </a>

        <div class="flex items-center gap-2.5">
            <!-- Tombol Cetak Dokumen -->
            <button type="button" onclick="window.print()"
                class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-[#FFFFFF] hover:bg-[#FAF7F4] border border-[#E7E0DA] hover:border-[#BD6745] text-[#292524] text-xs font-semibold rounded-xl shadow-xs transition cursor-pointer">
                <svg class="w-4 h-4 text-[#BD6745]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24-1.25-.97-2.3-2.095-2.836A4.5 4.5 0 0110.5 3h3a4.5 4.5 0 014.375 7.993 4.5 4.5 0 01-2.095 2.836m-9.06 0H18m-9.06 0v5.625A2.25 2.25 0 0011.19 21h1.62a2.25 2.25 0 002.25-2.25V13.83" />
                </svg>
                <span>Cetak Lembar Audit</span>
            </button>
            <a href="{{ route('opnames.create') }}"
                class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#BD6745] hover:bg-[#A55636] text-[#1C1917] text-xs font-bold uppercase tracking-wider rounded-xl shadow-xs transition cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>+ Opname Baru</span>
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

    <!-- Lembar Bukti Audit Stock Opname (Printable Card) -->
    <div class="bg-[#FFFFFF] rounded-2xl border border-[#E7E0DA] p-6 sm:p-8 shadow-xs space-y-6">
        
        <!-- Header Dokumen Audit -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center pb-6 border-b border-[#E7E0DA] gap-4">
            <div>
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 bg-[#F5E6DE] border border-[#BD6745]/30 text-[#BD6745] rounded-lg flex items-center justify-center font-heading font-bold text-base">S</div>
                    <span class="font-heading text-2xl font-semibold text-[#292524] tracking-tight">Stockify Warehouse</span>
                </div>
                <p class="text-xs text-[#78716C] mt-1">Berita Acara Rekonsiliasi & Audit Stock Opname Gudang</p>
            </div>
            
            <div class="sm:text-right">
                <div class="text-[10px] text-[#78716C] uppercase tracking-wider font-semibold">Nomor Berita Acara</div>
                <div class="text-lg font-mono tabular-nums font-bold text-[#292524] mt-0.5">#OPN-{{ str_pad($opname->id, 5, '0', STR_PAD_LEFT) }}</div>
                <div class="mt-1">
                    @if($opname->difference === 0)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                            Stok Sesuai (0 Selisih)
                        </span>
                    @elseif($opname->difference > 0)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200">
                            Surplus (+{{ $opname->difference }} Unit)
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-800 border border-rose-200">
                            Defisit ({{ $opname->difference }} Unit)
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Rincian Informasi Opname -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 text-xs">
            <div class="space-y-3 bg-[#FAF7F4] p-5 rounded-xl border border-[#E7E0DA]">
                <h3 class="font-heading font-semibold text-[#BD6745] uppercase tracking-wider text-[11px]">Informasi Produk Diperiksa</h3>
                <div class="flex justify-between py-1 border-b border-[#E7E0DA]">
                    <span class="text-[#78716C]">Nama Produk:</span>
                    <span class="font-bold text-[#292524]">{{ $opname->product?->name ?? 'Produk Dihapus' }}</span>
                </div>
                <div class="flex justify-between py-1 border-b border-[#E7E0DA]">
                    <span class="text-[#78716C]">Kode SKU:</span>
                    <span class="font-semibold text-[#292524] font-mono tabular-nums">{{ $opname->product?->sku ?? '-' }}</span>
                </div>
                <div class="flex justify-between py-1 border-b border-[#E7E0DA]">
                    <span class="text-[#78716C]">Kelompok Kategori:</span>
                    <span class="font-semibold text-[#292524]">{{ $opname->product?->category?->name ?? '-' }}</span>
                </div>
                <div class="flex justify-between py-1">
                    <span class="text-[#78716C]">Pemasok Utama:</span>
                    <span class="font-semibold text-[#292524]">{{ $opname->product?->supplier?->name ?? 'Tidak Ditentukan' }}</span>
                </div>
            </div>

            <div class="space-y-3 bg-[#FAF7F4] p-5 rounded-xl border border-[#E7E0DA]">
                <h3 class="font-heading font-semibold text-[#BD6745] uppercase tracking-wider text-[11px]">Data Pelaksanaan & Operator</h3>
                <div class="flex justify-between py-1 border-b border-[#E7E0DA]">
                    <span class="text-[#78716C]">Tanggal Audit Opname:</span>
                    <span class="font-semibold text-[#292524] font-mono tabular-nums">{{ $opname->opname_date ? $opname->opname_date->format('d F Y') : '-' }}</span>
                </div>
                <div class="flex justify-between py-1 border-b border-[#E7E0DA]">
                    <span class="text-[#78716C]">Petugas Pemeriksa:</span>
                    <span class="font-semibold text-[#292524]">{{ $opname->user?->name ?? 'Sistem' }} ({{ ucfirst($opname->user?->role ?? '') }})</span>
                </div>
                <div class="flex justify-between py-1 border-b border-[#E7E0DA]">
                    <span class="text-[#78716C]">Waktu Catat Sistem:</span>
                    <span class="font-semibold text-[#292524] font-mono tabular-nums">{{ $opname->created_at->format('d F Y, H:i:s') }}</span>
                </div>
                <div class="flex justify-between py-1">
                    <span class="text-[#78716C]">Kondisi Rekonsiliasi:</span>
                    <span class="font-semibold {{ $opname->difference === 0 ? 'text-emerald-700' : 'text-[#BD6745]' }}">
                        {{ $opname->difference === 0 ? 'Akurat & Sesuai' : 'Terdapat Selisih Fisik' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Tabel Perbandingan Persediaan -->
        <div class="overflow-x-auto border border-[#E7E0DA] rounded-xl">
            <table class="w-full text-xs text-left text-[#78716C] tabular-nums">
                <thead class="bg-[#FAF7F4] text-[11px] text-[#292524] uppercase tracking-wider border-b border-[#E7E0DA]">
                    <tr>
                        <th class="px-5 py-3.5 font-heading font-semibold">Komponen Pemeriksaan</th>
                        <th class="px-5 py-3.5 text-right font-heading font-semibold">Stok Sistem (Awal)</th>
                        <th class="px-5 py-3.5 text-right font-heading font-semibold">Stok Fisik (Aktual)</th>
                        <th class="px-5 py-3.5 text-right font-heading font-semibold">Selisih Unit</th>
                        <th class="px-5 py-3.5 text-right font-heading font-semibold">Estimasi Dampak Nilai Modal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E7E0DA]">
                    <tr>
                        <td class="px-5 py-3.5">
                            <div class="font-bold text-[#292524] text-sm">{{ $opname->product?->name }}</div>
                            <div class="text-[10px] text-[#78716C] mt-0.5">Harga Beli Satuan: Rp {{ number_format($opname->product?->purchase_price ?? 0, 0, ',', '.') }}</div>
                        </td>
                        <td class="px-5 py-3.5 text-right font-medium text-[#78716C]">
                            {{ number_format($opname->system_stock) }} Unit
                        </td>
                        <td class="px-5 py-3.5 text-right font-bold text-[#292524] text-sm">
                            {{ number_format($opname->physical_stock) }} Unit
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            @if($opname->difference === 0)
                                <span class="font-bold text-sm text-emerald-700">0 Unit</span>
                            @elseif($opname->difference > 0)
                                <span class="font-bold text-sm text-amber-700">+{{ number_format($opname->difference) }} Unit</span>
                            @else
                                <span class="font-bold text-sm text-rose-700">{{ number_format($opname->difference) }} Unit</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-right font-bold text-[#292524]">
                            @php
                                $impact = ($opname->product?->purchase_price ?? 0) * $opname->difference;
                            @endphp
                            <span class="{{ $impact < 0 ? 'text-rose-700' : ($impact > 0 ? 'text-amber-700' : 'text-emerald-700') }}">
                                {{ $impact < 0 ? '-' : ($impact > 0 ? '+' : '') }}Rp {{ number_format(abs($impact), 0, ',', '.') }}
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Catatan & Tanda Tangan Berita Acara -->
        <div class="pt-2 grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs">
            <div>
                <h4 class="font-bold text-[#292524] mb-1.5">Catatan / Analisis Penyebab:</h4>
                <div class="bg-[#FAF7F4] p-4 rounded-xl border border-[#E7E0DA] text-[#292524] min-h-[70px] leading-relaxed">
                    {{ $opname->notes ?: 'Tidak ada catatan tambahan yang disertakan pada sesi opname ini.' }}
                </div>
            </div>

            <div class="flex justify-around items-end pt-4 sm:pt-0 text-center">
                <div class="space-y-12">
                    <p class="text-[#78716C] text-[11px]">Petugas Pemeriksa,</p>
                    <p class="font-semibold text-[#292524] border-t border-[#E7E0DA] pt-1 px-4">
                        {{ $opname->user?->name ?? 'Pemeriksa' }}
                    </p>
                </div>
                <div class="space-y-12">
                    <p class="text-[#78716C] text-[11px]">Manajer Gudang / Kepala Logistik,</p>
                    <p class="font-semibold text-[#292524] border-t border-[#E7E0DA] pt-1 px-4">
                        ( ..................................... )
                    </p>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
