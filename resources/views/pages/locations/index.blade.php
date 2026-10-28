@extends('layouts.dashboard')

@section('content')
<div class="space-y-6">

    <div class="bg-[#202429] rounded-2xl border border-[#343A40] p-6 shadow-xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="w-2 h-2 rounded-full bg-[#F5C842]"></span>
                <span class="text-[11px] uppercase tracking-[0.2em] text-[#F5C842] font-semibold">Pemetaan Penyimpanan</span>
            </div>
            <h1 class="font-heading text-2xl sm:text-3xl font-semibold text-[#F5F5F5] tracking-tight">Lokasi Rak Gudang</h1>
            <p class="text-xs text-[#A1A1AA] mt-1">Pemetaan fisik gudang, okupansi kapasitas per rak, dan visualisasi dot matrix penyimpanan</p>
        </div>
        <button type="button" data-modal-target="modal-create-location" data-modal-toggle="modal-create-location"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#F5C842] hover:bg-[#E8B92E] text-[#101214] text-xs font-semibold rounded-xl shadow-xs transition duration-150 cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span>Tambah Rak Baru</span>
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

    <div class="bg-[#202429] rounded-2xl border border-[#343A40] p-6 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-5 border-b border-[#343A40]/80">
            <div>
                <span class="text-[10px] font-mono uppercase tracking-widest text-[#A1A1AA]">STORAGE MATRIX · 1 DOT = 1 RAK GUDANG</span>
                <div class="text-lg font-heading font-semibold text-[#F5F5F5] mt-0.5">
                    Kapasitas Terpakai: <span class="text-[#F5C842] font-mono font-bold">{{ $stats['overall_percentage'] }}%</span>
                    <span class="text-xs font-normal text-[#A1A1AA] ml-1.5 font-sans">({{ number_format($stats['total_stock']) }} / {{ number_format($stats['total_capacity']) }} Total Unit)</span>
                </div>
            </div>

            <!-- Quick Counter Pills -->
            <div class="flex flex-wrap items-center gap-2 text-[11px] font-mono">
                <span class="px-2.5 py-1 rounded-lg bg-[#181C20] border border-[#343A40] text-[#F5F5F5]">
                    Total: <strong class="text-[#F5C842]">{{ $stats['total_racks'] }}</strong> Rak
                </span>
                <span class="px-2.5 py-1 rounded-lg bg-[#343A40]/50 border border-[#343A40] text-[#A1A1AA]">
                    Kosong: <strong>{{ $stats['empty_count'] }}</strong>
                </span>
                <span class="px-2.5 py-1 rounded-lg bg-[#F5C842]/10 border border-[#F5C842]/30 text-[#F5C842]">
                    Normal: <strong>{{ $stats['normal_count'] }}</strong>
                </span>
                <span class="px-2.5 py-1 rounded-lg bg-[#FB923C]/10 border border-[#FB923C]/30 text-[#FB923C]">
                    Hampir Penuh: <strong>{{ $stats['almost_full_count'] }}</strong>
                </span>
                <span class="px-2.5 py-1 rounded-lg bg-[#F43F5E]/10 border border-[#F43F5E]/30 text-[#F43F5E]">
                    Penuh: <strong>{{ $stats['full_count'] }}</strong>
                </span>
            </div>
        </div>

        <!-- Dot Matrix Canvas -->
        <div class="mt-5 p-5 bg-[#101214] border border-[#272B30] rounded-xl">
            @if($allRacks->isEmpty())
            <div class="py-8 text-center">
                <p class="text-xs text-[#A1A1AA]">Belum ada rak terdaftar. Klik <strong>"Tambah Rak Baru"</strong> untuk melihat visualisasi dot matrix.</p>
            </div>
            @else
            <div class="flex flex-wrap gap-2.5 items-center">
                @foreach($allRacks as $rack)
                <div class="relative group cursor-pointer" onclick="filterByRack('{{ $rack->name }}')">
                    <!-- Dot Indicator -->
                    <div class="w-3.5 h-3.5 rounded-full transition-transform duration-150 transform group-hover:scale-150 {{ $rack->status_color }}"></div>

                    <!-- Industrial Floating Tooltip -->
                    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2.5 hidden group-hover:flex flex-col items-center z-50 pointer-events-none whitespace-nowrap">
                        <div class="bg-[#181C20] border border-[#495057] text-[#F5F5F5] rounded-xl shadow-2xl p-3 text-[11px] font-mono leading-tight">
                            <div class="flex items-center justify-between gap-3">
                                <span class="text-[#F5C842] font-bold text-xs">{{ $rack->name }}</span>
                                <span class="text-[10px] text-[#A1A1AA]">{{ $rack->zone ?: 'Zona Umum' }}</span>
                            </div>
                            <div class="mt-2 pt-1.5 border-t border-[#343A40] space-y-1">
                                <div class="flex items-center justify-between gap-4 text-xs">
                                    <span class="text-[#A1A1AA]">Keterisian:</span>
                                    <strong class="text-[#F5F5F5]">{{ $rack->current_stock }} / {{ $rack->capacity }} Unit</strong>
                                </div>
                                <div class="flex items-center justify-between gap-4 text-[10px]">
                                    <span class="text-[#A1A1AA]">Status:</span>
                                    <span class="font-bold text-[#F5C842]">{{ $rack->status_label }} ({{ $rack->occupancy_percentage }}%)</span>
                                </div>
                            </div>
                            <div class="mt-2 text-[9px] text-[#A1A1AA]/60 text-center">Klik untuk filter tabel</div>
                        </div>
                        <div class="w-2 h-2 bg-[#181C20] border-r border-b border-[#495057] transform rotate-45 -mt-1"></div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        <!-- Legend (Meniru visualisasi referensi SD Card / Block Map) -->
        <div class="mt-4 pt-3 flex flex-wrap items-center gap-5 text-[11px] font-mono text-[#A1A1AA]">
            <span class="flex items-center gap-1.5">
                <span class="w-2.5 h-2.5 rounded-full bg-[#343A40] border border-[#495057]/50 inline-block"></span>
                <span>KOSONG (0%)</span>
            </span>
            <span class="flex items-center gap-1.5">
                <span class="w-2.5 h-2.5 rounded-full bg-[#F5C842] shadow-[0_0_6px_rgba(245,200,66,0.5)] inline-block"></span>
                <span>NORMAL (&lt;75%)</span>
            </span>
            <span class="flex items-center gap-1.5">
                <span class="w-2.5 h-2.5 rounded-full bg-[#FB923C] shadow-[0_0_6px_rgba(251,146,60,0.5)] inline-block"></span>
                <span>HAMPIR PENUH (75-94%)</span>
            </span>
            <span class="flex items-center gap-1.5">
                <span class="w-2.5 h-2.5 rounded-full bg-[#F43F5E] shadow-[0_0_6px_rgba(244,63,94,0.6)] inline-block"></span>
                <span>PENUH (≥95%)</span>
            </span>
        </div>
    </div>

    <!-- Card Tabel & Pencarian -->
    <div class="bg-[#202429] rounded-2xl border border-[#343A40] shadow-xs overflow-hidden">

        <!-- Filter Pencarian -->
        <div class="p-4 border-b border-[#343A40] bg-[#181C20] flex flex-col sm:flex-row items-center justify-between gap-3">
            <form action="{{ route('locations.index') }}" method="GET" class="w-full sm:w-80" id="search-form">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-[#A1A1AA]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" name="search" id="search-input" value="{{ $search }}" placeholder="Cari Rack-0X atau zona..."
                        class="w-full pl-9 pr-3 py-2 text-xs bg-[#202429] border border-[#343A40] rounded-xl text-[#F5F5F5] placeholder-[#A1A1AA]/60 focus:outline-none focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842]">
                </div>
            </form>
            @if($search)
            <a href="{{ route('locations.index') }}" class="text-xs text-[#F5C842] hover:underline">Reset Filter</a>
            @endif
            <span class="text-xs text-[#A1A1AA]">Menampilkan: <strong class="text-[#F5F5F5] font-mono">{{ $locations->total() }}</strong> Rak</span>
        </div>

        <!-- Tabel Lokasi Rak -->
        <div class="overflow-x-auto" tabindex="0" role="region" aria-label="Tabel Pemetaan Lokasi Rak Gudang">
            <table class="w-full text-xs text-left text-[#A1A1AA]">
                <thead class="text-[11px] text-[#F5F5F5] uppercase tracking-wider bg-[#181C20] border-b border-[#343A40] sticky top-0 z-10">
                    <tr>
                        <th scope="col" class="px-5 py-3.5 w-12 text-center font-heading font-semibold">No</th>
                        <th scope="col" class="px-5 py-3.5 font-heading font-semibold">Nama Rak</th>
                        <th scope="col" class="px-5 py-3.5 font-heading font-semibold">Kapasitas & Okupansi</th>
                        <th scope="col" class="px-5 py-3.5 font-heading font-semibold">Status</th>
                        <th scope="col" class="px-5 py-3.5 font-heading font-semibold">Zona / Area</th>
                        <th scope="col" class="px-5 py-3.5 font-heading font-semibold">Deskripsi</th>
                        <th scope="col" class="px-5 py-3.5 text-center font-heading font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#343A40]">
                    @forelse($locations as $index => $loc)
                    <tr class="hover:bg-[#2A3036] transition" id="row-{{ $loc->name }}">
                        <td class="px-5 py-3.5 text-center text-[#A1A1AA] font-mono">
                            {{ $locations->firstItem() + $index }}
                        </td>
                        <td class="px-5 py-3.5 font-mono font-bold text-[#F5C842] text-sm">
                            {{ $loc->name }}
                        </td>
                        <td class="px-5 py-3.5 w-64">
                            <div class="flex items-center justify-between text-[11px] font-mono mb-1">
                                <span class="text-[#F5F5F5] font-semibold">{{ $loc->current_stock }} / {{ $loc->capacity }} Unit</span>
                                <span class="text-[#A1A1AA]">{{ $loc->occupancy_percentage }}%</span>
                            </div>
                            <!-- Mini Progress Bar -->
                            <div class="w-full bg-[#181C20] rounded-full h-1.5 border border-[#343A40] overflow-hidden">
                                <div class="h-full rounded-full transition-all duration-300 {{ $loc->status === 'full' ? 'bg-[#F43F5E]' : ($loc->status === 'almost_full' ? 'bg-[#FB923C]' : ($loc->status === 'normal' ? 'bg-[#F5C842]' : 'bg-transparent')) }}"
                                    style="width: {{ $loc->occupancy_percentage }}%"></div>
                            </div>
                        </td>
                        <td class="px-5 py-3.5">
                            @if($loc->status === 'empty')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-mono font-medium bg-[#343A40] text-[#A1A1AA]">
                                Kosong
                            </span>
                            @elseif($loc->status === 'normal')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-mono font-medium bg-[#F5C842]/15 text-[#F5C842] border border-[#F5C842]/30">
                                Tersedia
                            </span>
                            @elseif($loc->status === 'almost_full')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-mono font-medium bg-[#FB923C]/15 text-[#FB923C] border border-[#FB923C]/30">
                                Hampir Penuh
                            </span>
                            @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-mono font-medium bg-[#F43F5E]/15 text-[#F43F5E] border border-[#F43F5E]/30">
                                Penuh
                            </span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5">
                            @if($loc->zone)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-mono font-medium bg-[#181C20] border border-[#343A40] text-[#F5F5F5]">
                                {{ $loc->zone }}
                            </span>
                            @else
                            <span class="text-[#A1A1AA]/50">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-[#A1A1AA] max-w-xs truncate">
                            {{ $loc->description ?: '—' }}
                        </td>
                        <td class="px-5 py-3.5 text-right whitespace-nowrap">
                            <div class="inline-flex items-center gap-1.5 justify-end">
                                <button type="button"
                                    onclick="openEditLocationModal({{ $loc->id }}, '{{ addslashes($loc->name) }}', {{ $loc->capacity }}, '{{ addslashes($loc->zone ?? '') }}', '{{ addslashes($loc->description ?? '') }}')"
                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold text-[#F5C842] bg-[#F5C842]/10 hover:bg-[#F5C842] hover:text-[#101214] border border-[#F5C842]/30 transition cursor-pointer">
                                    Edit
                                </button>
                                <form action="{{ route('locations.destroy', $loc->id) }}" method="POST" class="inline-block"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus rak {{ addslashes($loc->name) }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold text-rose-400 bg-rose-500/10 hover:bg-rose-500 hover:text-white border border-rose-500/30 transition cursor-pointer">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-10 text-center text-[#A1A1AA]">
                            Tidak ada data lokasi rak yang ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($locations->hasPages())
        <div class="p-4 border-t border-[#343A40] bg-[#181C20]">
            {{ $locations->links() }}
        </div>
        @endif
    </div>

</div>

<!-- Modal Tambah Lokasi Rak -->
<div id="modal-create-location" tabindex="-1" aria-hidden="true"
    class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full flex items-center justify-center bg-black/60 backdrop-blur-xs">
    <div class="relative w-full max-w-md max-h-full">
        <div class="relative bg-[#202429] rounded-2xl border border-[#343A40] shadow-xl">
            <div class="flex items-center justify-between p-5 border-b border-[#343A40]">
                <div>
                    <span class="text-[10px] font-mono uppercase tracking-widest text-[#F5C842] font-semibold">Entri Rak Baru</span>
                    <h3 class="text-base font-heading font-semibold text-[#F5F5F5]">Tambah Rak Gudang</h3>
                </div>
                <button type="button" data-modal-hide="modal-create-location" class="text-[#A1A1AA] hover:text-[#F5F5F5] p-1 rounded-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form action="{{ route('locations.store') }}" method="POST" class="p-5 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-[#F5F5F5] mb-1.5">Nama Rak <span class="text-[#F5C842]">*</span></label>
                    <input type="text" name="name" required placeholder="Contoh: Rack-01, Rack-02"
                        class="w-full px-3.5 py-2 text-xs font-mono bg-[#181C20] border border-[#343A40] rounded-xl text-[#F5F5F5] placeholder-[#A1A1AA]/60 focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842] focus:outline-none">
                    <span class="text-[10px] text-[#A1A1AA] mt-1 block">Gunakan penamaan format standar "Rack-0X". Cukup ketik angka (misal "1") maka otomatis diformat menjadi "Rack-01".</span>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-[#F5F5F5] mb-1.5">Kapasitas Maksimum (Unit) <span class="text-[#F5C842]">*</span></label>
                    <input type="number" name="capacity" value="100" min="1" required
                        class="w-full px-3.5 py-2 text-xs font-mono bg-[#181C20] border border-[#343A40] rounded-xl text-[#F5F5F5] placeholder-[#A1A1AA]/60 focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842] focus:outline-none">
                    <span class="text-[10px] text-[#A1A1AA] mt-1 block">Batas muat total barang pada rak ini.</span>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-[#F5F5F5] mb-1.5">Zona / Area (Opsional)</label>
                    <input type="text" name="zone" placeholder="Contoh: Zona A, Lantai 1"
                        class="w-full px-3.5 py-2 text-xs bg-[#181C20] border border-[#343A40] rounded-xl text-[#F5F5F5] placeholder-[#A1A1AA]/60 focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842] focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-[#F5F5F5] mb-1.5">Deskripsi (Opsional)</label>
                    <textarea name="description" rows="2" placeholder="Catatan spesifikasi rak..."
                        class="w-full px-3.5 py-2 text-xs bg-[#181C20] border border-[#343A40] rounded-xl text-[#F5F5F5] placeholder-[#A1A1AA]/60 focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842] focus:outline-none"></textarea>
                </div>
                <div class="flex items-center justify-end gap-2 pt-3 border-t border-[#343A40]">
                    <button type="button" data-modal-hide="modal-create-location" class="px-4 py-2 text-xs font-semibold text-[#A1A1AA] hover:text-[#F5F5F5] hover:bg-[#2A3036] rounded-xl transition">Batal</button>
                    <button type="submit" class="px-4 py-2 text-xs font-semibold text-[#101214] bg-[#F5C842] hover:bg-[#E8B92E] rounded-xl transition cursor-pointer">Simpan Rak</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Lokasi Rak -->
<div id="modal-edit-location" tabindex="-1" aria-hidden="true"
    class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full flex items-center justify-center bg-black/60 backdrop-blur-xs">
    <div class="relative w-full max-w-md max-h-full">
        <div class="relative bg-[#202429] rounded-2xl border border-[#343A40] shadow-xl">
            <div class="flex items-center justify-between p-5 border-b border-[#343A40]">
                <div>
                    <span class="text-[10px] font-mono uppercase tracking-widest text-[#F5C842] font-semibold">Pembaruan</span>
                    <h3 class="text-base font-heading font-semibold text-[#F5F5F5]">Edit Rak Gudang</h3>
                </div>
                <button type="button" onclick="closeEditLocationModal()" class="text-[#A1A1AA] hover:text-[#F5F5F5] p-1 rounded-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="form-edit-location" method="POST" class="p-5 space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-semibold text-[#F5F5F5] mb-1.5">Nama Rak <span class="text-[#F5C842]">*</span></label>
                    <input type="text" name="name" id="edit-location-name" required
                        class="w-full px-3.5 py-2 text-xs font-mono bg-[#181C20] border border-[#343A40] rounded-xl text-[#F5F5F5] placeholder-[#A1A1AA]/60 focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842] focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-[#F5F5F5] mb-1.5">Kapasitas Maksimum (Unit) <span class="text-[#F5C842]">*</span></label>
                    <input type="number" name="capacity" id="edit-location-capacity" min="1" required
                        class="w-full px-3.5 py-2 text-xs font-mono bg-[#181C20] border border-[#343A40] rounded-xl text-[#F5F5F5] placeholder-[#A1A1AA]/60 focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842] focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-[#F5F5F5] mb-1.5">Zona / Area (Opsional)</label>
                    <input type="text" name="zone" id="edit-location-zone"
                        class="w-full px-3.5 py-2 text-xs bg-[#181C20] border border-[#343A40] rounded-xl text-[#F5F5F5] placeholder-[#A1A1AA]/60 focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842] focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-[#F5F5F5] mb-1.5">Deskripsi (Opsional)</label>
                    <textarea name="description" id="edit-location-description" rows="2"
                        class="w-full px-3.5 py-2 text-xs bg-[#181C20] border border-[#343A40] rounded-xl text-[#F5F5F5] placeholder-[#A1A1AA]/60 focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842] focus:outline-none"></textarea>
                </div>
                <div class="flex items-center justify-end gap-2 pt-3 border-t border-[#343A40]">
                    <button type="button" onclick="closeEditLocationModal()" class="px-4 py-2 text-xs font-semibold text-[#A1A1AA] hover:text-[#F5F5F5] hover:bg-[#2A3036] rounded-xl transition">Batal</button>
                    <button type="submit" class="px-4 py-2 text-xs font-semibold text-[#101214] bg-[#F5C842] hover:bg-[#E8B92E] rounded-xl transition cursor-pointer">Perbarui Rak</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function filterByRack(rackName) {
        const input = document.getElementById('search-input');
        if (input) {
            input.value = rackName;
            document.getElementById('search-form').submit();
        }
    }

    function openEditLocationModal(id, name, capacity, zone, description) {
        const modal = document.getElementById('modal-edit-location');
        const form = document.getElementById('form-edit-location');
        form.action = `/locations/${id}`;
        document.getElementById('edit-location-name').value = name;
        document.getElementById('edit-location-capacity').value = capacity || 100;
        document.getElementById('edit-location-zone').value = zone || '';
        document.getElementById('edit-location-description').value = description || '';
        modal.classList.remove('hidden');
    }

    function closeEditLocationModal() {
        document.getElementById('modal-edit-location').classList.add('hidden');
    }
</script>
@endsection