@extends('layouts.dashboard')

@section('content')
<div class="space-y-5">

    <!-- Header & Tombol Tambah -->
    <div class="bg-[#202429] rounded-2xl border border-[#343A40] p-6 shadow-xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <span class="text-[11px] font-mono tracking-widest uppercase text-[#F5C842] font-semibold">Rekanan Rantai Pasok</span>
            <h1 class="text-xl sm:text-2xl font-editorial font-medium text-[#F5F5F5] mt-0.5">Data Rekanan Supplier</h1>
            <p class="text-xs text-[#A1A1AA] mt-1">Kelola data vendor, manufaktur, dan pemasok barang gudang</p>
        </div>
        <button type="button" data-modal-target="modal-create-supplier" data-modal-toggle="modal-create-supplier"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#F5C842] hover:bg-[#E8B92E] text-[#101214] text-xs font-semibold rounded-xl shadow-xs transition duration-150 cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span>Tambah Supplier</span>
        </button>
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

    <!-- Card Tabel & Pencarian -->
    <div class="bg-[#202429] rounded-2xl border border-[#343A40] shadow-xs overflow-hidden">

        <!-- Filter Pencarian -->
        <div class="p-4 border-b border-[#343A40] bg-[#181C20] flex flex-col sm:flex-row items-center justify-between gap-3">
            <form action="{{ route('suppliers.index') }}" method="GET" class="w-full sm:w-80">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-[#A1A1AA]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama, telp, alamat..."
                        class="w-full pl-9 pr-3 py-2 text-xs bg-[#202429] border border-[#343A40] rounded-xl text-[#F5F5F5] placeholder-[#A1A1AA]/60 focus:outline-none focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842]">
                </div>
            </form>
            <span class="text-xs text-[#A1A1AA]">Total: <strong class="text-[#F5F5F5] font-mono">{{ $suppliers->total() }}</strong> Rekanan</span>
        </div>

        <!-- Tabel Supplier -->
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left text-[#A1A1AA]">
                <thead class="text-[11px] text-[#F5F5F5] uppercase tracking-wider bg-[#181C20] border-b border-[#343A40]">
                    <tr>
                        <th class="px-5 py-3.5 w-12 text-center">No</th>
                        <th class="px-5 py-3.5">Nama Supplier</th>
                        <th class="px-5 py-3.5">Kontak</th>
                        <th class="px-5 py-3.5">Alamat</th>
                        <th class="px-5 py-3.5 text-center">Produk Disuplai</th>
                        <th class="px-5 py-3.5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#343A40]">
                    @forelse($suppliers as $index => $sup)
                    <tr class="hover:bg-[#2A3036] transition">
                        <td class="px-5 py-3.5 text-center text-[#A1A1AA] font-mono">
                            {{ $suppliers->firstItem() + $index }}
                        </td>
                        <td class="px-5 py-3.5 font-semibold text-[#F5F5F5]">
                            {{ $sup->name }}
                        </td>
                        <td class="px-5 py-3.5 space-y-0.5">
                            <div class="font-mono text-[#F5F5F5] font-medium">{{ $sup->phone }}</div>
                            @if($sup->email)
                            <div class="text-[11px] text-[#A1A1AA]">{{ $sup->email }}</div>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-[#A1A1AA] max-w-xs truncate">
                            {{ $sup->address ?: '—' }}
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-[11px] font-mono font-medium bg-[#F5C842]/15 text-[#F5C842] border border-[#F5C842]/30">
                                {{ $sup->products_count }} Produk
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-right space-x-3 whitespace-nowrap">
                            <button type="button"
                                onclick="openEditSupplierModal({{ $sup->id }}, '{{ addslashes($sup->name) }}', '{{ addslashes($sup->email ?? '') }}', '{{ addslashes($sup->phone) }}', '{{ addslashes($sup->address ?? '') }}')"
                                class="text-[#F5C842] hover:text-[#E8B92E] font-semibold transition cursor-pointer">
                                Edit
                            </button>
                            <form action="{{ route('suppliers.destroy', $sup->id) }}" method="POST" class="inline-block"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus supplier {{ addslashes($sup->name) }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-400 hover:text-rose-300 font-semibold transition cursor-pointer">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-10 text-center text-[#A1A1AA]">
                            Tidak ada data supplier yang ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($suppliers->hasPages())
        <div class="p-4 border-t border-[#343A40] bg-[#181C20]">
            {{ $suppliers->links() }}
        </div>
        @endif
    </div>

</div>

<!-- Modal Tambah Supplier -->
<div id="modal-create-supplier" tabindex="-1" aria-hidden="true"
    class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full flex items-center justify-center bg-black/60 backdrop-blur-xs">
    <div class="relative w-full max-w-md max-h-full">
        <div class="relative bg-[#202429] rounded-2xl border border-[#343A40] shadow-xl">
            <div class="flex items-center justify-between p-5 border-b border-[#343A40]">
                <div>
                    <span class="text-[10px] font-mono uppercase tracking-widest text-[#F5C842] font-semibold">Entri Rekanan</span>
                    <h3 class="text-base font-editorial font-medium text-[#F5F5F5]">Tambah Supplier Baru</h3>
                </div>
                <button type="button" data-modal-hide="modal-create-supplier" class="text-[#A1A1AA] hover:text-[#F5F5F5] p-1 rounded-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form action="{{ route('suppliers.store') }}" method="POST" class="p-5 space-y-3.5">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-[#F5F5F5] mb-1.5">Nama Perusahaan / Supplier <span class="text-[#F5C842]">*</span></label>
                    <input type="text" name="name" required placeholder="PT / CV / Nama Supplier"
                        class="w-full px-3.5 py-2 text-xs bg-[#181C20] border border-[#343A40] rounded-xl text-[#F5F5F5] placeholder-[#A1A1AA]/60 focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842] focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-[#F5F5F5] mb-1.5">Nomor Telepon / WhatsApp <span class="text-[#F5C842]">*</span></label>
                    <input type="text" name="phone" required placeholder="08xxxxxxxxxx atau 021-xxxxxxx"
                        class="w-full px-3.5 py-2 text-xs bg-[#181C20] border border-[#343A40] rounded-xl text-[#F5F5F5] font-mono placeholder-[#A1A1AA]/60 focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842] focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-[#F5F5F5] mb-1.5">Alamat Email (Opsional)</label>
                    <input type="email" name="email" placeholder="sales@perusahaan.com"
                        class="w-full px-3.5 py-2 text-xs bg-[#181C20] border border-[#343A40] rounded-xl text-[#F5F5F5] placeholder-[#A1A1AA]/60 focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842] focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-[#F5F5F5] mb-1.5">Alamat Kantor / Gudang (Opsional)</label>
                    <textarea name="address" rows="2" placeholder="Alamat lengkap fasilitas atau kantor..."
                        class="w-full px-3.5 py-2 text-xs bg-[#181C20] border border-[#343A40] rounded-xl text-[#F5F5F5] placeholder-[#A1A1AA]/60 focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842] focus:outline-none"></textarea>
                </div>
                <div class="flex items-center justify-end gap-2 pt-3 border-t border-[#343A40]">
                    <button type="button" data-modal-hide="modal-create-supplier" class="px-4 py-2 text-xs font-semibold text-[#A1A1AA] hover:text-[#F5F5F5] hover:bg-[#2A3036] rounded-xl transition">Batal</button>
                    <button type="submit" class="px-4 py-2 text-xs font-semibold text-[#101214] bg-[#F5C842] hover:bg-[#E8B92E] rounded-xl transition cursor-pointer">Simpan Supplier</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Supplier -->
<div id="modal-edit-supplier" tabindex="-1" aria-hidden="true"
    class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full flex items-center justify-center bg-black/60 backdrop-blur-xs">
    <div class="relative w-full max-w-md max-h-full">
        <div class="relative bg-[#202429] rounded-2xl border border-[#343A40] shadow-xl">
            <div class="flex items-center justify-between p-5 border-b border-[#343A40]">
                <div>
                    <span class="text-[10px] font-mono uppercase tracking-widest text-[#F5C842] font-semibold">Pembaruan Data</span>
                    <h3 class="text-base font-editorial font-medium text-[#F5F5F5]">Edit Data Supplier</h3>
                </div>
                <button type="button" onclick="closeEditSupplierModal()" class="text-[#A1A1AA] hover:text-[#F5F5F5] p-1 rounded-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="form-edit-supplier" method="POST" class="p-5 space-y-3.5">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-semibold text-[#F5F5F5] mb-1.5">Nama Perusahaan / Supplier <span class="text-[#F5C842]">*</span></label>
                    <input type="text" name="name" id="edit-supplier-name" required
                        class="w-full px-3.5 py-2 text-xs bg-[#181C20] border border-[#343A40] rounded-xl text-[#F5F5F5] placeholder-[#A1A1AA]/60 focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842] focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-[#F5F5F5] mb-1.5">Nomor Telepon / WhatsApp <span class="text-[#F5C842]">*</span></label>
                    <input type="text" name="phone" id="edit-supplier-phone" required
                        class="w-full px-3.5 py-2 text-xs bg-[#181C20] border border-[#343A40] rounded-xl text-[#F5F5F5] font-mono placeholder-[#A1A1AA]/60 focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842] focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-[#F5F5F5] mb-1.5">Alamat Email (Opsional)</label>
                    <input type="email" name="email" id="edit-supplier-email"
                        class="w-full px-3.5 py-2 text-xs bg-[#181C20] border border-[#343A40] rounded-xl text-[#F5F5F5] placeholder-[#A1A1AA]/60 focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842] focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-[#F5F5F5] mb-1.5">Alamat Kantor / Gudang (Opsional)</label>
                    <textarea name="address" id="edit-supplier-address" rows="2"
                        class="w-full px-3.5 py-2 text-xs bg-[#181C20] border border-[#343A40] rounded-xl text-[#F5F5F5] placeholder-[#A1A1AA]/60 focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842] focus:outline-none"></textarea>
                </div>
                <div class="flex items-center justify-end gap-2 pt-3 border-t border-[#343A40]">
                    <button type="button" onclick="closeEditSupplierModal()" class="px-4 py-2 text-xs font-semibold text-[#A1A1AA] hover:text-[#F5F5F5] hover:bg-[#2A3036] rounded-xl transition">Batal</button>
                    <button type="submit" class="px-4 py-2 text-xs font-semibold text-[#101214] bg-[#F5C842] hover:bg-[#E8B92E] rounded-xl transition cursor-pointer">Perbarui Supplier</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openEditSupplierModal(id, name, email, phone, address) {
        const modal = document.getElementById('modal-edit-supplier');
        const form = document.getElementById('form-edit-supplier');
        form.action = `/suppliers/${id}`;
        document.getElementById('edit-supplier-name').value = name;
        document.getElementById('edit-supplier-email').value = email || '';
        document.getElementById('edit-supplier-phone').value = phone;
        document.getElementById('edit-supplier-address').value = address || '';
        modal.classList.remove('hidden');
    }

    function closeEditSupplierModal() {
        document.getElementById('modal-edit-supplier').classList.add('hidden');
    }
</script>
@endsection