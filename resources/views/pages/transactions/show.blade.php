@extends('layouts.dashboard')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Navigasi & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <a href="{{ route('transactions.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#A1A1AA] hover:text-[#F5F5F5] transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Kembali ke Riwayat Transaksi
        </a>

        <div class="flex items-center gap-2.5">
            <!-- Tombol Cetak Dokumen -->
            <button type="button" onclick="window.print()"
                class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-[#202429] hover:bg-[#181C20] border border-[#343A40] hover:border-[#F5C842] text-[#F5F5F5] text-xs font-semibold rounded-xl shadow-xs transition cursor-pointer">
                <svg class="w-4 h-4 text-[#F5C842]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24-1.25-.97-2.3-2.095-2.836A4.5 4.5 0 0110.5 3h3a4.5 4.5 0 014.375 7.993 4.5 4.5 0 01-2.095 2.836m-9.06 0H18m-9.06 0v5.625A2.25 2.25 0 0011.19 21h1.62a2.25 2.25 0 002.25-2.25V13.83" />
                </svg>
                <span>Cetak Bukti</span>
            </button>

            <!-- Aksi Konfirmasi / Tolak jika status masih Pending -->
            @if($transaction->status === 'pending' && in_array(Auth::user()->role, ['admin', 'manager']))
            <form action="{{ route('transactions.confirm', $transaction->id) }}" method="POST" class="inline-block"
                onsubmit="return confirm('Apakah Anda yakin ingin menyetujui transaksi ini? Stok produk akan diperbarui otomatis.');">
                @csrf
                <button type="submit"
                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#F5C842] hover:bg-[#E8B92E] text-[#101214] text-xs font-bold uppercase tracking-wider rounded-xl shadow-xs transition cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                    <span>Setujui Transaksi</span>
                </button>
            </form>

            <button type="button" onclick="openRejectModal()"
                class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/30 text-xs font-semibold rounded-xl transition cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
                <span>Tolak</span>
            </button>
            @endif
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

    @if(session('error'))
    <div class="p-4 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs flex items-center gap-2 shadow-xs">
        <svg class="w-4 h-4 text-rose-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
        </svg>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <!-- Lembar Bukti Transaksi (Printable Card) -->
    <div class="bg-[#202429] rounded-2xl border border-[#343A40] p-6 sm:p-8 shadow-xs space-y-6">

        @php
        $trxAppName = \App\Models\Setting::get('app_name', 'Stockify Warehouse');
        $trxAppTagline = \App\Models\Setting::get('app_tagline', 'Sistem Manajemen Persediaan & Mutasi Barang Gudang');
        $trxAppLogo = \App\Models\Setting::get('app_logo');
        @endphp
        <!-- Header Bukti Transaksi -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center pb-6 border-b border-[#343A40] gap-4">
            <div>
                <div class="flex items-center gap-2.5">
                    @if($trxAppLogo)
                    <img src="{{ asset('storage/' . $trxAppLogo) }}" alt="{{ $trxAppName }}" class="w-8 h-8 object-contain rounded-lg border border-[#343A40]">
                    @else
                    <div class="w-8 h-8 bg-[#181C20] border border-[#343A40] text-[#F5C842] rounded-lg flex items-center justify-center font-heading font-bold text-base">{{ strtoupper(substr($trxAppName, 0, 1)) }}</div>
                    @endif
                    <span class="font-heading text-2xl font-semibold text-[#F5F5F5] tracking-tight">{{ $trxAppName }}</span>
                </div>
                <p class="text-xs text-[#A1A1AA] mt-1">{{ $trxAppTagline }}</p>
            </div>

            <div class="sm:text-right">
                <div class="text-[10px] text-[#A1A1AA] uppercase tracking-wider font-semibold">Nomor Bukti Transaksi</div>
                <div class="text-lg font-mono font-bold text-[#F5F5F5] mt-0.5">{{ $transaction->invoice_number }}</div>
                <div class="mt-1">
                    @if($transaction->status === 'confirmed')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">
                        Disetujui / Selesai
                    </span>
                    @elseif($transaction->status === 'pending')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/30">
                        Menunggu Persetujuan Manajer
                    </span>
                    @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-500/10 text-rose-400 border border-rose-500/30">
                        Ditolak
                    </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Rincian Informasi Dokumen -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 text-xs">
            <div class="space-y-3 bg-[#181C20] p-5 rounded-xl border border-[#343A40]">
                <h3 class="font-heading font-semibold text-[#F5C842] uppercase tracking-wider text-[11px]">Informasi Mutasi</h3>
                <div class="flex justify-between py-1 border-b border-[#343A40]">
                    <span class="text-[#A1A1AA]">Jenis Transaksi:</span>
                    <span class="font-bold {{ $transaction->type === 'in' ? 'text-emerald-400' : 'text-[#F5C842]' }}">
                        {{ $transaction->type === 'in' ? 'Barang Masuk (Inbound)' : 'Barang Keluar (Outbound)' }}
                    </span>
                </div>
                <div class="flex justify-between py-1 border-b border-[#343A40]">
                    <span class="text-[#A1A1AA]">Tanggal & Waktu Input:</span>
                    <span class="font-semibold text-[#F5F5F5] font-mono">{{ $transaction->created_at->format('d F Y, H:i:s') }}</span>
                </div>
                <div class="flex justify-between py-1">
                    <span class="text-[#A1A1AA]">Rekanan Pemasok (Supplier):</span>
                    <span class="font-semibold text-[#F5F5F5]">{{ $transaction->supplier?->name ?? 'Tidak Ditentukan' }}</span>
                </div>
            </div>

            <div class="space-y-3 bg-[#181C20] p-5 rounded-xl border border-[#343A40]">
                <h3 class="font-heading font-semibold text-[#F5C842] uppercase tracking-wider text-[11px]">Petugas & Otorisasi</h3>
                <div class="flex justify-between py-1 border-b border-[#343A40]">
                    <span class="text-[#A1A1AA]">Operator Penginput:</span>
                    <span class="font-semibold text-[#F5F5F5]">{{ $transaction->user?->name ?? '-' }} ({{ ucfirst($transaction->user?->role ?? '') }})</span>
                </div>
                <div class="flex justify-between py-1 border-b border-[#343A40]">
                    <span class="text-[#A1A1AA]">Disetujui Oleh:</span>
                    <span class="font-semibold text-[#F5F5F5]">{{ $transaction->confirmedByUser?->name ?? 'Belum Diverifikasi' }}</span>
                </div>
                <div class="flex justify-between py-1">
                    <span class="text-[#A1A1AA]">Waktu Verifikasi:</span>
                    <span class="font-semibold text-[#F5F5F5] font-mono">{{ $transaction->confirmed_at ? $transaction->confirmed_at->format('d F Y, H:i') : '-' }}</span>
                </div>
            </div>
        </div>

        <!-- Tabel Item Barang -->
        <div class="overflow-x-auto border border-[#343A40] rounded-xl" tabindex="0" role="region" aria-label="Tabel Rincian Item Transaksi">
            <table class="w-full text-xs text-left text-[#A1A1AA] tabular-nums">
                <thead class="bg-[#181C20] text-[11px] text-[#F5F5F5] uppercase tracking-wider border-b border-[#343A40] sticky top-0 z-10">
                    <tr>
                        <th scope="col" class="px-5 py-3.5 font-heading font-semibold">Produk / Barang</th>
                        <th scope="col" class="px-5 py-3.5 font-heading font-semibold">Kategori</th>
                        <th scope="col" class="px-5 py-3.5 text-right font-heading font-semibold">Harga Satuan</th>
                        <th scope="col" class="px-5 py-3.5 text-right font-heading font-semibold">Kuantitas</th>
                        <th scope="col" class="px-5 py-3.5 text-right font-heading font-semibold">Total Nilai</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#343A40]">
                    <tr class="hover:bg-[#2A3036]/60 transition">
                        <td class="px-5 py-3.5">
                            <div class="font-bold text-[#F5F5F5] text-sm">{{ $transaction->product?->name ?? 'Produk Dihapus' }}</div>
                            <div class="text-[10px] font-mono text-[#A1A1AA]">SKU: {{ $transaction->product?->sku ?? '-' }}</div>
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-medium bg-[#181C20] text-[#F5F5F5] border border-[#343A40]">
                                {{ $transaction->product?->category?->name ?? '-' }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-right text-[#A1A1AA] font-mono">
                            @php
                            $price = $transaction->type === 'in'
                            ? ($transaction->product?->purchase_price ?? 0)
                            : ($transaction->product?->selling_price ?? 0);
                            @endphp
                            Rp {{ number_format($price, 0, ',', '.') }}
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <span class="font-bold text-sm font-mono {{ $transaction->type === 'in' ? 'text-emerald-400' : 'text-[#F5C842]' }}">
                                {{ $transaction->type === 'in' ? '' : '' }}{{ number_format($transaction->quantity) }} Unit
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-right font-bold font-mono text-[#F5F5F5]">
                            Rp {{ number_format($price * $transaction->quantity, 0, ',', '.') }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Catatan & Tanda Tangan -->
        <div class="pt-2 grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs">
            <div>
                <h4 class="font-semibold text-[#F5F5F5] mb-1.5 uppercase tracking-wider text-[11px]">Catatan / Alasan Mutasi:</h4>
                <div class="bg-[#181C20] p-4 rounded-xl border border-[#343A40] text-[#F5F5F5] min-h-[70px] leading-relaxed">
                    {{ $transaction->notes ?: 'Tidak ada catatan tambahan yang disertakan pada transaksi ini.' }}
                </div>
            </div>

            <div class="flex justify-around items-end pt-4 sm:pt-0 text-center">
                <div class="space-y-12">
                    <p class="text-[#A1A1AA] text-[11px]">Petugas Gudang,</p>
                    <p class="font-semibold text-[#F5F5F5] border-t border-[#343A40] pt-1 px-4">
                        {{ $transaction->user?->name ?? 'Operator' }}
                    </p>
                </div>
                <div class="space-y-12">
                    <p class="text-[#A1A1AA] text-[11px]">Manajer Gudang,</p>
                    <p class="font-semibold text-[#F5F5F5] border-t border-[#343A40] pt-1 px-4">
                        {{ $transaction->confirmedByUser?->name ?? '( ........................ )' }}
                    </p>
                </div>
            </div>
        </div>

    </div>

</div>

<!-- Modal Tolak Transaksi -->
<div id="modal-reject-transaction" tabindex="-1" aria-hidden="true"
    class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full flex items-center justify-center bg-black/60 backdrop-blur-xs">
    <div class="relative w-full max-w-md max-h-full">
        <div class="relative bg-[#202429] rounded-2xl shadow-xl border border-[#343A40] overflow-hidden">
            <div class="flex items-center justify-between p-5 border-b border-[#343A40] bg-[#181C20]">
                <div>
                    <h3 class="font-heading text-base text-[#F5F5F5] font-semibold">Tolak Pengajuan Transaksi</h3>
                    <p class="text-[11px] text-[#A1A1AA]">Berikan catatan alasan mengapa mutasi ini dibatalkan</p>
                </div>
                <button type="button" onclick="closeRejectModal()" class="text-[#A1A1AA] hover:text-[#F5F5F5] p-1 rounded-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form action="{{ route('transactions.reject', $transaction->id) }}" method="POST" class="p-5 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-[#F5F5F5] mb-1.5 uppercase tracking-wider">
                        Alasan Penolakan <span class="text-[#F5C842]">*</span>
                    </label>
                    <textarea name="reason" required rows="3" placeholder="Tuliskan alasan mengapa transaksi ini ditolak..."
                        class="w-full px-3.5 py-2 text-xs bg-[#181C20] border border-[#343A40] rounded-xl text-[#F5F5F5] placeholder-[#A1A1AA]/60 focus:outline-none focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842]"></textarea>
                </div>
                <div class="flex items-center justify-end gap-2 pt-3 border-t border-[#343A40]">
                    <button type="button" onclick="closeRejectModal()" class="px-4 py-2 text-xs font-semibold text-[#A1A1AA] hover:text-[#F5F5F5] hover:bg-[#2A3036] rounded-xl transition">Batal</button>
                    <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-rose-600 hover:bg-rose-500 rounded-xl transition cursor-pointer">Tolak Transaksi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openRejectModal() {
        document.getElementById('modal-reject-transaction').classList.remove('hidden');
    }

    function closeRejectModal() {
        document.getElementById('modal-reject-transaction').classList.add('hidden');
    }
</script>
@endsection