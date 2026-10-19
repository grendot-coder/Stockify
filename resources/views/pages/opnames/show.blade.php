@extends('layouts.dashboard')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Navigasi & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <a href="{{ route('opnames.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#A1A1AA] hover:text-[#F5F5F5] transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Kembali ke Daftar Stock Opname
        </a>

        <div class="flex items-center gap-2.5">
            <!-- Tombol Cetak Dokumen -->
            <button type="button" onclick="window.print()"
                class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-[#202429] hover:bg-[#181C20] border border-[#343A40] hover:border-[#F5C842] text-[#F5F5F5] text-xs font-semibold rounded-xl shadow-xs transition cursor-pointer">
                <svg class="w-4 h-4 text-[#F5C842]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24-1.25-.97-2.3-2.095-2.836A4.5 4.5 0 0110.5 3h3a4.5 4.5 0 014.375 7.993 4.5 4.5 0 01-2.095 2.836m-9.06 0H18m-9.06 0v5.625A2.25 2.25 0 0011.19 21h1.62a2.25 2.25 0 002.25-2.25V13.83" />
                </svg>
                <span>Cetak Lembar Audit</span>
            </button>
            <a href="{{ route('opnames.create') }}"
                class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#F5C842] hover:bg-[#E8B92E] text-[#101214] text-xs font-bold uppercase tracking-wider rounded-xl shadow-xs transition cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>+ Opname Baru</span>
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

    <!-- Lembar Bukti Audit Stock Opname (Printable Card) -->
    <div class="bg-[#202429] rounded-2xl border border-[#343A40] p-6 sm:p-8 shadow-xs space-y-6">
        
        <!-- Header Dokumen Audit -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center pb-6 border-b border-[#343A40] gap-4">
            <div>
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 bg-[#F5C842]/15 border border-[#F5C842]/30 text-[#F5C842] rounded-lg flex items-center justify-center font-heading font-bold text-base">S</div>
                    <span class="font-heading text-2xl font-semibold text-[#F5F5F5] tracking-tight">Stockify Warehouse</span>
                </div>
                <p class="text-xs text-[#A1A1AA] mt-1">Berita Acara Rekonsiliasi & Audit Stock Opname Gudang</p>
            </div>
            
            <div class="sm:text-right">
                <div class="text-[10px] text-[#A1A1AA] uppercase tracking-wider font-semibold">Nomor Berita Acara</div>
                <div class="text-lg font-mono tabular-nums font-bold text-[#F5F5F5] mt-0.5">#OPN-{{ str_pad($opname->id, 5, '0', STR_PAD_LEFT) }}</div>
                <div class="mt-1">
                    @if($opname->difference === 0)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">
                            Stok Sesuai (0 Selisih)
                        </span>
                    @elseif($opname->difference > 0)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/30">
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
            <div class="space-y-3 bg-[#181C20] p-5 rounded-xl border border-[#343A40]">
                <h3 class="font-heading font-semibold text-[#F5C842] uppercase tracking-wider text-[11px]">Informasi Produk Diperiksa</h3>
                <div class="flex justify-between py-1 border-b border-[#343A40]">
                    <span class="text-[#A1A1AA]">Nama Produk:</span>
                    <span class="font-bold text-[#F5F5F5]">{{ $opname->product?->name ?? 'Produk Dihapus' }}</span>
                </div>
                <div class="flex justify-between py-1 border-b border-[#343A40]">
                    <span class="text-[#A1A1AA]">Kode SKU:</span>
                    <span class="font-semibold text-[#F5F5F5] font-mono tabular-nums">{{ $opname->product?->sku ?? '-' }}</span>
                </div>
                <div class="flex justify-between py-1 border-b border-[#343A40]">
                    <span class="text-[#A1A1AA]">Kelompok Kategori:</span>
                    <span class="font-semibold text-[#F5F5F5]">{{ $opname->product?->category?->name ?? '-' }}</span>
                </div>
                <div class="flex justify-between py-1">
                    <span class="text-[#A1A1AA]">Pemasok Utama:</span>
                    <span class="font-semibold text-[#F5F5F5]">{{ $opname->product?->supplier?->name ?? 'Tidak Ditentukan' }}</span>
                </div>
            </div>

            <div class="space-y-3 bg-[#181C20] p-5 rounded-xl border border-[#343A40]">
                <h3 class="font-heading font-semibold text-[#F5C842] uppercase tracking-wider text-[11px]">Data Pelaksanaan & Operator</h3>
                <div class="flex justify-between py-1 border-b border-[#343A40]">
                    <span class="text-[#A1A1AA]">Tanggal Audit Opname:</span>
                    <span class="font-semibold text-[#F5F5F5] font-mono tabular-nums">{{ $opname->opname_date ? $opname->opname_date->format('d F Y') : '-' }}</span>
                </div>
                <div class="flex justify-between py-1 border-b border-[#343A40]">
                    <span class="text-[#A1A1AA]">Petugas Pemeriksa:</span>
                    <span class="font-semibold text-[#F5F5F5]">{{ $opname->user?->name ?? 'Sistem' }} ({{ ucfirst($opname->user?->role ?? '') }})</span>
                </div>
                <div class="flex justify-between py-1 border-b border-[#343A40]">
                    <span class="text-[#A1A1AA]">Waktu Catat Sistem:</span>
                    <span class="font-semibold text-[#F5F5F5] font-mono tabular-nums">{{ $opname->created_at->format('d F Y, H:i:s') }}</span>
                </div>
                <div class="flex justify-between py-1">
                    <span class="text-[#A1A1AA]">Kondisi Rekonsiliasi:</span>
                    <span class="font-semibold {{ $opname->difference === 0 ? 'text-emerald-700' : 'text-[#F5C842]' }}">
                        {{ $opname->difference === 0 ? 'Akurat & Sesuai' : 'Terdapat Selisih Fisik' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Tabel Perbandingan Persediaan -->
        <div class="overflow-x-auto border border-[#343A40] rounded-xl">
            <table class="w-full text-xs text-left text-[#A1A1AA] tabular-nums">
                <thead class="bg-[#181C20] text-[11px] text-[#F5F5F5] uppercase tracking-wider border-b border-[#343A40]">
                    <tr>
                        <th class="px-5 py-3.5 font-heading font-semibold">Komponen Pemeriksaan</th>
                        <th class="px-5 py-3.5 text-right font-heading font-semibold">Stok Sistem (Awal)</th>
                        <th class="px-5 py-3.5 text-right font-heading font-semibold">Stok Fisik (Aktual)</th>
                        <th class="px-5 py-3.5 text-right font-heading font-semibold">Selisih Unit</th>
                        <th class="px-5 py-3.5 text-right font-heading font-semibold">Estimasi Dampak Nilai Modal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#343A40]">
                    <tr>
                        <td class="px-5 py-3.5">
                            <div class="font-bold text-[#F5F5F5] text-sm">{{ $opname->product?->name }}</div>
                            <div class="text-[10px] text-[#A1A1AA] mt-0.5">Harga Beli Satuan: Rp {{ number_format($opname->product?->purchase_price ?? 0, 0, ',', '.') }}</div>
                        </td>
                        <td class="px-5 py-3.5 text-right font-medium text-[#A1A1AA]">
                            {{ number_format($opname->system_stock) }} Unit
                        </td>
                        <td class="px-5 py-3.5 text-right font-bold text-[#F5F5F5] text-sm">
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
                        <td class="px-5 py-3.5 text-right font-bold text-[#F5F5F5]">
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
                <h4 class="font-bold text-[#F5F5F5] mb-1.5">Catatan / Analisis Penyebab:</h4>
                <div class="bg-[#181C20] p-4 rounded-xl border border-[#343A40] text-[#F5F5F5] min-h-[70px] leading-relaxed">
                    {{ $opname->notes ?: 'Tidak ada catatan tambahan yang disertakan pada sesi opname ini.' }}
                </div>
            </div>

            <div class="flex justify-around items-end pt-4 sm:pt-0 text-center">
                <div class="space-y-12">
                    <p class="text-[#A1A1AA] text-[11px]">Petugas Pemeriksa,</p>
                    <p class="font-semibold text-[#F5F5F5] border-t border-[#343A40] pt-1 px-4">
                        {{ $opname->user?->name ?? 'Pemeriksa' }}
                    </p>
                </div>
                <div class="space-y-12">
                    <p class="text-[#A1A1AA] text-[11px]">Manajer Gudang / Kepala Logistik,</p>
                    <p class="font-semibold text-[#F5F5F5] border-t border-[#343A40] pt-1 px-4">
                        ( ..................................... )
                    </p>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
