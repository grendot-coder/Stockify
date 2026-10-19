@extends('layouts.dashboard')

@section('content')
<div class="space-y-6">

    <!-- Header Audit Log -->
    <div class="bg-[#FFFFFF] rounded-2xl border border-[#E7E0DA] p-6 shadow-xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="w-2 h-2 rounded-full bg-[#BD6745]"></span>
                <span class="text-[11px] uppercase tracking-[0.2em] text-[#BD6745] font-semibold">Keamanan & Kepatuhan</span>
            </div>
            <h1 class="font-heading text-2xl sm:text-3xl font-semibold text-[#292524] tracking-tight">Audit Log Aktivitas Sistem</h1>
            <p class="text-xs text-[#78716C] mt-1">Rekaman jejak aktivitas pengguna, autentikasi, dan mutasi persediaan secara realtime</p>
        </div>
        <div class="text-xs text-[#78716C] bg-[#FAF7F4] px-3.5 py-2 rounded-xl border border-[#E7E0DA] font-mono tabular-nums">
            Total {{ $logs->total() }} Peristiwa Tercatat
        </div>
    </div>

    <!-- Card Tabel & Filter Pencarian -->
    <div class="bg-[#FFFFFF] rounded-2xl border border-[#E7E0DA] shadow-xs overflow-hidden">
        
        <!-- Filter Bar -->
        <div class="p-4 sm:p-5 border-b border-[#E7E0DA] bg-[#FAF7F4]">
            <form action="{{ route('logs.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 items-center">
                <!-- Search -->
                <div>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari deskripsi, operator, atau IP..."
                        class="w-full px-3.5 py-2 text-xs bg-[#FFFFFF] border border-[#E7E0DA] rounded-xl text-[#292524] placeholder-[#78716C]/60 focus:outline-none focus:border-[#BD6745] focus:ring-1 focus:ring-[#BD6745]">
                </div>

                <!-- Action Filter -->
                <div>
                    <select name="action" onchange="this.form.submit()"
                        class="w-full px-3 py-2 text-xs bg-[#FFFFFF] border border-[#E7E0DA] rounded-xl text-[#292524] focus:outline-none focus:border-[#BD6745]">
                        <option value="">Semua Jenis Aksi</option>
                        @foreach($actions as $act)
                            <option value="{{ $act }}" {{ $action === $act ? 'selected' : '' }}>
                                {{ $act }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- User Filter -->
                <div>
                    <select name="user_id" onchange="this.form.submit()"
                        class="w-full px-3 py-2 text-xs bg-[#FFFFFF] border border-[#E7E0DA] rounded-xl text-[#292524] focus:outline-none focus:border-[#BD6745]">
                        <option value="">Semua Operator</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" {{ $userId == $u->id ? 'selected' : '' }}>
                                {{ $u->name }} ({{ ucfirst($u->role) }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Reset Button -->
                <div class="flex items-center justify-end">
                    @if($search || $action || $userId)
                        <a href="{{ route('logs.index') }}" class="text-xs text-[#BD6745] hover:underline font-medium">
                            Reset Filter
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Tabel Log Aktivitas -->
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left text-[#78716C] tabular-nums">
                <thead class="text-[11px] text-[#292524] uppercase tracking-wider bg-[#FAF7F4] border-b border-[#E7E0DA]">
                    <tr>
                        <th class="px-5 py-3.5 font-heading font-semibold">Waktu & Tanggal</th>
                        <th class="px-5 py-3.5 font-heading font-semibold">Operator / Pengguna</th>
                        <th class="px-5 py-3.5 font-heading font-semibold">Jenis Aksi</th>
                        <th class="px-5 py-3.5 font-heading font-semibold">Rincian Perubahan</th>
                        <th class="px-5 py-3.5 font-heading font-semibold text-right">Alamat IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E7E0DA]">
                    @forelse($logs as $log)
                        <tr class="hover:bg-[#FAF7F4] transition">
                            <!-- Waktu & Tanggal -->
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                <div class="font-bold text-[#292524]">
                                    {{ $log->created_at->format('d M Y') }}
                                </div>
                                <div class="text-[10px] font-mono text-[#78716C]">
                                    {{ $log->created_at->format('H:i:s') }} ({{ $log->created_at->diffForHumans() }})
                                </div>
                            </td>

                            <!-- Operator / User -->
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                <div class="font-medium text-[#292524]">
                                    {{ $log->user?->name ?? 'Tamu / Sistem' }}
                                </div>
                                @if($log->user)
                                    <span class="inline-block mt-0.5 px-2 py-0.2 rounded text-[9px] font-semibold tracking-wide border
                                        {{ $log->user->role === 'admin' ? 'bg-[#F5E6DE] text-[#BD6745] border-[#BD6745]/30' : ($log->user->role === 'manager' ? 'bg-[#FAF7F4] text-[#292524] border-[#E7E0DA]' : 'bg-[#FAF7F4] text-[#78716C] border-[#E7E0DA]') }}">
                                        {{ ucfirst($log->user->role) }}
                                    </span>
                                @endif
                            </td>

                            <!-- Jenis Aksi -->
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                @php
                                    $actionBadge = match(true) {
                                        str_contains($log->action, 'OPNAME') => 'bg-[#F5E6DE] text-[#BD6745] border-[#BD6745]/40',
                                        str_contains($log->action, 'IN') || str_contains($log->action, 'CREATE') || str_contains($log->action, 'CONFIRM') => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                                        str_contains($log->action, 'OUT') || str_contains($log->action, 'DELETE') || str_contains($log->action, 'REJECT') => 'bg-rose-50 text-rose-800 border-rose-200',
                                        str_contains($log->action, 'UPDATE') => 'bg-amber-50 text-amber-800 border-amber-200',
                                        default => 'bg-[#FAF7F4] text-[#292524] border-[#E7E0DA]',
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold border {{ $actionBadge }}">
                                    {{ $log->action }}
                                </span>
                            </td>

                            <!-- Rincian Deskripsi -->
                            <td class="px-5 py-3.5 max-w-md">
                                <p class="text-xs text-[#292524] leading-relaxed break-words">
                                    {{ $log->description }}
                                </p>
                            </td>

                            <!-- Alamat IP -->
                            <td class="px-5 py-3.5 text-right font-mono text-[11px] text-[#78716C] whitespace-nowrap">
                                {{ $log->ip_address ?: '127.0.0.1' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center text-[#78716C]">
                                <p class="font-medium text-[#292524] text-sm">Tidak ada log aktivitas ditemukan</p>
                                <p class="text-xs text-[#78716C] mt-1">Coba sesuaikan parameter pencarian atau filter.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($logs->hasPages())
            <div class="p-4 border-t border-[#E7E0DA] bg-[#FAF7F4]">
                {{ $logs->links() }}
            </div>
        @endif

    </div>

</div>
@endsection
