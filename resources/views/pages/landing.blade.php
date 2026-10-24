@php
$landingAppName = \App\Models\Setting::get('app_name', 'Stockify');
$landingAppTagline = \App\Models\Setting::get('app_tagline', 'Warehouse Management System');
$landingAppLogo = \App\Models\Setting::get('app_logo');
$landingCompanyPhone = \App\Models\Setting::get('company_phone');
$landingCompanyEmail = \App\Models\Setting::get('company_email');
$landingCompanyAddress = \App\Models\Setting::get('company_address');
@endphp
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $landingAppName }} — {{ $landingAppTagline }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600&family=IBM+Plex+Sans:wght@400;500;600&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'IBM Plex Sans', sans-serif;
            background-color: #101214;
            color: #F5F5F5;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        .font-heading {
            font-family: 'Space Grotesk', sans-serif;
        }

        .font-mono,
        [data-tabular] {
            font-family: 'IBM Plex Mono', monospace;
            font-variant-numeric: tabular-nums;
        }
    </style>
</head>

<body class="bg-[#101214] text-[#F5F5F5] antialiased selection:bg-[#F5C842] selection:text-[#101214]">

    <header class="sticky top-0 z-50 backdrop-blur-md bg-[#181C20]/90 border-b border-[#343A40]">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">

            <a href="/" class="flex items-center gap-3 group">
                @if($landingAppLogo && file_exists(public_path('storage/' . $landingAppLogo)))
                <img src="{{ asset('storage/' . $landingAppLogo) }}" alt="{{ $landingAppName }}" class="w-10 h-10 rounded-xl object-contain bg-[#202429] border border-[#343A40] p-1">
                @else
                <div class="w-10 h-10 rounded-xl bg-[#F5C842] border border-[#F5C842]/40 flex items-center justify-center transition group-hover:bg-[#E8B92E]">
                    <span class="text-[#101214] font-heading font-bold text-base tracking-wider transition">S</span>
                </div>
                @endif
                <div class="flex flex-col">
                    <span class="font-heading text-xl font-bold tracking-tight text-[#F5F5F5]">{{ $landingAppName }}</span>
                    <span class="text-[9px] uppercase tracking-[0.2em] text-[#A1A1AA] font-semibold">{{ $landingAppTagline }}</span>
                </div>
            </a>

            <nav class="hidden md:flex items-center gap-7 text-xs font-semibold text-[#A1A1AA]">
                <a href="#simulator" class="hover:text-[#F5C842] transition">Simulator Stok</a>
                <a href="#fitur" class="hover:text-[#F5C842] transition">Fitur Inti</a>
                <a href="#arsitektur" class="hover:text-[#F5C842] transition">Alur Logistik</a>
                <a href="#akses-demo" class="hover:text-[#F5C842] transition">Akses Demo</a>
            </nav>

            <div class="flex items-center gap-3">
                @auth
                <a href="{{ route('dashboard') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#F5C842] text-[#101214] text-xs font-heading font-semibold hover:bg-[#E8B92E] transition shadow-xs">
                    <span>Buka Dashboard</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </a>
                @else
                <a href="{{ route('login') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#F5C842] text-[#101214] text-xs font-heading font-semibold hover:bg-[#E8B92E] transition shadow-xs">
                    <span>Masuk ke Sistem</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </a>
                @endauth
            </div>

        </div>
    </header>

    <main class="space-y-24 sm:space-y-32 pb-24">

        <section class="min-h-[calc(100dvh-5rem)] flex items-center max-w-7xl mx-auto px-6 py-8 relative">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-center">
                <div class="lg:col-span-7 space-y-6 text-left">

                    <h1 class="font-heading text-4xl sm:text-5xl lg:text-6xl font-bold tracking-tight text-[#F5F5F5] leading-[1.15]">
                        Kelola Persediaan Gudang dengan <span class="text-[#F5C842]">Presisi Tinggi.</span>
                    </h1>
                    <p class="text-sm sm:text-base text-[#A1A1AA] leading-relaxed max-w-xl">
                        Platform pergudangan terpadu untuk brand ritel dan pusat distribusi modern. Mengeliminasi selisih stok melalui telemetri rak berstandar dot-matrix, mutasi ganda, dan audit log tanpa kompromi.
                    </p>
                    <div class="space-y-3 pt-1">
                        <div class="flex items-center gap-3 text-xs sm:text-sm text-[#F5F5F5]">
                            <span class="w-5 h-5 rounded-full bg-[#F5C842]/15 border border-[#F5C842]/40 flex items-center justify-center shrink-0 text-[#F5C842]">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                            </span>
                            <span>Telemetri Keterisian Rak & Matriks Gudang Real-Time</span>
                        </div>
                        <div class="flex items-center gap-3 text-xs sm:text-sm text-[#F5F5F5]">
                            <span class="w-5 h-5 rounded-full bg-[#F5C842]/15 border border-[#F5C842]/40 flex items-center justify-center shrink-0 text-[#F5C842]">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                            </span>
                            <span>Pencatatan Mutasi Ganda (Inbound & Outbound) Terotorisasi</span>
                        </div>
                        <div class="flex items-center gap-3 text-xs sm:text-sm text-[#F5F5F5]">
                            <span class="w-5 h-5 rounded-full bg-[#F5C842]/15 border border-[#F5C842]/40 flex items-center justify-center shrink-0 text-[#F5C842]">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                            </span>
                            <span>Multi-Role Access Control (Admin, Manager, Staff) & Audit Log</span>
                        </div>
                    </div>

                    <div class="pt-2 flex flex-wrap items-center gap-3.5">
                        <a href="#akses-demo"
                            class="inline-flex items-center gap-2.5 px-6 py-3.5 rounded-full bg-[#F5C842] text-[#101214] text-xs font-heading font-bold hover:bg-[#E8B92E] transition shadow-lg shadow-[#F5C842]/20 hover:scale-[1.02]">

                            <span>Mulai Akses Demo 1-Klik</span>
                        </a>
                        <a href="#simulator"
                            class="inline-flex items-center gap-2 px-5 py-3.5 rounded-full bg-[#181C20] border border-[#343A40] text-[#F5F5F5] text-xs font-heading font-semibold hover:border-[#F5C842] hover:text-[#F5C842] transition">
                            <svg class="w-4 h-4 text-[#F5C842]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                            <span>Uji Simulator Mutasi</span>
                        </a>
                    </div>

                </div>

                <div class="lg:col-span-5 relative">

                    <div class="absolute -top-6 -right-6 w-48 h-48 bg-[#F5C842]/10 rounded-full blur-3xl pointer-events-none animate-pulse"></div>
                    <div class="bg-[#202429] rounded-2xl border border-[#343A40] p-5 shadow-xl relative overflow-hidden">

                        <div class="pb-3 border-b border-[#343A40]/80">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-mono uppercase tracking-widest text-[#A1A1AA]">CHECK WHETHER YOUR ROW ARE FULL OR NOT</span>
                                <span class="px-2 py-0.5 rounded text-[9px] font-mono bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 flex items-center gap-1.5" id="hero-stream-status">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                    LIVE TELEMETRI
                                </span>
                            </div>
                            <div class="flex items-baseline justify-between mt-2.5">
                                <div>
                                    <span class="text-xs text-[#A1A1AA] font-sans">Kapasitas Terpakai: </span>
                                    <span class="text-lg font-mono font-bold text-[#F5C842]" id="hero-bit-percent">0%</span>
                                </div>
                                <div class="text-[11px] font-mono text-[#A1A1AA]">
                                    (<span id="hero-bit-units" class="text-[#F5F5F5] font-semibold">0</span> / 1.000 Unit)
                                </div>
                            </div>
                            <div class="w-full bg-[#101214] rounded-full h-1.5 border border-[#272B30] mt-2 overflow-hidden">
                                <div id="hero-bit-progressbar" class="h-full bg-gradient-to-r from-[#F5C842] to-[#FB923C] rounded-full transition-all duration-300" style="width: 0%;"></div>
                            </div>
                        </div>
                        <div class="mt-4 p-4 bg-[#101214] border border-[#272B30] rounded-xl space-y-2.5" id="hero-dot-matrix">
                            @for($row = 1; $row <= 4; $row++)
                                <div class="flex items-center justify-between gap-3">
                                <span class="text-[10px] font-mono text-[#A1A1AA] shrink-0 w-16">Rack Row 0{{ $row }}</span>
                                <div class="flex items-center justify-between flex-1 gap-1.5 sm:gap-2">
                                    @for($col = 1; $col <= 10; $col++)
                                        @php
                                        $rackNum=($row - 1) * 10 + $col;
                                        @endphp
                                        <div class="hero-rack-dot relative transition-all duration-300"
                                        data-col="{{ $col }}"
                                        data-row="{{ $row }}"
                                        data-rack="{{ sprintf('Rack-%02d', $rackNum) }}"
                                        title="Rack-{{ sprintf('%02d', $rackNum) }}"
                                        style="width: 14px; height: 14px; min-width: 14px; min-height: 14px; border-radius: 9999px; background-color: #343A40;">
                                </div>
                                @endfor
                        </div>
                    </div>
                    @endfor
                </div>


                <div class="mt-3.5 flex flex-wrap items-center justify-between gap-2 text-[10px] font-mono pt-3 border-t border-[#343A40]/60">
                    <span class="flex items-center gap-1.5 text-[#A1A1AA]">
                        <span style="width: 8px; height: 8px; border-radius: 9999px; background-color: #343A40;" class="inline-block"></span> Kosong
                    </span>
                    <span class="flex items-center gap-1.5 text-[#F5C842]">
                        <span style="width: 8px; height: 8px; border-radius: 9999px; background-color: #F5C842;" class="inline-block"></span> Normal
                    </span>
                    <span class="flex items-center gap-1.5 text-[#FB923C]">
                        <span style="width: 8px; height: 8px; border-radius: 9999px; background-color: #FB923C;" class="inline-block"></span> Hampir Penuh
                    </span>
                    <span class="flex items-center gap-1.5 text-rose-400">
                        <span style="width: 8px; height: 8px; border-radius: 9999px; background-color: #F43F5E;" class="inline-block shadow-[0_0_6px_rgba(244,63,94,0.6)]"></span> Penuh King
                    </span>
                </div>
            </div>
            </div>
        </section>

        <div class="max-w-7xl mx-auto px-6 pt-6 pb-12 border-t border-[#343A40]">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-left">
                <div class="p-6 rounded-2xl bg-[#202429] border border-[#343A40] shadow-xs hover:border-[#F5C842]/40 transition">
                    <div class="text-3xl font-bold font-heading tabular-nums text-[#F5C842]">
                        {{ $productCount > 0 ? number_format($productCount) . ' SKU' : '15.000+' }}
                    </div>
                    <div class="text-xs font-semibold text-[#F5F5F5] mt-1.5 font-heading">Kapasitas Multi-Atribut</div>
                    <div class="text-[11px] text-[#A1A1AA] mt-0.5">Varian ukuran, warna, nomor seri, dan ambang batas stok.</div>
                </div>

                <div class="p-6 rounded-2xl bg-[#202429] border border-[#343A40] shadow-xs hover:border-[#F5C842]/40 transition">
                    <div class="text-3xl font-bold font-heading tabular-nums text-[#F5C842]">
                        {{ $transactionCount > 0 ? number_format($transactionCount) . ' Mutasi' : 'Real-time' }}
                    </div>
                    <div class="text-xs font-semibold text-[#F5F5F5] mt-1.5 font-heading">Alur Mutasi Terotorisasi</div>
                    <div class="text-[11px] text-[#A1A1AA] mt-0.5">Pencatatan invoice digital Inbound & Outbound dengan verifikasi ganda.</div>
                </div>

                <div class="p-6 rounded-2xl bg-[#202429] border border-[#343A40] shadow-xs hover:border-[#F5C842]/40 transition">
                    <div class="text-3xl font-bold font-heading tabular-nums text-[#F5C842]">
                        {{ $supplierCount > 0 ? number_format($supplierCount) . ' Rekanan' : '99.98%' }}
                    </div>
                    <div class="text-xs font-semibold text-[#F5F5F5] mt-1.5 font-heading">Jaringan Supplier Aktif</div>
                    <div class="text-[11px] text-[#A1A1AA] mt-0.5">Kemitraan terintegrasi dengan alamat kontak & riwayat pasokan.</div>
                </div>
            </div>
        </div>



        <section id="simulator" class="max-w-6xl mx-auto px-6">

            <div class="text-center max-w-2xl mx-auto mb-10">
                <span class="text-[11px] uppercase tracking-[0.2em] text-[#F5C842] font-semibold">Simulator Gudang Interaktif</span>
                <h2 class="font-heading text-3xl sm:text-4xl font-bold text-[#F5F5F5] mt-1">
                    Uji Kalkulasi Mutasi Stok Real-Time.
                </h2>
                <p class="mt-2 text-xs text-[#A1A1AA]">
                    Pilih produk di bawah untuk mencoba simulasi barang masuk (Inbound) dan pengeluaran barang (Outbound) dengan deteksi ambang batas stok otomatis.
                </p>
            </div>

            <div class="rounded-3xl bg-[#202429] border border-[#343A40] shadow-md overflow-hidden">

                <div class="p-6 bg-[#181C20] border-b border-[#343A40] flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                    <div>
                        <span class="text-[10px] font-mono uppercase tracking-wider text-[#A1A1AA] font-semibold">Pilih SKU Produk untuk Diuji:</span>
                        <div class="mt-2 flex flex-wrap gap-2" id="sim-product-tabs">
                            <button type="button" onclick="selectSimProduct(0)" id="sim-tab-0"
                                class="sim-tab px-3.5 py-1.5 rounded-lg text-xs font-heading font-semibold transition cursor-pointer bg-[#F5C842] text-[#101214] shadow-xs">
                                Logitech K98M Mechanical
                            </button>
                            <button type="button" onclick="selectSimProduct(1)" id="sim-tab-1"
                                class="sim-tab px-3.5 py-1.5 rounded-lg text-xs font-heading font-semibold transition cursor-pointer bg-[#202429] border border-[#343A40] text-[#F5F5F5] hover:border-[#F5C842]">
                                Herman Miller Aeron Mesh Chair
                            </button>
                            <button type="button" onclick="selectSimProduct(2)" id="sim-tab-2"
                                class="sim-tab px-3.5 py-1.5 rounded-lg text-xs font-heading font-semibold transition cursor-pointer bg-[#202429] border border-[#343A40] text-[#F5F5F5] hover:border-[#F5C842]">
                                Termos Maspion 750ml
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="text-xs font-mono font-medium text-[#A1A1AA]">Simulator</span>
                    </div>
                </div>

                <div class="p-6 sm:p-8 grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

                    <div class="lg:col-span-7 space-y-6">

                        <div class="p-6 rounded-2xl bg-[#181C20] border border-[#343A40]">
                            <div class="flex items-center justify-between gap-2 mb-3">
                                <div class="flex items-center gap-2">
                                    <span id="sim-sku" class="px-2.5 py-0.5 rounded font-mono text-xs font-semibold bg-[#F5C842]/15 text-[#F5C842] border border-[#F5C842]/30">
                                        PRD-ELC-001
                                    </span>
                                    <span id="sim-category" class="text-xs text-[#A1A1AA]">Elektronik & Periferal</span>
                                </div>
                                <span id="sim-status-badge" class="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/15 text-emerald-400 border border-emerald-500/30">
                                    Stok Aman
                                </span>
                            </div>

                            <h3 id="sim-name" class="font-heading text-2xl font-bold text-[#F5F5F5]">
                                Logitech Alto K98M Mechanical Keyboard
                            </h3>
                            <p id="sim-desc" class="text-xs text-[#A1A1AA] mt-1 leading-relaxed">
                                Wireless Mechanical Keyboard with UniGasket to absorb vibration.
                            </p>

                            <div class="mt-6 pt-6 border-t border-[#343A40] flex items-end justify-between">
                                <div>
                                    <span class="text-[11px] text-[#A1A1AA] uppercase tracking-wider block font-medium">Sisa Stok di Gudang</span>
                                    <div class="flex items-baseline gap-2 mt-1">
                                        <span id="sim-stock" class="font-mono text-5xl font-bold text-[#F5F5F5] tabular-nums transition-all duration-200">
                                            35
                                        </span>
                                        <span class="text-xs text-[#A1A1AA]">Unit Tersedia</span>
                                    </div>
                                </div>

                                <div class="text-right">
                                    <span class="text-[10px] text-[#A1A1AA] block uppercase tracking-wider font-medium">Batas Minimum (Alert)</span>
                                    <span id="sim-min-stock" class="font-mono text-base font-semibold text-[#A1A1AA] tabular-nums">
                                        10 Unit
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <span class="text-[11px] font-semibold text-[#A1A1AA] uppercase tracking-wider block mb-3">
                                Eksekusi Mutasi Cepat:
                            </span>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                                <button type="button" onclick="adjustSimStock(5, 'in')"
                                    class="py-3 px-3 rounded-xl bg-emerald-500/15 hover:bg-emerald-500/25 text-emerald-400 border border-emerald-500/30 text-xs font-heading font-semibold transition cursor-pointer flex flex-col items-center justify-center gap-1 active:scale-95">
                                    <span class="text-sm"> +5 Unit</span>
                                    <span class="text-[10px] opacity-80">Inbound Masuk</span>
                                </button>
                                <button type="button" onclick="adjustSimStock(20, 'in')"
                                    class="py-3 px-3 rounded-xl bg-emerald-500/15 hover:bg-emerald-500/25 text-emerald-400 border border-emerald-500/30 text-xs font-heading font-semibold transition cursor-pointer flex flex-col items-center justify-center gap-1 active:scale-95">
                                    <span class="text-sm"> +20 Unit</span>
                                    <span class="text-[10px] opacity-80">Pasokan Besar</span>
                                </button>
                                <button type="button" onclick="adjustSimStock(5, 'out')"
                                    class="py-3 px-3 rounded-xl bg-[#F87171]/15 hover:bg-[#F87171]/25 text-[#F87171] border border-[#F87171]/30 text-xs font-heading font-semibold transition cursor-pointer flex flex-col items-center justify-center gap-1 active:scale-95">
                                    <span class="text-sm"> -5 Unit</span>
                                    <span class="text-[10px] opacity-80">Kirim Outbound</span>
                                </button>
                                <button type="button" onclick="adjustSimStock(20, 'out')"
                                    class="py-3 px-3 rounded-xl bg-[#F87171]/15 hover:bg-[#F87171]/25 text-[#F87171] border border-[#F87171]/30 text-xs font-heading font-semibold transition cursor-pointer flex flex-col items-center justify-center gap-1 active:scale-95">
                                    <span class="text-sm"> -20 Unit</span>
                                    <span class="text-[10px] opacity-80">Order Massal</span>
                                </button>
                            </div>
                            <div class="mt-3 flex items-center justify-between">
                                <span class="text-[10px] text-[#A1A1AA] italic">*Sistem otomatis menolak pengeluaran jika stok tidak mencukupi.</span>
                                <button type="button" onclick="resetSimProduct()" class="text-xs font-medium text-[#A1A1AA] hover:text-[#F5C842] underline cursor-pointer">
                                    Reset Nilai
                                </button>
                            </div>
                        </div>

                    </div>

                    <div class="lg:col-span-5 flex flex-col h-full space-y-3">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-heading font-bold text-[#F5F5F5] flex items-center gap-2">
                                <svg class="w-4 h-4 text-[#F5C842]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Buku Mutasi (Live Ledger)
                            </span>
                            <button type="button" onclick="clearSimLogs()" class="text-[11px] text-[#A1A1AA] hover:text-[#F5C842] transition cursor-pointer">
                                Bersihkan Log
                            </button>
                        </div>

                        <div class="rounded-2xl bg-[#101214] border border-[#343A40] p-4 text-xs font-mono text-[#F5F5F5] flex-1 flex flex-col justify-between shadow-inner min-h-[320px]">
                            <div class="flex items-center justify-between pb-3 border-b border-[#343A40] text-[10px] text-[#A1A1AA]">
                                <div class="flex items-center gap-1.5">
                                    <span class="w-2.5 h-2.5 rounded-full bg-red-500/80"></span>
                                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500/80"></span>
                                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500/80"></span>
                                </div>
                                <span>STOCKIFY-AUDIT-STREAM</span>
                            </div>

                            <div id="sim-log-entries" class="py-3 space-y-2 overflow-y-auto max-h-[260px] text-[11px] flex-1">
                                <div class="text-[#A1A1AA]">mencatat mutasi stok</div>
                            </div>

                            <div class="pt-3 border-t border-[#343A40] flex items-center justify-between text-[10px] text-[#A1A1AA]">
                                <span>Status: <span class="text-emerald-400">Tersinkronisasi</span></span>
                                <span>Double-Entry Ledger</span>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </section>


        <section id="fitur" class="max-w-6xl mx-auto px-6">

            <div class="text-center max-w-2xl mx-auto mb-12">
                <span class="text-[11px] uppercase tracking-[0.2em] text-[#F5C842] font-semibold">Kapabilitas Sistem</span>
                <h2 class="font-heading text-3xl sm:text-4xl font-bold text-[#F5F5F5] mt-1">
                    Dirancang untuk Membantu UMKM, Unicorn, maupun Perusahaan Besar
                </h2>
                <p class="mt-2 text-xs text-[#A1A1AA]">
                    Stockify terintegrasi langsung ke basis data terpusat dengan integritas transaksi atomik.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

                <div class="p-6 rounded-2xl bg-[#202429] border border-[#343A40] hover:border-[#F5C842]/50 shadow-xs flex flex-col justify-between group transition">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-[#181C20] text-[#F5C842] flex items-center justify-center font-bold text-sm mb-4 border border-[#343A40]">
                            01
                        </div>
                        <h3 class="font-heading text-lg font-bold text-[#F5F5F5] group-hover:text-[#F5C842] transition">
                            Master Data & SKU
                        </h3>
                        <p class="text-xs text-[#A1A1AA] mt-2 leading-relaxed">
                            Generate kode SKU dinamis, atribut multi-varian (ukuran, warna, garansi), dan ambang batas minimum stok.
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-[#343A40]">
                        <a href="{{ route('login') }}" class="text-xs font-heading font-semibold text-[#F5C842] hover:underline inline-flex items-center gap-1">
                            <span>Jelajahi Katalog</span> &rarr;
                        </a>
                    </div>
                </div>

                <div class="p-6 rounded-2xl bg-[#202429] border border-[#343A40] hover:border-[#F5C842]/50 shadow-xs flex flex-col justify-between group transition">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-[#181C20] text-[#F5C842] flex items-center justify-center font-bold text-sm mb-4 border border-[#343A40]">
                            02
                        </div>
                        <h3 class="font-heading text-lg font-bold text-[#F5F5F5] group-hover:text-[#F5C842] transition">
                            Mutasi Inbound & Outbound
                        </h3>
                        <p class="text-xs text-[#A1A1AA] mt-2 leading-relaxed">
                            Validasi sisa stok ketat sebelum pengeluaran barang. Dilengkapi alur otorisasi approval terpisah Manajer dan Staff.
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-[#343A40]">
                        <a href="{{ route('login') }}" class="text-xs font-heading font-semibold text-[#F5C842] hover:underline inline-flex items-center gap-1">
                            <span>Alur Transaksi</span> &rarr;
                        </a>
                    </div>
                </div>

                <div class="p-6 rounded-2xl bg-[#202429] border border-[#343A40] hover:border-[#F5C842]/50 shadow-xs flex flex-col justify-between group transition">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-[#181C20] text-[#F5C842] flex items-center justify-center font-bold text-sm mb-4 border border-[#343A40]">
                            03
                        </div>
                        <h3 class="font-heading text-lg font-bold text-[#F5F5F5] group-hover:text-[#F5C842] transition">
                            Stock Opname Presisi
                        </h3>
                        <p class="text-xs text-[#A1A1AA] mt-2 leading-relaxed">
                            Penghitungan fisik real-time dengan kalkulator selisih instan, berita acara cetak, dan rekonsiliasi otomatis.
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-[#343A40]">
                        <a href="{{ route('login') }}" class="text-xs font-heading font-semibold text-[#F5C842] hover:underline inline-flex items-center gap-1">
                            <span>Rekonsiliasi Fisik</span> &rarr;
                        </a>
                    </div>
                </div>

                <div class="p-6 rounded-2xl bg-[#202429] border border-[#343A40] hover:border-[#F5C842]/50 shadow-xs flex flex-col justify-between group transition">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-[#181C20] text-[#F5C842] flex items-center justify-center font-bold text-sm mb-4 border border-[#343A40]">
                            04
                        </div>
                        <h3 class="font-heading text-lg font-bold text-[#F5F5F5] group-hover:text-[#F5C842] transition">
                            Laporan & Ekspor Data
                        </h3>
                        <p class="text-xs text-[#A1A1AA] mt-2 leading-relaxed">
                            Grafik tren mutasi 7 hari, visualisasi terpadu, ekspor dokumen CSV untuk Excel, dan lembar PDF berotorisasi.
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-[#343A40]">
                        <a href="{{ route('login') }}" class="text-xs font-heading font-semibold text-[#F5C842] hover:underline inline-flex items-center gap-1">
                            <span>Modul Laporan</span> &rarr;
                        </a>
                    </div>
                </div>

            </div>

        </section>

        <section id="arsitektur" class="max-w-6xl mx-auto px-6">

            <div class="p-8 sm:p-12 rounded-3xl bg-[#202429] border border-[#343A40] shadow-xs relative overflow-hidden">
                <div class="max-w-2xl">
                    <span class="text-[11px] uppercase tracking-[0.2em] text-[#F5C842] font-semibold">Standar Operasional</span>
                    <h2 class="font-heading text-3xl sm:text-4xl font-bold text-[#F5F5F5] mt-1">
                        Siklus Mutasi Stok Tanpa Ambang Human Error.
                    </h2>
                    <p class="mt-3 text-xs sm:text-sm text-[#A1A1AA] leading-relaxed">
                        Setiap perubahan jumlah barang diikat dengan nomor bukti transaksi (Invoice), validasi kapasitas stok sebelum barang keluar, dan status konfirmasi bertingkat.
                    </p>
                </div>

                <div class="mt-10 grid grid-cols-1 md:grid-cols-3 gap-6 text-xs">
                    <div class="p-6 rounded-2xl bg-[#181C20] border border-[#343A40] space-y-2.5">
                        <div class="w-8 h-8 rounded-lg bg-[#F5C842] text-[#101214] flex items-center justify-center font-heading font-bold">1</div>
                        <h4 class="font-heading font-bold text-[#F5F5F5] text-sm">Penerimaan Inbound</h4>
                        <p class="text-[#A1A1AA] leading-relaxed">
                            Pencatatan nomor surat jalan supplier, SKU barang, dan kuantitas masuk. Stok fisik langsung terbarui secara atomic dalam database.
                        </p>
                    </div>

                    <div class="p-6 rounded-2xl bg-[#181C20] border border-[#343A40] space-y-2.5">
                        <div class="w-8 h-8 rounded-lg bg-[#F5C842] text-[#101214] flex items-center justify-center font-heading font-bold">2</div>
                        <h4 class="font-heading font-bold text-[#F5F5F5] text-sm">Validasi Stok Outbound</h4>
                        <p class="text-[#A1A1AA] leading-relaxed">
                            Sistem secara proaktif menolak pengeluaran jika permintaan melebihi sisa fisik di gudang. Dilengkapi klasifikasi alasan mutasi.
                        </p>
                    </div>

                    <div class="p-6 rounded-2xl bg-[#181C20] border border-[#343A40] space-y-2.5">
                        <div class="w-8 h-8 rounded-lg bg-[#F5C842] text-[#101214] flex items-center justify-center font-heading font-bold">3</div>
                        <h4 class="font-heading font-bold text-[#F5F5F5] text-sm">Otorisasi & Audit Trail</h4>
                        <p class="text-[#A1A1AA] leading-relaxed">
                            Transaksi pending ditinjau oleh Manajer Gudang. Setiap langkah tercatat dalam tabel log aktivitas dengan alamat IP dan timestamp.
                        </p>
                    </div>
                </div>
            </div>

        </section>

        <section id="akses-demo" class="max-w-5xl mx-auto px-6">

            <div class="text-center mb-10">
                <span class="text-[11px] uppercase tracking-[0.2em] text-[#F5C842] font-semibold">Sandbox & Demo Eksklusif</span>
                <h2 class="font-heading text-3xl sm:text-4xl font-bold text-[#F5F5F5] mt-1">
                    Uji Coba Langsung Berdasarkan Peran Pengguna.
                </h2>
                <p class="text-xs text-[#A1A1AA] mt-2">
                    Pilih peran di bawah ini untuk langsung masuk ke dashboard sistem tanpa perlu mengisi formulir login.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                <div class="p-6 rounded-2xl bg-[#202429] border border-[#343A40] shadow-xs flex flex-col justify-between space-y-5 hover:border-[#F5C842]/50 transition">
                    <div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-heading font-bold text-[#F5F5F5]">Administrator</span>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-[#F5C842]/15 text-[#F5C842] border border-[#F5C842]/30">Akses Penuh</span>
                        </div>
                        <p class="text-xs text-[#A1A1AA] mt-2 leading-relaxed">
                            Akses kelola Master Data (Kategori, Supplier, Produk), hapus data, audit log aktivitas sistem, dan konfirmasi transaksi.
                        </p>
                    </div>
                    <form action="{{ route('login.post') }}" method="POST">
                        @csrf
                        <input type="hidden" name="email" value="admin@stockify.test">
                        <input type="hidden" name="password" value="password">
                        <button type="submit"
                            class="w-full py-3 px-4 rounded-xl bg-[#F5C842] hover:bg-[#E8B92E] text-[#101214] text-xs font-heading font-semibold transition shadow-xs cursor-pointer flex items-center justify-center gap-1.5">
                            <span>Masuk sebagai Admin</span> &rarr;
                        </button>
                    </form>
                </div>

                <div class="p-6 rounded-2xl bg-[#202429] border border-[#343A40] shadow-xs flex flex-col justify-between space-y-5 hover:border-[#F5C842]/50 transition">
                    <div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-heading font-bold text-[#F5F5F5]">Manajer Gudang</span>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-[#181C20] text-[#F5F5F5] border border-[#343A40]">Supervisor</span>
                        </div>
                        <p class="text-xs text-[#A1A1AA] mt-2 leading-relaxed">
                            Akses monitoring stok tipis, input produk baru, persetujuan transaksi mutasi, dan verifikasi barang masuk/keluar.
                        </p>
                    </div>
                    <form action="{{ route('login.post') }}" method="POST">
                        @csrf
                        <input type="hidden" name="email" value="manager@stockify.test">
                        <input type="hidden" name="password" value="password">
                        <button type="submit"
                            class="w-full py-3 px-4 rounded-xl bg-[#181C20] border border-[#343A40] hover:border-[#F5C842] text-[#F5F5F5] text-xs font-heading font-semibold hover:bg-[#2A3036] hover:text-[#F5C842] transition cursor-pointer flex items-center justify-center gap-1.5">
                            <span>Masuk sebagai Manajer</span> &rarr;
                        </button>
                    </form>
                </div>

                <div class="p-6 rounded-2xl bg-[#202429] border border-[#343A40] shadow-xs flex flex-col justify-between space-y-5 hover:border-[#F5C842]/50 transition">
                    <div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-heading font-bold text-[#F5F5F5]">Staff Gudang</span>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-[#181C20] text-[#A1A1AA] border border-[#343A40]">Operator</span>
                        </div>
                        <p class="text-xs text-[#A1A1AA] mt-2 leading-relaxed">
                            Akses operasional harian: melihat katalog produk, mencatat barang masuk dari supplier, dan menyiapkan barang keluar.
                        </p>
                    </div>
                    <form action="{{ route('login.post') }}" method="POST">
                        @csrf
                        <input type="hidden" name="email" value="staff@stockify.test">
                        <input type="hidden" name="password" value="password">
                        <button type="submit"
                            class="w-full py-3 px-4 rounded-xl bg-[#181C20] border border-[#343A40] hover:border-[#F5C842] text-[#F5F5F5] text-xs font-heading font-semibold hover:bg-[#2A3036] hover:text-[#F5C842] transition cursor-pointer flex items-center justify-center gap-1.5">
                            <span>Masuk sebagai Staff</span> &rarr;
                        </button>
                    </form>
                </div>

            </div>

        </section>

    </main>

    <footer class="border-t border-[#343A40] bg-[#141619] pt-20 pb-14 mt-24">
        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-12 lg:gap-16 pb-16 border-b border-[#343A40]">

                <!-- Kolom 1: Brand & Identitas Perusahaan -->
                <div class="lg:col-span-5 space-y-5">
                    <a href="/" class="flex items-center gap-3 group inline-flex">
                        @if($landingAppLogo && file_exists(public_path('storage/' . $landingAppLogo)))
                        <img src="{{ asset('storage/' . $landingAppLogo) }}" alt="{{ $landingAppName }}" class="w-10 h-10 rounded-xl object-contain bg-[#202429] border border-[#343A40] p-1">
                        @else
                        <div class="w-10 h-10 rounded-xl bg-[#F5C842] border border-[#F5C842]/40 flex items-center justify-center text-[#101214] font-heading font-bold text-base shadow-xs transition group-hover:bg-[#E8B92E]">
                            S
                        </div>
                        @endif
                        <div class="flex flex-col">
                            <span class="font-heading text-xl font-bold tracking-tight text-[#F5F5F5]">{{ $landingAppName }}</span>
                            <span class="text-[9px] uppercase tracking-[0.2em] text-[#A1A1AA] font-semibold">{{ $landingAppTagline }}</span>
                        </div>
                    </a>

                    <p class="text-xs sm:text-sm text-[#A1A1AA] leading-relaxed max-w-md">
                        Platform manajemen pergudangan terpadu yang dirancang untuk presisi operasional tinggi. Mengeliminasi selisih stok melalui pencatatan mutasi terverifikasi, kontrol multi-peran, dan rekonsiliasi audit fisik akurat.
                    </p>

                    @if($landingCompanyAddress || $landingCompanyEmail || $landingCompanyPhone)
                    <div class="space-y-2 pt-2 text-xs text-[#A1A1AA]">
                        @if($landingCompanyAddress)
                        <div class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-[#F5C842] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="leading-snug">{{ $landingCompanyAddress }}</span>
                        </div>
                        @endif
                        @if($landingCompanyEmail)
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-[#F5C842] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span>{{ $landingCompanyEmail }}</span>
                        </div>
                        @endif
                        @if($landingCompanyPhone)
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-[#F5C842] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <span>{{ $landingCompanyPhone }}</span>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>

                <!-- Kolom 2: Fitur Utama Web -->
                <div class="lg:col-span-2 sm:col-span-1 space-y-4">
                    <span class="text-xs font-mono tracking-widest uppercase text-[#F5C842] font-semibold block">Fitur Sistem</span>
                    <ul class="space-y-3 text-xs sm:text-[13px]">
                        <li><a href="#simulator" class="text-[#A1A1AA] hover:text-[#F5C842] transition">Simulator Stok</a></li>
                        <li><a href="#fitur" class="text-[#A1A1AA] hover:text-[#F5C842] transition">Katalog SKU</a></li>
                        <li><a href="#fitur" class="text-[#A1A1AA] hover:text-[#F5C842] transition">Mutasi Inbound & Outbound</a></li>
                        <li><a href="#fitur" class="text-[#A1A1AA] hover:text-[#F5C842] transition">Stock Opname Fisik</a></li>
                        <li><a href="#arsitektur" class="text-[#A1A1AA] hover:text-[#F5C842] transition">Alur Logistik 4-Pilar</a></li>
                    </ul>
                </div>

                <!-- Kolom 3: Akses Demo Cepat -->
                <div class="lg:col-span-2 sm:col-span-1 space-y-4">
                    <span class="text-xs font-mono tracking-widest uppercase text-[#F5C842] font-semibold block">Akses Demo</span>
                    <ul class="space-y-3 text-xs sm:text-[13px]">
                        <li>
                            <a href="#akses-demo" class="text-[#A1A1AA] hover:text-[#F5C842] transition flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#F5C842]"></span>
                                <span>Demo Admin</span>
                            </a>
                        </li>
                        <li>
                            <a href="#akses-demo" class="text-[#A1A1AA] hover:text-[#F5C842] transition flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#38BDF8]"></span>
                                <span>Demo Manajer</span>
                            </a>
                        </li>
                        <li>
                            <a href="#akses-demo" class="text-[#A1A1AA] hover:text-[#F5C842] transition flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#22C55E]"></span>
                                <span>Demo Staff</span>
                            </a>
                        </li>
                        <li class="pt-2">
                            <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#F5C842] hover:text-[#E8B92E] transition">
                                <span>Halaman Login</span> &rarr;
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Kolom 4: Modul Operasional -->
                <div class="lg:col-span-3 sm:col-span-1 space-y-4">
                    <span class="text-xs font-mono tracking-widest uppercase text-[#F5C842] font-semibold block">Modul Operasional</span>
                    <ul class="space-y-3 text-xs sm:text-[13px]">
                        <li><a href="{{ route('dashboard') }}" class="text-[#A1A1AA] hover:text-[#F5F5F5] transition">Dashboard Overview</a></li>
                        <li><a href="{{ route('products.index') }}" class="text-[#A1A1AA] hover:text-[#F5F5F5] transition">Master Data Produk</a></li>
                        <li><a href="{{ route('transactions.index') }}" class="text-[#A1A1AA] hover:text-[#F5F5F5] transition">Riwayat Transaksi</a></li>
                        <li><a href="{{ route('opnames.index') }}" class="text-[#A1A1AA] hover:text-[#F5F5F5] transition">Stock Opname</a></li>
                        <li><a href="{{ route('reports.stock') }}" class="text-[#A1A1AA] hover:text-[#F5F5F5] transition">Laporan Valuasi</a></li>
                    </ul>
                </div>

            </div>

            <!-- Bagian Bawah: Copyright, Status Sistem, & Back to top -->
            <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-6 text-xs text-[#A1A1AA]">
                <div class="flex items-center gap-2.5">
                    <span class="w-2 h-2 rounded-full bg-[#22C55E] animate-pulse"></span>
                    <span class="text-xs text-[#A1A1AA]">Status Sistem: <strong class="text-[#F5F5F5]">Operasional Normal</strong> &bull; Laravel v10</span>
                </div>

                <div class="text-xs text-[#A1A1AA]">
                    &copy; {{ date('Y') }} {{ $landingAppName }}. Seluruh hak cipta dilindungi.
                </div>

                <a href="#" onclick="window.scrollTo({top: 0, behavior: 'smooth'}); return false;" class="text-xs text-[#A1A1AA] hover:text-[#F5C842] transition flex items-center gap-1.5">
                    <span>Kembali ke atas</span> &uarr;
                </a>
            </div>

        </div>
    </footer>

    <script>
        const simProducts = [{
                name: "Keyboard Mekanikal Pro Wireless",
                sku: "PRD-ELC-001",
                category: "Elektronik & Periferal",
                desc: "Dilengkapi switch hot-swappable dengan varian atribut warna Onyx Black dan Grey.",
                initialStock: 35,
                stock: 35,
                minStock: 10
            },
            {
                name: "Kursi Ergonomis Mesh Lumbar",
                sku: "PRD-FRN-002",
                category: "Furniture Gudang & Kantor",
                desc: "Rangka baja ringan dengan sandaran jaring fleksibel dan gaslift hidrolik kelas 4.",
                initialStock: 8,
                stock: 8,
                minStock: 10
            },
            {
                name: "Termos Insulasi Stainless 750ml",
                sku: "PRD-HMD-003",
                category: "Perlengkapan & Logistik",
                desc: "Dinding vakum ganda menjaga suhu hingga 24 jam dengan tutup seal anti-bocor.",
                initialStock: 60,
                stock: 60,
                minStock: 15
            }
        ];

        let currentProductIndex = 0;

        function getTimestamp() {
            const now = new Date();
            return now.toTimeString().split(' ')[0];
        }

        function appendSimLog(message, type = 'normal') {
            const container = document.getElementById('sim-log-entries');
            if (!container) return;

            const div = document.createElement('div');
            div.className = 'leading-relaxed break-words';

            let prefix = `<span class="text-[#A1A1AA]">[${getTimestamp()}]</span> `;
            if (type === 'inbound') {
                div.innerHTML = `${prefix}<span class="text-emerald-400 font-semibold">📥 INBOUND:</span> ${message}`;
            } else if (type === 'outbound') {
                div.innerHTML = `${prefix}<span class="text-[#F87171] font-semibold">📤 OUTBOUND:</span> ${message}`;
            } else if (type === 'warning') {
                div.innerHTML = `${prefix}<span class="text-amber-400 font-semibold">⚠️ PERINGATAN:</span> ${message}`;
            } else if (type === 'error') {
                div.innerHTML = `${prefix}<span class="text-red-400 font-semibold">❌ DITOLAK:</span> ${message}`;
            } else {
                div.innerHTML = `${prefix}${message}`;
            }

            container.appendChild(div);
            container.scrollTop = container.scrollHeight;
        }

        function updateSimUI() {
            const p = simProducts[currentProductIndex];

            document.getElementById('sim-name').textContent = p.name;
            document.getElementById('sim-sku').textContent = p.sku;
            document.getElementById('sim-category').textContent = p.category;
            document.getElementById('sim-desc').textContent = p.desc;
            document.getElementById('sim-min-stock').textContent = p.minStock + ' Unit';

            const stockEl = document.getElementById('sim-stock');
            stockEl.textContent = p.stock;

            const badge = document.getElementById('sim-status-badge');
            if (p.stock <= p.minStock) {
                badge.className = 'px-3 py-1 rounded-full text-xs font-semibold bg-[#FB923C]/15 text-[#FB923C] border border-[#FB923C]/30 animate-pulse';
                badge.textContent = '⚠️ Peringatan: Stok Menipis!';
                stockEl.className = 'font-mono text-5xl font-bold text-[#FB923C] tabular-nums transition-all duration-200';
            } else {
                badge.className = 'px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/15 text-emerald-400 border border-emerald-500/30';
                badge.textContent = '✓ Stok Aman (Normal)';
                stockEl.className = 'font-mono text-5xl font-bold text-[#F5F5F5] tabular-nums transition-all duration-200';
            }

            // Update tab styling
            for (let i = 0; i < simProducts.length; i++) {
                const tab = document.getElementById(`sim-tab-${i}`);
                if (!tab) continue;
                if (i === currentProductIndex) {
                    tab.className = 'sim-tab px-3.5 py-1.5 rounded-lg text-xs font-heading font-semibold transition cursor-pointer bg-[#F5C842] text-[#101214] shadow-xs';
                } else {
                    tab.className = 'sim-tab px-3.5 py-1.5 rounded-lg text-xs font-heading font-semibold transition cursor-pointer bg-[#202429] border border-[#343A40] text-[#F5F5F5] hover:border-[#F5C842]';
                }
            }
        }

        function selectSimProduct(index) {
            currentProductIndex = index;
            const p = simProducts[currentProductIndex];
            updateSimUI();
            appendSimLog(`Beralih ke SKU ${p.sku} (${p.name}). Stok sekarang: ${p.stock} unit.`);
        }

        function adjustSimStock(qty, direction) {
            const p = simProducts[currentProductIndex];

            if (direction === 'in') {
                p.stock += qty;
                updateSimUI();
                appendSimLog(`+${qty} unit masuk untuk ${p.sku}. Stok baru: ${p.stock} unit. Invoice dicatat.`, 'inbound');
            } else {
                if (p.stock < qty) {
                    appendSimLog(`Permintaan pengeluaran ${qty} unit melebihi sisa fisik ${p.stock} unit untuk ${p.sku}. Transaksi dibatalkan.`, 'error');
                    return;
                }
                p.stock -= qty;
                updateSimUI();
                appendSimLog(`-${qty} unit keluar untuk ${p.sku}. Sisa stok fisik: ${p.stock} unit.`, 'outbound');

                if (p.stock <= p.minStock) {
                    appendSimLog(`Stok ${p.sku} menyentuh batas minimum (${p.stock} <= ${p.minStock}). Notifikasi purchase order terpicu!`, 'warning');
                }
            }
        }

        function resetSimProduct() {
            const p = simProducts[currentProductIndex];
            p.stock = p.initialStock;
            updateSimUI();
            appendSimLog(`Stok ${p.sku} direset ke nilai awal: ${p.initialStock} unit.`);
        }

        function clearSimLogs() {
            const container = document.getElementById('sim-log-entries');
            if (container) {
                container.innerHTML = '<div class="text-[#A1A1AA]">[BERSIH] Riwayat log simulasi telah dikosongkan.</div>';
            }
        }

        // Animasi Storage Dot Matrix Gudang Hero (4 Baris, Mengisi dari kiri ke kanan)
        function initHeroBitAnimation() {
            const matrixContainer = document.getElementById('hero-dot-matrix');
            if (!matrixContainer) return;

            const dots = Array.from(matrixContainer.querySelectorAll('.hero-rack-dot'));
            const percentEl = document.getElementById('hero-bit-percent');
            const unitsEl = document.getElementById('hero-bit-units');
            const progressEl = document.getElementById('hero-bit-progressbar');
            const streamStatus = document.getElementById('hero-stream-status');

            const totalCols = 10;
            const totalDots = dots.length; // 40
            const maxUnits = 1000;
            let currentStep = 0;
            let isFilling = true;

            const emptyBg = '#343A40';
            const normalBg = '#F5C842';
            const warningBg = '#FB923C';
            const fullBg = '#F43F5E';

            function resetDots() {
                dots.forEach(d => {
                    d.style.backgroundColor = emptyBg;
                    d.style.boxShadow = 'none';
                    d.style.transform = 'scale(1)';
                });
                if (percentEl) percentEl.textContent = '0%';
                if (unitsEl) unitsEl.textContent = '0';
                if (progressEl) progressEl.style.width = '0%';
                if (streamStatus) {
                    streamStatus.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> LIVE TELEMETRI';
                }
            }

            function tickAnimation() {
                if (isFilling) {
                    currentStep++;
                    const targetCol = currentStep;

                    // Nyalakan kolom ke-targetCol pada 4 baris
                    const colDots = dots.filter(d => parseInt(d.dataset.col) === targetCol);
                    colDots.forEach((dot, idx) => {
                        setTimeout(() => {
                            let color = normalBg;
                            let glow = '0 0 8px rgba(245, 200, 66, 0.75)';
                            if (targetCol > 7) {
                                color = fullBg;
                                glow = '0 0 8px rgba(244, 63, 94, 0.75)';
                            } else if (targetCol > 4) {
                                color = warningBg;
                                glow = '0 0 8px rgba(251, 146, 60, 0.75)';
                            }
                            dot.style.backgroundColor = color;
                            dot.style.boxShadow = glow;
                            dot.style.transform = 'scale(1.3)';
                            setTimeout(() => {
                                dot.style.transform = 'scale(1)';
                            }, 180);
                        }, idx * 40);
                    });

                    // Update persentase dan unit
                    const filledCount = currentStep * 4;
                    const pct = Math.min(100, Math.round((filledCount / totalDots) * 100));
                    const units = Math.min(maxUnits, Math.round((pct / 100) * maxUnits));

                    if (percentEl) percentEl.textContent = pct + '%';
                    if (unitsEl) unitsEl.textContent = units.toLocaleString('id-ID');
                    if (progressEl) progressEl.style.width = pct + '%';

                    if (streamStatus) {
                        streamStatus.innerHTML = `<span class="w-1.5 h-1.5 rounded-full bg-[#F5C842] animate-ping"></span> RACK-0${targetCol} SINKRON`;
                    }

                    if (currentStep >= totalCols) {
                        isFilling = false;
                        if (streamStatus) {
                            streamStatus.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> 4 BARIS TERISI (100%)';
                        }
                        // Jeda 3 detik sebelum mengulang siklus
                        setTimeout(() => {
                            resetDots();
                            currentStep = 0;
                            isFilling = true;
                        }, 3000);
                    }
                }
            }

            resetDots();
            setInterval(tickAnimation, 450);
        }

        // Jalankan inisialisasi awal UI
        document.addEventListener('DOMContentLoaded', () => {
            updateSimUI();
            appendSimLog('Simulator siap. Coba klik tombol Inbound atau Outbound untuk menguji!', 'normal');
            initHeroBitAnimation();
        });
    </script>
</body>

</html>