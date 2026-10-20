@extends('layouts.dashboard')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Navigasi & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <a href="{{ route('transactions.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#B6AEB6] hover:text-[#E7A58B] transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Kembali ke Riwayat Transaksi
        </a>

        <div class="flex items-center gap-2.5">
            <!-- Tombol Cetak Dokumen -->
            <button type="button" onclick="window.print()"
                class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-[#28252B] hover:bg-[#1F1C23] border border-[#3B3540] hover:border-[#E7A58B] text-[#F3EEE8] text-xs font-semibold rounded-xl shadow-sm transition">
                <svg class="w-4 h-4 text-[#E7A58B]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
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
                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#E7A58B] hover:bg-[#D99479] text-[#251A1B] text-xs font-bold uppercase tracking-wider rounded-xl shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                    <span>Setujui Transaksi</span>
                </button>
            </form>

            <button type="button" onclick="openRejectModal()"
                class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-[#141318] hover:bg-red-950/40 text-red-400 border border-[#3B3540] hover:border-red-500 text-xs font-semibold rounded-xl transition">
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
    <div class="p-4 rounded-xl bg-[#28252B] border border-emerald-500/40 text-emerald-300 text-xs flex items-center justify-between">
        <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="p-4 rounded-xl bg-[#28252B] border border-red-500/40 text-red-300 text-xs flex items-center gap-2">
        <svg class="w-4 h-4 text-red-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
        </svg>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <!-- Lembar Bukti Transaksi (Printable Card) -->
    <div class="bg-[#28252B] rounded-2xl border border-[#3B3540] p-6 sm:p-8 shadow-sm space-y-6">

        @php
            $trxAppName = \App\Models\Setting::get('app_name', 'Stockify Warehouse');
            $trxAppTagline = \App\Models\Setting::get('app_tagline', 'Sistem Manajemen Persediaan & Mutasi Barang Gudang');
            $trxAppLogo = \App\Models\Setting::get('app_logo');
        @endphp
        <!-- Header Bukti Transaksi -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center pb-6 border-b border-[#3B3540] gap-4">
            <div>
                <div class="flex items-center gap-2.5">
                    @if($trxAppLogo)
                        <img src="{{ asset('storage/' . $trxAppLogo) }}" alt="{{ $trxAppName }}" class="w-8 h-8 object-contain rounded-lg border border-[#3B3540]">
                    @else
                        <div class="w-8 h-8 bg-[#141318] border border-[#3B3540] text-[#F5C842] rounded-lg flex items-center justify-center font-editorial font-bold text-base">{{ strtoupper(substr($trxAppName, 0, 1)) }}</div>
                    @endif
                    <span class="font-editorial text-2xl font-medium text-[#F3EEE8] tracking-tight">{{ $trxAppName }}</span>
                </div>
                <p class="text-xs text-[#B6AEB6] mt-1 font-light">{{ $trxAppTagline }}</p>
            </div>

            <div class="sm:text-right">
                <div class="text-[10px] text-[#8C848C] uppercase tracking-wider">Nomor Bukti Transaksi</div>
                <div class="text-lg font-mono font-bold text-[#F3EEE8] mt-0.5">{{ $transaction->invoice_number }}</div>
                <div class="mt-1">
                    @if($transaction->status === 'confirmed')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-950/60 text-emerald-400 border border-emerald-800/40">
                        Disetujui / Selesai
                    </span>
                    @elseif($transaction->status === 'pending')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-950/60 text-amber-400 border border-amber-800/40">
                        Menunggu Persetujuan Manajer
                    </span>
                    @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-950/60 text-red-400 border border-red-800/40">
                        Ditolak
                    </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Rincian Informasi Dokumen -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 text-xs">
            <div class="space-y-3 bg-[#141318] p-5 rounded-xl border border-[#3B3540]">
                <h3 class="font-bold text-[#E7A58B] uppercase tracking-wider text-[10px]">Informasi Mutasi</h3>
                <div class="flex justify-between py-1 border-b border-[#3B3540]/60">
                    <span class="text-[#B6AEB6]">Jenis Transaksi:</span>
                    <span class="font-bold {{ $transaction->type === 'in' ? 'text-[#E7A58B]' : 'text-[#F3EEE8]' }}">
                        {{ $transaction->type === 'in' ? 'Barang Masuk (Inbound)' : 'Barang Keluar (Outbound)' }}
                    </span>
                </div>
                <div class="flex justify-between py-1 border-b border-[#3B3540]/60">
                    <span class="text-[#B6AEB6]">Tanggal & Waktu Input:</span>
                    <span class="font-semibold text-[#F3EEE8] font-mono">{{ $transaction->created_at->format('d F Y, H:i:s') }}</span>
                </div>
                <div class="flex justify-between py-1">
                    <span class="text-[#B6AEB6]">Rekanan Pemasok (Supplier):</span>
                    <span class="font-semibold text-[#F3EEE8]">{{ $transaction->supplier?->name ?? 'Tidak Ditentukan' }}</span>
                </div>
            </div>

            <div class="space-y-3 bg-[#141318] p-5 rounded-xl border border-[#3B3540]">
                <h3 class="font-bold text-[#E7A58B] uppercase tracking-wider text-[10px]">Petugas & Otorisasi</h3>
                <div class="flex justify-between py-1 border-b border-[#3B3540]/60">
                    <span class="text-[#B6AEB6]">Operator Penginput:</span>
                    <span class="font-semibold text-[#F3EEE8]">{{ $transaction->user?->name ?? '-' }} ({{ ucfirst($transaction->user?->role ?? '') }})</span>
                </div>
                <div class="flex justify-between py-1 border-b border-[#3B3540]/60">
                    <span class="text-[#B6AEB6]">Disetujui Oleh:</span>
                    <span class="font-semibold text-[#F3EEE8]">{{ $transaction->confirmedByUser?->name ?? 'Belum Diverifikasi' }}</span>
                </div>
                <div class="flex justify-between py-1">
                    <span class="text-[#B6AEB6]">Waktu Verifikasi:</span>
                    <span class="font-semibold text-[#F3EEE8] font-mono">{{ $transaction->confirmed_at ? $transaction->confirmed_at->format('d F Y, H:i') : '-' }}</span>
                </div>
            </div>
        </div>

        <!-- Tabel Item Barang -->
        <div class="overflow-x-auto border border-[#3B3540] rounded-xl">
            <table class="w-full text-xs text-left text-[#B6AEB6]">
                <thead class="bg-[#141318] text-[11px] text-[#F3EEE8] uppercase tracking-wider border-b border-[#3B3540]">
                    <tr>
                        <th class="px-5 py-3.5">Produk / Barang</th>
                        <th class="px-5 py-3.5">Kategori</th>
                        <th class="px-5 py-3.5 text-right">Harga Satuan</th>
                        <th class="px-5 py-3.5 text-right">Kuantitas</th>
                        <th class="px-5 py-3.5 text-right">Total Nilai</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#3B3540]/60">
                    <tr>
                        <td class="px-5 py-3.5">
                            <div class="font-bold text-[#F3EEE8] text-sm">{{ $transaction->product?->name ?? 'Produk Dihapus' }}</div>
                            <div class="text-[10px] font-mono text-[#8C848C]">SKU: {{ $transaction->product?->sku ?? '-' }}</div>
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="px-2 py-0.5 rounded text-[10px] font-medium bg-[#141318] text-[#F3EEE8] border border-[#3B3540]">
                                {{ $transaction->product?->category?->name ?? '-' }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-right text-[#B6AEB6] font-medium">
                            @php
                            $price = $transaction->type === 'in'
                            ? ($transaction->product?->purchase_price ?? 0)
                            : ($transaction->product?->selling_price ?? 0);
                            @endphp
                            Rp {{ number_format($price, 0, ',', '.') }}
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <span class="font-bold text-sm {{ $transaction->type === 'in' ? 'text-[#E7A58B]' : 'text-[#F3EEE8]' }}">
                                {{ $transaction->type === 'in' ? '+' : '-' }}{{ number_format($transaction->quantity) }} Unit
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-right font-bold text-[#F3EEE8]">
                            Rp {{ number_format($price * $transaction->quantity, 0, ',', '.') }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Catatan & Tanda Tangan -->
        <div class="pt-2 grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs">
            <div>
                <h4 class="font-bold text-[#F3EEE8] mb-1.5">Catatan / Alasan Mutasi:</h4>
                <div class="bg-[#141318] p-4 rounded-xl border border-[#3B3540] text-[#B6AEB6] min-h-[70px] leading-relaxed">
                    {{ $transaction->notes ?: 'Tidak ada catatan tambahan yang disertakan pada transaksi ini.' }}
                </div>
            </div>

            <div class="flex justify-around items-end pt-4 sm:pt-0 text-center">
                <div class="space-y-12">
                    <p class="text-[#8C848C] text-[11px]">Petugas Gudang,</p>
                    <p class="font-semibold text-[#F3EEE8] border-t border-[#3B3540] pt-1 px-4">
                        {{ $transaction->user?->name ?? 'Operator' }}
                    </p>
                </div>
                <div class="space-y-12">
                    <p class="text-[#8C848C] text-[11px]">Manajer Gudang,</p>
                    <p class="font-semibold text-[#F3EEE8] border-t border-[#3B3540] pt-1 px-4">
                        {{ $transaction->confirmedByUser?->name ?? '( ........................ )' }}
                    </p>
                </div>
            </div>
        </div>

    </div>

</div>

<!-- Modal Tolak Transaksi -->
<div id="modal-reject-transaction" tabindex="-1" aria-hidden="true"
    class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full flex items-center justify-center bg-[#141318]/80 backdrop-blur-sm">
    <div class="relative w-full max-w-md max-h-full">
        <div class="relative bg-[#28252B] rounded-2xl shadow-2xl border border-[#3B3540]">
            <div class="flex items-center justify-between p-5 border-b border-[#3B3540]">
                <h3 class="font-editorial text-lg text-[#F3EEE8] font-medium">Tolak Pengajuan Transaksi</h3>
                <button type="button" onclick="closeRejectModal()" class="text-[#8C848C] hover:text-[#F3EEE8] p-1 rounded-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form action="{{ route('transactions.reject', $transaction->id) }}" method="POST" class="p-5 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-[#B6AEB6] mb-1.5 uppercase tracking-wider">
                        Alasan Penolakan <span class="text-[#E7A58B]">*</span>
                    </label>
                    <textarea name="reason" required rows="3" placeholder="Tuliskan alasan mengapa transaksi ini ditolak..."
                        class="w-full px-3.5 py-2 text-xs bg-[#141318] border border-[#3B3540] rounded-xl text-[#F3EEE8] placeholder-[#8C848C] focus:outline-none focus:border-[#E7A58B]"></textarea>
                </div>
                <div class="flex items-center justify-end gap-2 pt-2 border-t border-[#3B3540]">
                    <button type="button" onclick="closeRejectModal()" class="px-4 py-2 text-xs font-medium text-[#B6AEB6] hover:bg-[#141318] rounded-xl">Batal</button>
                    <button type="submit" class="px-4 py-2 text-xs font-bold text-white bg-red-600 hover:bg-red-700 rounded-xl">Tolak Transaksi</button>
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