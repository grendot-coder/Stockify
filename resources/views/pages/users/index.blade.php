@extends('layouts.dashboard')

@section('content')
<div class="space-y-5">

    <!-- Header & Tombol Tambah Pengguna -->
    <div class="bg-[#FFFFFF] rounded-2xl border border-[#E7E0DA] p-6 shadow-xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="w-2 h-2 rounded-full bg-[#BD6745]"></span>
                <span class="text-[11px] font-mono tracking-widest uppercase text-[#BD6745] font-semibold">Kontrol Otorisasi Pengguna</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-editorial font-medium text-[#292524] tracking-tight">Manajemen Akun Pengguna</h1>
            <p class="text-xs text-[#78716C] mt-1">Kelola data login, hak akses otorisasi role, dan status aktif staf pergudangan</p>
        </div>
        <a href="{{ route('users.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#A34E30] hover:bg-[#8A3F25] text-white text-xs font-semibold rounded-xl shadow-xs transition duration-150 cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span>Tambah Pengguna Baru</span>
        </a>
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

    <!-- Card Tabel & Pencarian -->
    <div class="bg-[#FFFFFF] rounded-2xl border border-[#E7E0DA] shadow-xs overflow-hidden">
        
        <!-- Filter Bar -->
        <div class="p-4 border-b border-[#E7E0DA] bg-[#FAF7F4] flex flex-col md:flex-row items-center justify-between gap-3">
            <form action="{{ route('users.index') }}" method="GET" class="w-full md:w-auto flex flex-col sm:flex-row items-center gap-2 flex-1 max-w-xl">
                <div class="relative w-full sm:w-72">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-[#78716C]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama, email, telepon..."
                        class="w-full pl-9 pr-3 py-2 text-xs bg-[#FFFFFF] border border-[#E7E0DA] rounded-xl text-[#292524] placeholder-[#78716C]/60 focus:outline-none focus:border-[#BD6745] focus:ring-1 focus:ring-[#BD6745]">
                </div>

                <select name="role" onchange="this.form.submit()"
                    class="w-full sm:w-44 py-2 px-3 text-xs bg-[#FFFFFF] border border-[#E7E0DA] rounded-xl text-[#292524] focus:outline-none focus:border-[#BD6745] focus:ring-1 focus:ring-[#BD6745]">
                    <option value="">Semua Peran (Role)</option>
                    <option value="admin" {{ $role === 'admin' ? 'selected' : '' }}>Admin Sistem</option>
                    <option value="manager" {{ $role === 'manager' ? 'selected' : '' }}>Manajer Gudang</option>
                    <option value="staff" {{ $role === 'staff' ? 'selected' : '' }}>Staff Gudang</option>
                </select>

                @if($search || $role)
                    <a href="{{ route('users.index') }}" class="text-xs text-[#78716C] hover:text-[#BD6745] px-2 py-1 underline whitespace-nowrap">
                        Reset Filter
                    </a>
                @endif
            </form>
            <span class="text-xs text-[#78716C]">Total: <strong class="text-[#292524] font-mono">{{ $users->total() }}</strong> Akun</span>
        </div>

        <!-- Tabel Pengguna -->
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left text-[#78716C]">
                <thead class="text-[11px] text-[#292524] uppercase tracking-wider bg-[#FAF7F4] border-b border-[#E7E0DA]">
                    <tr>
                        <th class="px-5 py-3 font-heading font-semibold">Nama & Email</th>
                        <th class="px-5 py-3 font-heading font-semibold">Peran (Role)</th>
                        <th class="px-5 py-3 font-heading font-semibold">No. Telepon</th>
                        <th class="px-5 py-3 font-heading font-semibold text-center">Status Akun</th>
                        <th class="px-5 py-3 font-heading font-semibold">Terdaftar</th>
                        <th class="px-5 py-3 text-center font-heading font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E7E0DA]">
                    @forelse($users as $u)
                        <tr class="hover:bg-[#FAF7F4] transition">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-[#F5E6DE] text-[#A34E30] flex items-center justify-center font-bold text-xs">
                                        {{ strtoupper(substr($u->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-[#292524] flex items-center gap-1.5">
                                            {{ $u->name }}
                                            @if($u->id === auth()->id())
                                                <span class="px-1.5 py-0.2 text-[9px] font-semibold bg-stone-100 text-[#78716C] rounded border border-stone-200">Anda</span>
                                            @endif
                                        </div>
                                        <div class="text-[11px] font-mono text-[#78716C]">{{ $u->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3.5">
                                @if($u->role === 'admin')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-[#F5E6DE] text-[#A34E30] border border-[#BD6745]/30">
                                        Administrator
                                    </span>
                                @elseif($u->role === 'manager')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                        Manajer Gudang
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Staff Lapangan
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 font-mono text-xs text-[#292524]">
                                {{ $u->phone ?: '-' }}
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                @if($u->is_active)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-red-50 text-red-700 border border-red-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-xs text-[#78716C] font-mono">
                                {{ $u->created_at->translatedFormat('d M Y') }}
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('users.edit', $u->id) }}"
                                        class="p-1.5 text-[#78716C] hover:text-[#BD6745] hover:bg-[#FAF7F4] rounded-lg transition" title="Edit Akun">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>

                                    @if($u->id !== auth()->id())
                                        <form action="{{ route('users.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ $u->name }}? Tindakan ini tidak dapat dibatalkan.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-[#78716C] hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Hapus Akun">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-[#78716C]">
                                <svg class="w-8 h-8 text-[#BD6745]/40 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                Tidak ada data pengguna yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="p-4 border-t border-[#E7E0DA] bg-[#FAF7F4]">
                {{ $users->links() }}
            </div>
        @endif

    </div>

</div>
@endsection
