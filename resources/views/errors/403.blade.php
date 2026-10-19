<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 - Akses Terbatas | Stockify</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600&family=IBM+Plex+Sans:wght@400;500;600&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#101214] text-[#F5F5F5] min-h-screen flex items-center justify-center p-6 selection:bg-[#F5C842] selection:text-[#101214]">
    <div class="text-center max-w-md bg-[#202429] border border-[#343A40] rounded-2xl p-8 shadow-sm space-y-5">
        <div class="w-16 h-16 bg-[#F5C842]/15 border border-[#F5C842]/30 text-[#F5C842] rounded-2xl mx-auto flex items-center justify-center shadow-sm">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
            </svg>
        </div>
        <div>
            <span class="text-[11px] font-mono tracking-widest uppercase text-[#F5C842] font-semibold">Akses Dibatasi</span>
            <h1 class="text-2xl font-serif font-medium text-[#F5F5F5] mt-1">403 — Izin Terbatas</h1>
            <p class="text-xs text-[#A1A1AA] mt-2 leading-relaxed">
                {{ $exception->getMessage() ?: 'Akun Anda tidak memiliki kewenangan otorisasi untuk mengakses berkas ini. Hubungi administrator sistem jika Anda membutuhkan akses tambahan.' }}
            </p>
        </div>
        <div class="pt-2">
            <a href="{{ route('dashboard') }}"
                class="inline-flex items-center justify-center px-5 py-2.5 text-xs font-bold text-[#101214] bg-[#F5C842] hover:bg-[#E8B92E] rounded-lg transition duration-150 shadow-sm">
                Kembali ke Dashboard
            </a>
        </div>
    </div>
</body>
</html>
