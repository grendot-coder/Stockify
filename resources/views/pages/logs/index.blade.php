@extends('layouts.dashboard')

@section('content')
<div class="space-y-6">

    <!-- Header Audit Log -->
    <div class="bg-[#202429] rounded-2xl border border-[#343A40] p-6 shadow-xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="w-2 h-2 rounded-full bg-[#F5C842]"></span>
                <span class="text-[11px] uppercase tracking-[0.2em] text-[#F5C842] font-semibold">Keamanan & Kepatuhan</span>
            </div>
            <h1 class="font-heading text-2xl sm:text-3xl font-semibold text-[#F5F5F5] tracking-tight">Audit Log Aktivitas Sistem</h1>
            <p class="text-xs text-[#A1A1AA] mt-1">Rekaman jejak aktivitas pengguna, autentikasi, dan mutasi persediaan secara realtime</p>
        </div>
        <div class="text-xs text-[#A1A1AA] bg-[#181C20] px-3.5 py-2 rounded-xl border border-[#343A40] font-mono tabular-nums">
            Total {{ $logs->total() }} Peristiwa Tercatat
        </div>
    </div>

    <!-- Card Tabel & Filter Pencarian -->
    <div class="bg-[#202429] rounded-2xl border border-[#343A40] shadow-xs overflow-hidden">
        
        <!-- Filter Bar -->
        <div class="p-4 sm:p-5 border-b border-[#343A40] bg-[#181C20]">
            <form action="{{ route('logs.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 items-center">
                <!-- Search -->
                <div>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari deskripsi, operator, atau IP..."
                        class="w-full px-3.5 py-2 text-xs bg-[#202429] border border-[#343A40] rounded-xl text-[#F5F5F5] placeholder-[#A1A1AA]/60 focus:outline-none focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842]">
                </div>

                <!-- Action Filter -->
                <div>
                    <select name="action" onchange="this.form.submit()"
                        class="w-full px-3 py-2 text-xs bg-[#202429] border border-[#343A40] rounded-xl text-[#F5F5F5] focus:outline-none focus:border-[#F5C842]">
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
                        class="w-full px-3 py-2 text-xs bg-[#202429] border border-[#343A40] rounded-xl text-[#F5F5F5] focus:outline-none focus:border-[#F5C842]">
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
                        <a href="{{ route('logs.index') }}" class="text-xs text-[#F5C842] hover:underline font-medium">
                            Reset Filter
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Tabel Log Aktivitas -->
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left text-[#A1A1AA] tabular-nums">
                <thead class="text-[11px] text-[#F5F5F5] uppercase tracking-wider bg-[#181C20] border-b border-[#343A40]">
                    <tr>
                        <th class="px-5 py-3.5 font-heading font-semibold">Waktu & Tanggal</th>
                        <th class="px-5 py-3.5 font-heading font-semibold">Operator / Pengguna</th>
                        <th class="px-5 py-3.5 font-heading font-semibold">Jenis Aksi</th>
                        <th class="px-5 py-3.5 font-heading font-semibold">Rincian Perubahan</th>
                        <th class="px-5 py-3.5 font-heading font-semibold text-right">Alamat IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#343A40]">
                    @forelse($logs as $log)
                        <tr class="hover:bg-[#181C20] transition">
                            <!-- Waktu & Tanggal -->
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                <div class="font-bold text-[#F5F5F5]">
                                    {{ $log->created_at->format('d M Y') }}
                                </div>
                                <div class="text-[10px] font-mono text-[#A1A1AA]">
                                    {{ $log->created_at->format('H:i:s') }} ({{ $log->created_at->diffForHumans() }})
                                </div>
                            </td>

                            <!-- Operator / User -->
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                <div class="font-medium text-[#F5F5F5]">
                                    {{ $log->user?->name ?? 'Tamu / Sistem' }}
                                </div>
                                @if($log->user)
                                    <span class="inline-block mt-0.5 px-2 py-0.2 rounded text-[9px] font-semibold tracking-wide border
                                        {{ $log->user->role === 'admin' ? 'bg-[#F5C842]/15 text-[#F5C842] border-[#F5C842]/30' : ($log->user->role === 'manager' ? 'bg-[#181C20] text-[#F5F5F5] border-[#343A40]' : 'bg-[#181C20] text-[#A1A1AA] border-[#343A40]') }}">
                                        {{ ucfirst($log->user->role) }}
                                    </span>
                                @endif
                            </td>

                            <!-- Jenis Aksi -->
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                @php
                                    $actionBadge = match(true) {
                                        str_contains($log->action, 'OPNAME') => 'bg-[#F5C842]/15 text-[#F5C842] border-[#F5C842]/40',
                                        str_contains($log->action, 'IN') || str_contains($log->action, 'CREATE') || str_contains($log->action, 'CONFIRM') => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                                        str_contains($log->action, 'OUT') || str_contains($log->action, 'DELETE') || str_contains($log->action, 'REJECT') => 'bg-rose-50 text-rose-800 border-rose-200',
                                        str_contains($log->action, 'UPDATE') => 'bg-amber-50 text-amber-800 border-amber-200',
                                        default => 'bg-[#181C20] text-[#F5F5F5] border-[#343A40]',
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold border {{ $actionBadge }}">
                                    {{ $log->action }}
                                </span>
                            </td>

                            <!-- Rincian Deskripsi -->
                            <td class="px-5 py-3.5 max-w-md">
                                <p class="text-xs text-[#F5F5F5] leading-relaxed break-words">
                                    {{ $log->description }}
                                </p>
                            </td>

                            <!-- Alamat IP -->
                            <td class="px-5 py-3.5 text-right font-mono text-[11px] text-[#A1A1AA] whitespace-nowrap">
                                {{ $log->ip_address ?: '127.0.0.1' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center text-[#A1A1AA]">
                                <p class="font-medium text-[#F5F5F5] text-sm">Tidak ada log aktivitas ditemukan</p>
                                <p class="text-xs text-[#A1A1AA] mt-1">Coba sesuaikan parameter pencarian atau filter.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($logs->hasPages())
            <div class="p-4 border-t border-[#343A40] bg-[#181C20]">
                {{ $logs->links() }}
            </div>
        @endif

    </div>

</div>
@endsection
