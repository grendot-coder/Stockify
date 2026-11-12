@php
$landingAppName = \App\Models\Setting::get('app_name', 'Stockify');
$landingAppTagline = \App\Models\Setting::get('app_tagline', 'Warehouse Management System');
$landingAppLogo = \App\Models\Setting::get('app_logo');
$landingCompanyPhone = \App\Models\Setting::get('warehouse_phone') ?: \App\Models\Setting::get('company_phone');
$landingCompanyEmail = \App\Models\Setting::get('warehouse_email') ?: \App\Models\Setting::get('company_email');
$landingCompanyAddress = \App\Models\Setting::get('warehouse_address') ?: \App\Models\Setting::get('company_address');
@endphp
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $landingAppName }} â€” {{ $landingAppTagline }}</title>

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

        #scroll-progress {
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, #F5C842 0%, #FB923C 50%, #F5C842 100%);
            box-shadow: 0 0 12px rgba(245, 200, 66, 0.6);
            transform-origin: 0 50%;
            transform: scaleX(0);
            z-index: 100;
            pointer-events: none;
        }

        @media (prefers-reduced-motion: no-preference) {
            @supports ((animation-timeline: scroll())) {
                @keyframes grow-progress {
                    from {
                        transform: scaleX(0);
                    }

                    to {
                        transform: scaleX(1);
                    }
                }

                #scroll-progress {
                    animation: grow-progress auto linear;
                    animation-timeline: scroll();
                }
            }
        }

        .blueprint-grid {
            background-size: 48px 48px;
            background-image:
                linear-gradient(to right, rgba(52, 58, 64, 0.28) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(52, 58, 64, 0.28) 1px, transparent 1px);
            mask-image: radial-gradient(ellipse 75% 65% at 50% 30%, #000 35%, transparent 100%);
            -webkit-mask-image: radial-gradient(ellipse 75% 65% at 50% 30%, #000 35%, transparent 100%);
        }

        @keyframes ambient-glow {

            0%,
            100% {
                transform: translate(0, 0) scale(1);
                opacity: 0.2;
            }

            50% {
                transform: translate(24px, -24px) scale(1.18);
                opacity: 0.32;
            }
        }

        .animate-ambient-glow {
            animation: ambient-glow 9s ease-in-out infinite;
        }

        @keyframes shimmer-sweep {
            0% {
                transform: translateX(-150%) skewX(-20deg);
            }

            25%,
            100% {
                transform: translateX(250%) skewX(-20deg);
            }
        }

        .shimmer-btn {
            position: relative;
            overflow: hidden;
        }

        .shimmer-btn::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 45%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.38), transparent);
            animation: shimmer-sweep 4s cubic-bezier(0.4, 0, 0.2, 1) infinite;
            pointer-events: none;
        }

        .spotlight-card {
            position: relative;
            overflow: hidden;
            transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.25s ease, box-shadow 0.25s ease;
        }

        .spotlight-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(450px circle at var(--mouse-x, -999px) var(--mouse-y, -999px), rgba(245, 200, 66, 0.12), transparent 70%);
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: 1;
        }

        .spotlight-card:hover::before {
            opacity: 1;
        }

        .spotlight-card:hover {
            transform: translateY(-4px);
            border-color: rgba(245, 200, 66, 0.45);
            box-shadow: 0 16px 32px -12px rgba(0, 0, 0, 0.6), 0 0 24px -6px rgba(245, 200, 66, 0.15);
        }

        .reveal-on-scroll {
            opacity: 0;
            transform: translateY(24px);
            transition: opacity 0.7s cubic-bezier(0.16, 1, 0.3, 1), transform 0.7s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: opacity, transform;
        }

        .reveal-on-scroll.is-revealed {
            opacity: 1;
            transform: translateY(0);
        }

        @media (prefers-reduced-motion: reduce) {
            .reveal-on-scroll {
                opacity: 1 !important;
                transform: none !important;
                transition: none !important;
            }

            .animate-ambient-glow,
            .shimmer-btn::after,
            #scroll-progress {
                animation: none !important;
            }

            .spotlight-card:hover {
                transform: none !important;
            }
        }
    </style>
</head>

<body class="bg-[#101214] text-[#F5F5F5] antialiased selection:bg-[#F5C842] selection:text-[#101214] relative">
    <div id="scroll-progress" aria-hidden="true"></div>

    <header class="sticky top-0 z-50 backdrop-blur-md bg-[#181C20]/90 border-b border-[#343A40]">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">

            <a href="/" class="flex items-center gap-3 group"
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
                <a href="#fitur" class="hover:text-[#F5C842] transition">Fitur Inti</a>
                <a href="#arsitektur" class="hover:text-[#F5C842] transition">Alur Logistik</a>
                <a href="#akses-demo" class="hover:text-[#F5C842] transition">Akses Demo</a>
            </nav>

            <div class="flex items-center gap-3">
                @if(Auth::check())
                <a href="{{ route('dashboard') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#F5C842] text-[#101214] text-xs font-heading font-semibold hover:bg-[#E8B92E] transition shadow-xs">
                    <span>Dasbor Gudang</span>
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
                @endif
            </div>

        </div>
    </header>

    <main class="space-y-24 sm:space-y-32 pb-24">

        <section class="min-h-[calc(100dvh-5rem)] flex items-center max-w-7xl mx-auto px-6 py-8 relative overflow-hidden">
            <div class="absolute inset-0 blueprint-grid pointer-events-none" aria-hidden="true"></div>
            <div class="absolute -top-16 left-1/4 w-96 h-96 bg-[#F5C842]/10 rounded-full blur-[120px] pointer-events-none animate-ambient-glow" aria-hidden="true"></div>
            <div class="absolute top-1/2 -right-16 w-80 h-80 bg-amber-500/10 rounded-full blur-[100px] pointer-events-none animate-ambient-glow" style="animation-delay: -4.5s;" aria-hidden="true"></div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-center relative z-10">
                <div class="lg:col-span-7 space-y-6 text-left">

                    <h1 class="font-heading text-4xl sm:text-5xl lg:text-6xl font-bold tracking-tight text-[#F5F5F5] leading-[1.15]">
                        Kelola Persediaan Gudang dengan <span class="text-[#F5C842]">Hampir Dimana Saja.</span>
                    </h1>
                    <p class="text-sm sm:text-base text-[#A1A1AA] leading-relaxed max-w-xl">
                        Platform pergudangan untuk segala jenis usaha dan pusat distribusi modern.
                    </p>
                    <div class="space-y-3 pt-1">
                        <div class="flex items-center gap-3 text-xs sm:text-sm text-[#F5F5F5]">
                            <span class="w-5 h-5 rounded-full bg-[#F5C842]/15 border border-[#F5C842]/40 flex items-center justify-center shrink-0 text-[#F5C842]">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                            </span>
                            <span>Penunjuk Keterisian Rak & Matriks Gudang Real-Time</span>
                        </div>
                        <div class="flex items-center gap-3 text-xs sm:text-sm text-[#F5F5F5]">
                            <span class="w-5 h-5 rounded-full bg-[#F5C842]/15 border border-[#F5C842]/40 flex items-center justify-center shrink-0 text-[#F5C842]">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                            </span>
                            <span>Pencatatan Mutasi Dua Arah(Inbound & Outbound) Terotorisasi</span>
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
                        <a href="{{ route('login') }}"
                            class="shimmer-btn inline-flex items-center gap-2.5 px-6 py-3.5 rounded-full bg-[#F5C842] text-[#101214] text-xs font-heading font-bold hover:bg-[#E8B92E] transition shadow-lg shadow-[#F5C842]/20 hover:scale-[1.02] active:scale-[0.98]">
                            <span>Buka Portal Pergudangan</span>
                            <svg class="w-4 h-4 text-[#101214]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                        <a href="#akses-demo"
                            class="inline-flex items-center gap-2 px-5 py-3.5 rounded-full bg-[#181C20] border border-[#343A40] text-[#F5F5F5] text-xs font-heading font-semibold hover:border-[#F5C842] hover:text-[#F5C842] transition">
                            <span>Akses Akun Demo &rarr;</span>
                        </a>
                    </div>

                </div>

                <div class="lg:col-span-5 relative">

                    <div class="bg-[#202429] rounded-2xl border border-[#343A40] p-5 shadow-xl relative overflow-hidden backdrop-blur-sm">

                        <div class="pb-3 border-b border-[#343A40]/80">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-mono uppercase tracking-widest text-[#A1A1AA]">Afraid of Full Rack? Check this out</span>
                                <span id="hero-active-coord" class="text-[10px] font-mono text-[#F5C842] font-semibold tracking-wider">STANDBY</span>
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
                        <span style="width: 8px; height: 8px; border-radius: 9999px; background-color: #F43F5E;" class="inline-block shadow-[0_0_6px_rgba(244,63,94,0.6)]"></span> Kapasitas Maksimal (100%)
                    </span>
                </div>
            </div>
            </div>
        </section>

        <div class="max-w-7xl mx-auto px-6 pt-6 pb-12 border-t border-[#343A40]">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-left">
                <div class="spotlight-card reveal-on-scroll p-6 rounded-2xl bg-[#202429] border border-[#343A40] shadow-xs hover:border-[#F5C842]/40 transition">
                    <div class="text-3xl font-bold font-heading tabular-nums text-[#F5C842] counter-stat" data-target="{{ $productCount > 0 ? $productCount : 15000 }}" data-suffix="{{ $productCount > 0 ? ' SKU' : '+' }}">
                        0{{ $productCount > 0 ? ' SKU' : '+' }}
                    </div>
                    <div class="text-xs font-semibold text-[#F5F5F5] mt-1.5 font-heading">Kapasitas Multi-Atribut</div>
                    <div class="text-[11px] text-[#A1A1AA] mt-0.5">Varian ukuran, warna, nomor seri, dan ambang batas stok.</div>
                </div>

                <div class="spotlight-card reveal-on-scroll p-6 rounded-2xl bg-[#202429] border border-[#343A40] shadow-xs hover:border-[#F5C842]/40 transition" style="transition-delay: 100ms;">
                    <div class="text-3xl font-bold font-heading tabular-nums text-[#F5C842] counter-stat" data-target="{{ $transactionCount > 0 ? $transactionCount : 8420 }}" data-suffix="{{ $transactionCount > 0 ? ' Mutasi' : ' Mutasi Terverifikasi' }}">
                        0{{ $transactionCount > 0 ? ' Mutasi' : ' Mutasi Terverifikasi' }}
                    </div>
                    <div class="text-xs font-semibold text-[#F5F5F5] mt-1.5 font-heading">Alur Mutasi Terotorisasi</div>
                    <div class="text-[11px] text-[#A1A1AA] mt-0.5">Pencatatan invoice digital Inbound & Outbound dengan verifikasi ganda.</div>
                </div>

                <div class="spotlight-card reveal-on-scroll p-6 rounded-2xl bg-[#202429] border border-[#343A40] shadow-xs hover:border-[#F5C842]/40 transition" style="transition-delay: 200ms;">
                    <div class="text-3xl font-bold font-heading tabular-nums text-[#F5C842] counter-stat" data-target="{{ $supplierCount > 0 ? $supplierCount : 99.98 }}" data-suffix="{{ $supplierCount > 0 ? ' Supplier' : '%' }}" data-is-decimal="{{ $supplierCount > 0 ? 'false' : 'true' }}">
                        0{{ $supplierCount > 0 ? ' Mitra Supplier' : '%' }}
                    </div>
                    <div class="text-xs font-semibold text-[#F5F5F5] mt-1.5 font-heading">Jaringan Supplier Aktif</div>
                    <div class="text-[11px] text-[#A1A1AA] mt-0.5">Kemitraan terintegrasi dengan alamat kontak & riwayat pasokan.</div>
                </div>
            </div>
        </div>






        <section id="fitur" class="max-w-6xl mx-auto px-6">

            <div class="text-center max-w-2xl mx-auto mb-12 reveal-on-scroll">
                <span class="text-[11px] uppercase tracking-[0.2em] text-[#F5C842] font-semibold">Kapabilitas Sistem</span>
                <h2 class="font-heading text-3xl sm:text-4xl font-bold text-[#F5F5F5] mt-1">
                    Dirancang untuk Membantu UMKM, Unicorn, maupun Perusahaan Besar
                </h2>
                <p class="mt-2 text-xs text-[#A1A1AA]">
                    Stockify terintegrasi langsung ke basis data terpusat dengan integritas transaksi atomik.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

                <div class="spotlight-card reveal-on-scroll p-6 rounded-2xl bg-[#202429] border border-[#343A40] shadow-xs flex flex-col justify-between group" style="transition-delay: 0ms;">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-[#181C20] text-[#F5C842] flex items-center justify-center font-bold text-sm mb-4 border border-[#343A40] group-hover:border-[#F5C842]/40 group-hover:scale-105 transition-all duration-300">
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
                        <a href="{{ route('login') }}" class="text-xs font-heading font-semibold text-[#F5C842] hover:underline inline-flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                            <span>Jelajahi Katalog</span> &rarr;
                        </a>
                    </div>
                </div>

                <div class="spotlight-card reveal-on-scroll p-6 rounded-2xl bg-[#202429] border border-[#343A40] shadow-xs flex flex-col justify-between group" style="transition-delay: 100ms;">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-[#181C20] text-[#F5C842] flex items-center justify-center font-bold text-sm mb-4 border border-[#343A40] group-hover:border-[#F5C842]/40 group-hover:scale-105 transition-all duration-300">
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
                        <a href="{{ route('login') }}" class="text-xs font-heading font-semibold text-[#F5C842] hover:underline inline-flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                            <span>Alur Transaksi</span> &rarr;
                        </a>
                    </div>
                </div>

                <div class="spotlight-card reveal-on-scroll p-6 rounded-2xl bg-[#202429] border border-[#343A40] shadow-xs flex flex-col justify-between group" style="transition-delay: 200ms;">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-[#181C20] text-[#F5C842] flex items-center justify-center font-bold text-sm mb-4 border border-[#343A40] group-hover:border-[#F5C842]/40 group-hover:scale-105 transition-all duration-300">
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
                        <a href="{{ route('login') }}" class="text-xs font-heading font-semibold text-[#F5C842] hover:underline inline-flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                            <span>Rekonsiliasi Fisik</span> &rarr;
                        </a>
                    </div>
                </div>

                <div class="spotlight-card reveal-on-scroll p-6 rounded-2xl bg-[#202429] border border-[#343A40] shadow-xs flex flex-col justify-between group" style="transition-delay: 300ms;">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-[#181C20] text-[#F5C842] flex items-center justify-center font-bold text-sm mb-4 border border-[#343A40] group-hover:border-[#F5C842]/40 group-hover:scale-105 transition-all duration-300">
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
                        <a href="{{ route('login') }}" class="text-xs font-heading font-semibold text-[#F5C842] hover:underline inline-flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                            <span>Modul Laporan</span> &rarr;
                        </a>
                    </div>
                </div>

            </div>

        </section>

        <section id="arsitektur" class="max-w-6xl mx-auto px-6">

            <div class="reveal-on-scroll p-8 sm:p-12 rounded-3xl bg-[#202429] border border-[#343A40] shadow-xs relative overflow-hidden">
                <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-[#F5C842]/5 rounded-full blur-3xl pointer-events-none"></div>

                <div class="max-w-2xl relative z-10">
                    <span class="text-[11px] uppercase tracking-[0.2em] text-[#F5C842] font-semibold">Standar Operasional</span>
                    <h2 class="font-heading text-3xl sm:text-4xl font-bold text-[#F5F5F5] mt-1">
                        Siklus Mutasi Stok Tanpa Ambang Human Error.
                    </h2>
                    <p class="mt-3 text-xs sm:text-sm text-[#A1A1AA] leading-relaxed">
                        Setiap perubahan jumlah barang diikat dengan nomor bukti transaksi (Invoice), validasi kapasitas stok sebelum barang keluar, dan status konfirmasi bertingkat.
                    </p>
                </div>

                <div class="mt-10 grid grid-cols-1 md:grid-cols-3 gap-6 text-xs relative z-10">
                    <div class="spotlight-card p-6 rounded-2xl bg-[#181C20] border border-[#343A40] space-y-2.5">
                        <div class="w-8 h-8 rounded-lg bg-[#F5C842] text-[#101214] flex items-center justify-center font-heading font-bold shadow-xs">1</div>
                        <h4 class="font-heading font-bold text-[#F5F5F5] text-sm">Penerimaan Inbound</h4>
                        <p class="text-[#A1A1AA] leading-relaxed">
                            Pencatatan nomor surat jalan supplier, SKU barang, dan kuantitas masuk. Stok fisik langsung terbarui secara atomic dalam database.
                        </p>
                    </div>

                    <div class="spotlight-card p-6 rounded-2xl bg-[#181C20] border border-[#343A40] space-y-2.5">
                        <div class="w-8 h-8 rounded-lg bg-[#F5C842] text-[#101214] flex items-center justify-center font-heading font-bold shadow-xs">2</div>
                        <h4 class="font-heading font-bold text-[#F5F5F5] text-sm">Validasi Stok Outbound</h4>
                        <p class="text-[#A1A1AA] leading-relaxed">
                            Sistem secara proaktif menolak pengeluaran jika permintaan melebihi sisa fisik di gudang. Dilengkapi klasifikasi alasan mutasi.
                        </p>
                    </div>

                    <div class="spotlight-card p-6 rounded-2xl bg-[#181C20] border border-[#343A40] space-y-2.5">
                        <div class="w-8 h-8 rounded-lg bg-[#F5C842] text-[#101214] flex items-center justify-center font-heading font-bold shadow-xs">3</div>
                        <h4 class="font-heading font-bold text-[#F5F5F5] text-sm">Otorisasi & Audit Trail</h4>
                        <p class="text-[#A1A1AA] leading-relaxed">
                            Transaksi pending ditinjau oleh Manajer Gudang. Setiap langkah tercatat dalam tabel log aktivitas dengan alamat IP dan timestamp.
                        </p>
                    </div>
                </div>
            </div>

        </section>

        <section id="akses-demo" class="max-w-5xl mx-auto px-6">

            <div class="text-center mb-10 reveal-on-scroll">
                <span class="text-[11px] uppercase tracking-[0.2em] text-[#F5C842] font-semibold">Sandbox & Demo Eksklusif</span>
                <h2 class="font-heading text-3xl sm:text-4xl font-bold text-[#F5F5F5] mt-1">
                    Uji Coba Langsung Berdasarkan Peran Pengguna.
                </h2>
                <p class="text-xs text-[#A1A1AA] mt-2">
                    Pilih peran di bawah ini untuk langsung masuk ke dashboard sistem tanpa perlu mengisi formulir login.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                <div class="spotlight-card reveal-on-scroll p-6 rounded-2xl bg-[#202429] border border-[#343A40] shadow-xs flex flex-col justify-between space-y-5" style="transition-delay: 0ms;">
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
                            class="w-full py-3 px-4 rounded-xl bg-[#F5C842] hover:bg-[#E8B92E] text-[#101214] text-xs font-heading font-semibold transition shadow-xs cursor-pointer flex items-center justify-center gap-1.5 active:scale-95">
                            <span>Masuk sebagai Admin</span> &rarr;
                        </button>
                    </form>
                </div>

                <div class="spotlight-card reveal-on-scroll p-6 rounded-2xl bg-[#202429] border border-[#343A40] shadow-xs flex flex-col justify-between space-y-5" style="transition-delay: 120ms;">
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
                            class="w-full py-3 px-4 rounded-xl bg-[#181C20] border border-[#343A40] hover:border-[#F5C842] text-[#F5F5F5] text-xs font-heading font-semibold hover:bg-[#2A3036] hover:text-[#F5C842] transition cursor-pointer flex items-center justify-center gap-1.5 active:scale-95">
                            <span>Masuk sebagai Manajer</span> &rarr;
                        </button>
                    </form>
                </div>

                <div class="spotlight-card reveal-on-scroll p-6 rounded-2xl bg-[#202429] border border-[#343A40] shadow-xs flex flex-col justify-between space-y-5" style="transition-delay: 240ms;">
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

                <div class="lg:col-span-2 sm:col-span-1 space-y-4">
                    <span class="text-xs font-mono tracking-widest uppercase text-[#F5C842] font-semibold block">Fitur Sistem</span>
                    <ul class="space-y-3 text-xs sm:text-[13px]">
                        <li><a href="#fitur" class="text-[#A1A1AA] hover:text-[#F5C842] transition">Katalog SKU</a></li>
                        <li><a href="#fitur" class="text-[#A1A1AA] hover:text-[#F5C842] transition">Mutasi Inbound & Outbound</a></li>
                        <li><a href="#fitur" class="text-[#A1A1AA] hover:text-[#F5C842] transition">Stock Opname Fisik</a></li>
                        <li><a href="#arsitektur" class="text-[#A1A1AA] hover:text-[#F5C842] transition">Alur Logistik 4-Pilar</a></li>
                    </ul>
                </div>

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

            <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-6 text-xs text-[#A1A1AA]">


                <div class="text-xs text-[#A1A1AA]">
                    &copy; {{ date('Y') }} {{ $landingAppName }}.
                </div>

                <a href="#" onclick="window.scrollTo({top: 0, behavior: 'smooth'}); return false;" class="text-xs text-[#A1A1AA] hover:text-[#F5C842] transition flex items-center gap-1.5">
                    <span>Kembali ke atas</span> &uarr;
                </a>
            </div>

        </div>
    </footer>

    <script>
        function initHeroBitAnimation() {
            const matrixContainer = document.getElementById('hero-dot-matrix');
            if (!matrixContainer) return;

            const dots = Array.from(matrixContainer.querySelectorAll('.hero-rack-dot'));
            const percentEl = document.getElementById('hero-bit-percent');
            const unitsEl = document.getElementById('hero-bit-units');
            const progressEl = document.getElementById('hero-bit-progressbar');
            const coordEl = document.getElementById('hero-active-coord');

            const totalCols = 10;
            const totalDots = dots.length;
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
                if (coordEl) coordEl.textContent = 'STANDBY';
            }

            function tickAnimation() {
                if (isFilling) {
                    currentStep++;
                    const targetCol = currentStep;

                    const colDots = dots.filter(d => parseInt(d.dataset.col) === targetCol);
                    colDots.forEach((dot, idx) => {
                        setTimeout(() => {
                            let color = normalBg;
                            let glow = '0 0 10px rgba(245, 200, 66, 0.75)';
                            if (targetCol > 7) {
                                color = fullBg;
                                glow = '0 0 10px rgba(244, 63, 94, 0.75)';
                            } else if (targetCol > 4) {
                                color = warningBg;
                                glow = '0 0 10px rgba(251, 146, 60, 0.75)';
                            }
                            dot.style.backgroundColor = color;
                            dot.style.boxShadow = glow;
                            dot.style.transform = 'scale(1.25)';
                            setTimeout(() => {
                                dot.style.transform = 'scale(1)';
                            }, 180);
                        }, idx * 40);
                    });

                    const filledCount = currentStep * 4;
                    const pct = Math.min(100, Math.round((filledCount / totalDots) * 100));
                    const units = Math.min(maxUnits, Math.round((pct / 100) * maxUnits));

                    if (percentEl) percentEl.textContent = pct + '%';
                    if (unitsEl) unitsEl.textContent = units.toLocaleString('id-ID');
                    if (progressEl) progressEl.style.width = pct + '%';
                    if (coordEl) coordEl.textContent = `ROW 01-04 \u2022 COL 0${targetCol}`;

                    if (currentStep >= totalCols) {
                        isFilling = false;
                        if (coordEl) coordEl.textContent = 'MAX CAPACITY (40/40)';
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

        function initSpotlightCards() {
            const cards = document.querySelectorAll('.spotlight-card');
            cards.forEach(card => {
                card.addEventListener('mousemove', e => {
                    const rect = card.getBoundingClientRect();
                    const x = e.clientX - rect.left;
                    const y = e.clientY - rect.top;
                    card.style.setProperty('--mouse-x', `${x}px`);
                    card.style.setProperty('--mouse-y', `${y}px`);
                });
            });
        }

        function initScrollReveals() {
            const elements = document.querySelectorAll('.reveal-on-scroll');
            if (!elements.length) return;

            const observer = new IntersectionObserver((entries, obs) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-revealed');
                        obs.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.12,
                rootMargin: '0px 0px -30px 0px'
            });

            elements.forEach(el => observer.observe(el));
        }

        function initCounters() {
            const counters = document.querySelectorAll('.counter-stat');
            if (!counters.length) return;

            const observer = new IntersectionObserver((entries, obs) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const el = entry.target;
                        const target = parseFloat(el.getAttribute('data-target')) || 0;
                        const suffix = el.getAttribute('data-suffix') || '';
                        const isDecimal = el.getAttribute('data-is-decimal') === 'true';
                        const duration = 1600;
                        const startTime = performance.now();

                        function update(currentTime) {
                            const elapsed = currentTime - startTime;
                            const progress = Math.min(elapsed / duration, 1);
                            const ease = progress === 1 ? 1 : 1 - Math.pow(2, -10 * progress);
                            const currentVal = target * ease;

                            if (isDecimal) {
                                el.textContent = currentVal.toFixed(2) + suffix;
                            } else {
                                el.textContent = Math.floor(currentVal).toLocaleString('id-ID') + suffix;
                            }

                            if (progress < 1) {
                                requestAnimationFrame(update);
                            }
                        }

                        requestAnimationFrame(update);
                        obs.unobserve(el);
                    }
                });
            }, {
                threshold: 0.3
            });

            counters.forEach(c => observer.observe(c));
        }

        function initScrollProgressFallback() {
            if (!window.CSS || !CSS.supports('animation-timeline', 'scroll()')) {
                const progress = document.getElementById('scroll-progress');
                if (!progress) return;

                window.addEventListener('scroll', () => {
                    const scrollable = document.documentElement.scrollHeight - window.innerHeight;
                    if (scrollable > 0) {
                        const scrolled = window.scrollY;
                        const pct = Math.min(Math.max(scrolled / scrollable, 0), 1);
                        progress.style.transform = `scaleX(${pct})`;
                    }
                }, {
                    passive: true
                });
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            initHeroBitAnimation();
            initSpotlightCards();
            initScrollReveals();
            initCounters();
            initScrollProgressFallback();

            let pageLoadTime = Date.now();
            document.addEventListener('visibilitychange', () => {
                if (document.visibilityState === 'visible') {
                    if (Date.now() - pageLoadTime > 20 * 60 * 1000) {
                        window.location.reload();
                    }
                }
            });
        });
    </script>
</body>

</html>
