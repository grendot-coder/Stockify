<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Stockify — Warehouse Management System</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600&family=IBM+Plex+Sans:wght@400;500;600&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            background-color: #FAF7F4;
            color: #292524;
            font-family: 'IBM Plex Sans', sans-serif;
        }

        h1, h2, h3, h4, h5, h6, .font-heading, .font-brand, .font-editorial {
            font-family: 'Space Grotesk', sans-serif;
        }

        .tabular-nums, table, [data-tabular], .font-mono {
            font-variant-numeric: tabular-nums;
            font-feature-settings: "tnum" 1;
        }
    </style>
</head>

<body class="bg-[#FAF7F4] text-[#292524] antialiased selection:bg-[#F5E6DE] selection:text-[#1C1917]">

    <!-- Navigasi Utama (Sticky Nav) -->
    <header class="sticky top-0 z-50 backdrop-blur-md bg-[#FFFFFF]/90 border-b border-[#E7E0DA]">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">

            <!-- Brand Logo -->
            <a href="/" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-xl bg-[#F5E6DE] border border-[#BD6745]/30 flex items-center justify-center transition group-hover:bg-[#A34E30] group-hover:text-[#FFFFFF]">
                    <span class="text-[#A34E30] group-hover:text-[#FFFFFF] font-heading font-bold text-base tracking-wider transition">S</span>
                </div>
                <div class="flex flex-col">
                    <span class="font-heading text-xl font-bold tracking-tight text-[#292524]">Stockify</span>
                    <span class="text-[9px] uppercase tracking-[0.2em] text-[#78716C] font-semibold">Warehouse Management System</span>
                </div>
            </a>

            <!-- Nav Links Fungsional -->
            <nav class="hidden md:flex items-center gap-7 text-xs font-semibold text-[#78716C]">
                <a href="#showroom" class="hover:text-[#A34E30] transition">Showroom Video</a>
                <a href="#klien" class="hover:text-[#A34E30] transition">Mitra Gudang</a>
                <a href="#fitur" class="hover:text-[#A34E30] transition">Fitur Inti</a>
                <a href="#arsitektur" class="hover:text-[#A34E30] transition">Alur Logistik</a>
                <a href="#akses-demo" class="hover:text-[#A34E30] transition">Akses Demo</a>
            </nav>

            <!-- CTA Masuk / Dashboard (DEEP TERRACOTTA #A34E30) -->
            <div class="flex items-center gap-3">
                @auth
                <a href="{{ route('dashboard') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#A34E30] text-white text-xs font-heading font-semibold hover:bg-[#8C3D22] transition shadow-xs">
                    <span>Buka Dashboard</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </a>
                @else
                <a href="{{ route('login') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#A34E30] text-white text-xs font-heading font-semibold hover:bg-[#8C3D22] transition shadow-xs">
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

        <!-- ============================================================ -->
        <!-- 1. HERO SECTION (SHOPIFY-GRADE EDITORIAL)                    -->
        <!-- ============================================================ -->
        <section class="relative pt-16 sm:pt-24 max-w-5xl mx-auto px-6 text-center">

            <!-- Pill Status Badge -->
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-[#FFFFFF] border border-[#E7E0DA] text-xs text-[#78716C] mb-8 shadow-xs">
                <span class="w-2 h-2 rounded-full bg-[#A34E30]"></span>
                <span class="text-[#292524] font-semibold">Stockify Enterprise</span>
                <span class="text-[#E7E0DA]">•</span>
                <span>Kendali Gudang & Inventaris Terintegrasi</span>
            </div>

            <!-- Headline Tajam Space Grotesk -->
            <h1 class="font-heading text-4xl sm:text-6xl lg:text-7xl font-bold tracking-tight text-[#292524] leading-[1.1]">
                Kelola Persediaan Gudang dengan Akurasi dan Presisi Tinggi.
            </h1>

            <!-- Subheading Editorial -->
            <p class="mt-6 max-w-2xl mx-auto text-sm sm:text-base text-[#78716C] leading-relaxed">
                Platform pergudangan terpadu yang dirancang untuk brand ritel, pusat distribusi, dan rantai pasok modern. Mengeliminasi selisih stok melalui pencatatan mutasi ganda dan audit log tanpa kompromi.
            </p>

            <!-- Dua Tombol Aksi 100% Fungsional -->
            <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                <!-- Tombol Primer: Deep Terracotta (#A34E30) -->
                <a href="#akses-demo"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-7 py-3.5 rounded-xl bg-[#A34E30] text-white text-xs font-heading font-semibold hover:bg-[#8C3D22] transition shadow-xs">
                    <span>Mulai Akses Demo 1-Klik</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 13.5L12 21m0 0l-7.5-7.5M12 21V3" />
                    </svg>
                </a>
                
                <!-- Tombol Sekunder: Scroll & Play Video Langsung -->
                <button type="button" onclick="playShowroomVideo()"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-7 py-3.5 rounded-xl bg-[#FFFFFF] border border-[#E7E0DA] text-[#292524] text-xs font-heading font-semibold hover:border-[#A34E30] hover:text-[#A34E30] transition shadow-xs cursor-pointer">
                    <svg class="w-4 h-4 text-[#A34E30]" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M6.3 2.841A1.5 1.5 0 004 4.11v11.78a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z" />
                    </svg>
                    <span>Tonton Video Showroom</span>
                </button>
            </div>

            <!-- Tiga Metrik Utama Real-Time dari Database -->
            <div class="mt-16 pt-12 border-t border-[#E7E0DA] grid grid-cols-1 sm:grid-cols-3 gap-6 text-left">
                <div class="p-6 rounded-2xl bg-[#FFFFFF] border border-[#E7E0DA] shadow-xs hover:border-[#A34E30]/40 transition">
                    <div class="text-3xl font-bold font-heading tabular-nums text-[#A34E30]">
                        {{ $productCount > 0 ? number_format($productCount) . ' SKU' : '15.000+' }}
                    </div>
                    <div class="text-xs font-semibold text-[#292524] mt-1.5 font-heading">Kapasitas Multi-Atribut</div>
                    <div class="text-[11px] text-[#78716C] mt-0.5">Varian ukuran, warna, nomor seri, dan ambang batas stok.</div>
                </div>

                <div class="p-6 rounded-2xl bg-[#FFFFFF] border border-[#E7E0DA] shadow-xs hover:border-[#A34E30]/40 transition">
                    <div class="text-3xl font-bold font-heading tabular-nums text-[#A34E30]">
                        {{ $transactionCount > 0 ? number_format($transactionCount) . ' Mutasi' : 'Real-time' }}
                    </div>
                    <div class="text-xs font-semibold text-[#292524] mt-1.5 font-heading">Alur Mutasi Terotorisasi</div>
                    <div class="text-[11px] text-[#78716C] mt-0.5">Pencatatan invoice digital Inbound & Outbound dengan verifikasi ganda.</div>
                </div>

                <div class="p-6 rounded-2xl bg-[#FFFFFF] border border-[#E7E0DA] shadow-xs hover:border-[#A34E30]/40 transition">
                    <div class="text-3xl font-bold font-heading tabular-nums text-[#A34E30]">
                        {{ $supplierCount > 0 ? number_format($supplierCount) . ' Rekanan' : '99.98%' }}
                    </div>
                    <div class="text-xs font-semibold text-[#292524] mt-1.5 font-heading">Jaringan Supplier Aktif</div>
                    <div class="text-[11px] text-[#78716C] mt-0.5">Kemitraan terintegrasi dengan alamat kontak & riwayat pasokan.</div>
                </div>
            </div>

        </section>


        <!-- ============================================================ -->
        <!-- 2. PORTOFOLIO VIDEO OF THE COMPANY (SHOWROOM SECTION)        -->
        <!-- ============================================================ -->
        <section id="showroom" class="max-w-6xl mx-auto px-6">

            <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <span class="text-[11px] uppercase tracking-[0.2em] text-[#A34E30] font-semibold">Portofolio Showroom</span>
                    <h2 class="font-heading text-3xl sm:text-4xl font-bold text-[#292524] mt-1">
                        Di Balik Ketepatan Distribusi Skala Modern.
                    </h2>
                </div>
                <p class="text-xs text-[#78716C] max-w-md leading-relaxed">
                    {{ $companyVideo['director_note'] }}
                </p>
            </div>

            <!-- Cinema Frame Video Player Fungsional Penuh -->
            <div class="relative rounded-3xl bg-[#FFFFFF] border border-[#E7E0DA] overflow-hidden shadow-md group">

                <!-- Status Bar Video -->
                <div class="px-5 py-3.5 bg-[#FAF7F4] border-b border-[#E7E0DA] flex items-center justify-between text-xs text-[#78716C]">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-[#A34E30] animate-pulse"></span>
                        <span class="font-mono text-[11px] text-[#292524] font-medium">{{ $companyVideo['title'] }}</span>
                    </div>
                    <div class="flex items-center gap-3 font-mono text-[11px]">
                        <span class="px-2.5 py-0.5 rounded-md bg-[#FFFFFF] border border-[#E7E0DA] text-[#A34E30] font-semibold">{{ $companyVideo['resolution'] }}</span>
                        <span>{{ $companyVideo['duration'] }}</span>
                    </div>
                </div>

                <!-- Video Element & Playable Container -->
                <div class="relative aspect-video bg-[#1C1917] flex items-center justify-center overflow-hidden">

                    <video id="company-portfolio-video"
                        class="w-full h-full object-cover"
                        poster="{{ $companyVideo['poster_image'] }}"
                        preload="metadata"
                        controls
                        playsinline>
                        <source src="{{ $companyVideo['video_url'] }}" type="video/mp4">
                        Peramban Anda tidak mendukung elemen video HTML5.
                    </video>

                    <!-- Tombol Cover Play Trigger -->
                    <div id="video-overlay" class="absolute inset-0 bg-[#1C1917]/35 flex flex-col items-center justify-center transition-all duration-300">
                        <button type="button" id="btn-play-video"
                            class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-[#A34E30] hover:bg-[#8C3D22] text-white flex items-center justify-center shadow-lg transition-transform transform group-hover:scale-105 active:scale-95 focus:outline-none cursor-pointer">
                            <svg class="w-7 h-7 sm:w-8 sm:h-8 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M6.3 2.841A1.5 1.5 0 004 4.11v11.78a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z" />
                            </svg>
                        </button>
                        <p class="mt-4 text-xs font-heading font-semibold text-[#FFFFFF] tracking-wide">Klik untuk Memutar Video Showroom</p>
                    </div>
                </div>

                <!-- Footer Informasi Teknis Video -->
                <div class="p-6 bg-[#FFFFFF] border-t border-[#E7E0DA] grid grid-cols-1 md:grid-cols-3 gap-6 text-xs">
                    <div>
                        <span class="text-[#78716C] block mb-1 font-semibold uppercase tracking-wider text-[10px]">Fokus Sistem:</span>
                        <p class="text-[#292524] font-medium leading-relaxed">
                            Integrasi alur mutasi barang Inbound & Outbound dengan pencatatan invoice digital real-time.
                        </p>
                    </div>
                    <div>
                        <span class="text-[#78716C] block mb-1 font-semibold uppercase tracking-wider text-[10px]">Keamanan & Wewenang:</span>
                        <p class="text-[#292524] font-medium leading-relaxed">
                            Pemisahan wewenang otorisasi multi-role (RBAC) untuk menjaga integritas data stock opname.
                        </p>
                    </div>
                    <div>
                        <span class="text-[#78716C] block mb-1 font-semibold uppercase tracking-wider text-[10px]">Kustomisasi Data Video:</span>
                        <p class="text-[#78716C] leading-relaxed">
                            Parameter URL dan info video ini dikelola secara dinamis melalui <code class="text-[#A34E30] bg-[#F5E6DE] px-1.5 py-0.5 rounded font-mono">LandingController.php</code>.
                        </p>
                    </div>
                </div>

            </div>

        </section>


        <!-- ============================================================ -->
        <!-- 3. LIST OF COMPANIES USING STOCKIFY (MITRA PERGUDANGAN)     -->
        <!-- ============================================================ -->
        <section id="klien" class="max-w-6xl mx-auto px-6">

            <div class="text-center max-w-2xl mx-auto mb-12">
                <span class="text-[11px] uppercase tracking-[0.2em] text-[#A34E30] font-semibold">Portofolio Klien & Ekosistem</span>
                <h2 class="font-heading text-3xl sm:text-4xl font-bold text-[#292524] mt-1">
                    Dipercaya oleh Studio & Jaringan Retail Terkemuka.
                </h2>
                <p class="mt-2 text-xs text-[#78716C]">
                    Mulai dari studio kerajinan independen hingga jaringan distribusi nasional mengandalkan Stockify untuk menjaga ketepatan perputaran stok.
                </p>
            </div>

            <!-- Client Grid Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($clientCompanies as $company)
                <div class="p-6 rounded-2xl bg-[#FFFFFF] border border-[#E7E0DA] hover:border-[#A34E30]/60 hover:shadow-md transition-all duration-200 flex flex-col justify-between group">

                    <div>
                        <!-- Header Monogram & Badge -->
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-xl bg-[#F5E6DE] border border-[#BD6745]/20 flex items-center justify-center font-heading text-lg text-[#A34E30] font-bold group-hover:bg-[#A34E30] group-hover:text-[#FFFFFF] transition">
                                {{ $company['monogram'] }}
                            </div>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold tracking-wide bg-[#FAF7F4] text-[#A34E30] border border-[#E7E0DA]">
                                {{ $company['badge'] }}
                            </span>
                        </div>

                        <!-- Name & Industry -->
                        <h3 class="font-heading text-xl font-bold text-[#292524]">
                            {{ $company['name'] }}
                        </h3>
                        <p class="text-xs text-[#78716C] mt-0.5">
                            {{ $company['industry'] }}
                        </p>

                        <!-- Testimonial Quote -->
                        <blockquote class="mt-4 text-xs text-[#292524] italic leading-relaxed pl-3 border-l-2 border-[#A34E30]">
                            “{{ $company['quote'] }}”
                        </blockquote>
                    </div>

                    <!-- Stats Footer -->
                    <div class="mt-6 pt-4 border-t border-[#E7E0DA] flex items-center justify-between text-[11px]">
                        <div>
                            <span class="text-[#78716C] block text-[10px]">Cakupan Gudang</span>
                            <span class="font-semibold text-[#292524]">{{ $company['warehouses'] }}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-[#78716C] block text-[10px]">Total Unit Terkelola</span>
                            <span class="font-semibold text-[#A34E30] font-mono">{{ $company['units_tracked'] }}</span>
                        </div>
                    </div>

                </div>
                @endforeach
            </div>

        </section>


        <!-- ============================================================ -->
        <!-- 4. EMPAT PILAR FITUR INTI GUDANG (SHOPIFY FEATURE CARDS)     -->
        <!-- ============================================================ -->
        <section id="fitur" class="max-w-6xl mx-auto px-6">

            <div class="text-center max-w-2xl mx-auto mb-12">
                <span class="text-[11px] uppercase tracking-[0.2em] text-[#A34E30] font-semibold">Kapabilitas Sistem</span>
                <h2 class="font-heading text-3xl sm:text-4xl font-bold text-[#292524] mt-1">
                    Dirancang untuk Menjawab Kompleksitas Fisik Gudang.
                </h2>
                <p class="mt-2 text-xs text-[#78716C]">
                    Setiap modul terhubung langsung ke basis data terpusat dengan integritas transaksi atomik.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

                <!-- Pilar 1: Master Data -->
                <div class="p-6 rounded-2xl bg-[#FFFFFF] border border-[#E7E0DA] hover:border-[#A34E30]/50 shadow-xs flex flex-col justify-between group transition">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-[#F5E6DE] text-[#A34E30] flex items-center justify-center font-bold text-sm mb-4">
                            01
                        </div>
                        <h3 class="font-heading text-lg font-bold text-[#292524] group-hover:text-[#A34E30] transition">
                            Master Data & SKU
                        </h3>
                        <p class="text-xs text-[#78716C] mt-2 leading-relaxed">
                            Generate kode SKU dinamis, atribut multi-varian (ukuran, warna, garansi), dan ambang batas minimum stok.
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-[#E7E0DA]">
                        <a href="{{ route('login') }}" class="text-xs font-heading font-semibold text-[#A34E30] hover:underline inline-flex items-center gap-1">
                            <span>Jelajahi Katalog</span> &rarr;
                        </a>
                    </div>
                </div>

                <!-- Pilar 2: Mutasi Arus Barang -->
                <div class="p-6 rounded-2xl bg-[#FFFFFF] border border-[#E7E0DA] hover:border-[#A34E30]/50 shadow-xs flex flex-col justify-between group transition">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-[#F5E6DE] text-[#A34E30] flex items-center justify-center font-bold text-sm mb-4">
                            02
                        </div>
                        <h3 class="font-heading text-lg font-bold text-[#292524] group-hover:text-[#A34E30] transition">
                            Mutasi Inbound & Outbound
                        </h3>
                        <p class="text-xs text-[#78716C] mt-2 leading-relaxed">
                            Validasi sisa stok ketat sebelum pengeluaran barang. Dilengkapi alur otorisasi approval terpisah Manajer dan Staff.
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-[#E7E0DA]">
                        <a href="{{ route('login') }}" class="text-xs font-heading font-semibold text-[#A34E30] hover:underline inline-flex items-center gap-1">
                            <span>Alur Transaksi</span> &rarr;
                        </a>
                    </div>
                </div>

                <!-- Pilar 3: Stock Opname & Audit -->
                <div class="p-6 rounded-2xl bg-[#FFFFFF] border border-[#E7E0DA] hover:border-[#A34E30]/50 shadow-xs flex flex-col justify-between group transition">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-[#F5E6DE] text-[#A34E30] flex items-center justify-center font-bold text-sm mb-4">
                            03
                        </div>
                        <h3 class="font-heading text-lg font-bold text-[#292524] group-hover:text-[#A34E30] transition">
                            Stock Opname Presisi
                        </h3>
                        <p class="text-xs text-[#78716C] mt-2 leading-relaxed">
                            Penghitungan fisik real-time dengan kalkulator selisih instan, berita acara cetak, dan rekonsiliasi otomatis.
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-[#E7E0DA]">
                        <a href="{{ route('login') }}" class="text-xs font-heading font-semibold text-[#A34E30] hover:underline inline-flex items-center gap-1">
                            <span>Rekonsiliasi Fisik</span> &rarr;
                        </a>
                    </div>
                </div>

                <!-- Pilar 4: Analitik & Pelaporan -->
                <div class="p-6 rounded-2xl bg-[#FFFFFF] border border-[#E7E0DA] hover:border-[#A34E30]/50 shadow-xs flex flex-col justify-between group transition">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-[#F5E6DE] text-[#A34E30] flex items-center justify-center font-bold text-sm mb-4">
                            04
                        </div>
                        <h3 class="font-heading text-lg font-bold text-[#292524] group-hover:text-[#A34E30] transition">
                            Laporan & Ekspor Data
                        </h3>
                        <p class="text-xs text-[#78716C] mt-2 leading-relaxed">
                            Grafik tren mutasi 7 hari, visualisasi ApexCharts, ekspor dokumen CSV untuk Excel, dan lembar PDF berotorisasi.
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-[#E7E0DA]">
                        <a href="{{ route('login') }}" class="text-xs font-heading font-semibold text-[#A34E30] hover:underline inline-flex items-center gap-1">
                            <span>Modul Laporan</span> &rarr;
                        </a>
                    </div>
                </div>

            </div>

        </section>


        <!-- ============================================================ -->
        <!-- 5. STANDAR OPERASIONAL LOGISTIK (ARSITEKTUR MUTASI)          -->
        <!-- ============================================================ -->
        <section id="arsitektur" class="max-w-6xl mx-auto px-6">

            <div class="p-8 sm:p-12 rounded-3xl bg-[#FFFFFF] border border-[#E7E0DA] shadow-xs relative overflow-hidden">
                <div class="max-w-2xl">
                    <span class="text-[11px] uppercase tracking-[0.2em] text-[#A34E30] font-semibold">Standar Operasional</span>
                    <h2 class="font-heading text-3xl sm:text-4xl font-bold text-[#292524] mt-1">
                        Siklus Mutasi Stok Tanpa Ambang Human Error.
                    </h2>
                    <p class="mt-3 text-xs sm:text-sm text-[#78716C] leading-relaxed">
                        Setiap perubahan jumlah barang diikat dengan nomor bukti transaksi (Invoice), validasi kapasitas stok sebelum barang keluar, dan status konfirmasi bertingkat.
                    </p>
                </div>

                <div class="mt-10 grid grid-cols-1 md:grid-cols-3 gap-6 text-xs">
                    <!-- Step 1 -->
                    <div class="p-6 rounded-2xl bg-[#FAF7F4] border border-[#E7E0DA] space-y-2.5">
                        <div class="w-8 h-8 rounded-lg bg-[#F5E6DE] text-[#A34E30] flex items-center justify-center font-heading font-bold">1</div>
                        <h4 class="font-heading font-bold text-[#292524] text-sm">Penerimaan Inbound</h4>
                        <p class="text-[#78716C] leading-relaxed">
                            Pencatatan nomor surat jalan supplier, SKU barang, dan kuantitas masuk. Stok fisik langsung terbarui secara atomic dalam database.
                        </p>
                    </div>

                    <!-- Step 2 -->
                    <div class="p-6 rounded-2xl bg-[#FAF7F4] border border-[#E7E0DA] space-y-2.5">
                        <div class="w-8 h-8 rounded-lg bg-[#F5E6DE] text-[#A34E30] flex items-center justify-center font-heading font-bold">2</div>
                        <h4 class="font-heading font-bold text-[#292524] text-sm">Validasi Stok Outbound</h4>
                        <p class="text-[#78716C] leading-relaxed">
                            Sistem secara proaktif menolak pengeluaran jika permintaan melebihi sisa fisik di gudang. Dilengkapi klasifikasi alasan mutasi.
                        </p>
                    </div>

                    <!-- Step 3 -->
                    <div class="p-6 rounded-2xl bg-[#FAF7F4] border border-[#E7E0DA] space-y-2.5">
                        <div class="w-8 h-8 rounded-lg bg-[#F5E6DE] text-[#A34E30] flex items-center justify-center font-heading font-bold">3</div>
                        <h4 class="font-heading font-bold text-[#292524] text-sm">Otorisasi & Audit Trail</h4>
                        <p class="text-[#78716C] leading-relaxed">
                            Transaksi pending ditinjau oleh Manajer Gudang. Setiap langkah tercatat dalam tabel log aktivitas dengan alamat IP dan timestamp.
                        </p>
                    </div>
                </div>
            </div>

        </section>


        <!-- ============================================================ -->
        <!-- 6. 1-CLICK DEMO SANDBOX BANNER (100% FUNGSIONAL)             -->
        <!-- ============================================================ -->
        <section id="akses-demo" class="max-w-5xl mx-auto px-6">

            <div class="text-center mb-10">
                <span class="text-[11px] uppercase tracking-[0.2em] text-[#A34E30] font-semibold">Sandbox & Demo Eksklusif</span>
                <h2 class="font-heading text-3xl sm:text-4xl font-bold text-[#292524] mt-1">
                    Uji Coba Langsung Berdasarkan Peran Pengguna.
                </h2>
                <p class="text-xs text-[#78716C] mt-2">
                    Pilih peran di bawah ini untuk langsung masuk ke dashboard sistem tanpa perlu mengisi formulir login.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                <!-- 1. Admin Card (DEEP TERRACOTTA #A34E30) -->
                <div class="p-6 rounded-2xl bg-[#FFFFFF] border border-[#E7E0DA] shadow-xs flex flex-col justify-between space-y-5 hover:border-[#A34E30]/50 transition">
                    <div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-heading font-bold text-[#292524]">Administrator</span>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-[#F5E6DE] text-[#A34E30] border border-[#BD6745]/30">Akses Penuh</span>
                        </div>
                        <p class="text-xs text-[#78716C] mt-2 leading-relaxed">
                            Akses kelola Master Data (Kategori, Supplier, Produk), hapus data, audit log aktivitas sistem, dan konfirmasi transaksi.
                        </p>
                    </div>
                    <form action="{{ route('login.post') }}" method="POST">
                        @csrf
                        <input type="hidden" name="email" value="admin@stockify.test">
                        <input type="hidden" name="password" value="password">
                        <button type="submit"
                            class="w-full py-3 px-4 rounded-xl bg-[#A34E30] hover:bg-[#8C3D22] text-white text-xs font-heading font-semibold transition shadow-xs cursor-pointer flex items-center justify-center gap-1.5">
                            <span>Masuk sebagai Admin</span> &rarr;
                        </button>
                    </form>
                </div>

                <!-- 2. Manager Card -->
                <div class="p-6 rounded-2xl bg-[#FFFFFF] border border-[#E7E0DA] shadow-xs flex flex-col justify-between space-y-5 hover:border-[#A34E30]/50 transition">
                    <div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-heading font-bold text-[#292524]">Manajer Gudang</span>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-[#FAF7F4] text-[#292524] border border-[#E7E0DA]">Supervisor</span>
                        </div>
                        <p class="text-xs text-[#78716C] mt-2 leading-relaxed">
                            Akses monitoring stok tipis, input produk baru, persetujuan transaksi mutasi, dan verifikasi barang masuk/keluar.
                        </p>
                    </div>
                    <form action="{{ route('login.post') }}" method="POST">
                        @csrf
                        <input type="hidden" name="email" value="manager@stockify.test">
                        <input type="hidden" name="password" value="password">
                        <button type="submit"
                            class="w-full py-3 px-4 rounded-xl bg-[#FAF7F4] border border-[#E7E0DA] hover:border-[#A34E30] text-[#292524] text-xs font-heading font-semibold hover:bg-[#F5E6DE] hover:text-[#A34E30] transition cursor-pointer flex items-center justify-center gap-1.5">
                            <span>Masuk sebagai Manajer</span> &rarr;
                        </button>
                    </form>
                </div>

                <!-- 3. Staff Card -->
                <div class="p-6 rounded-2xl bg-[#FFFFFF] border border-[#E7E0DA] shadow-xs flex flex-col justify-between space-y-5 hover:border-[#A34E30]/50 transition">
                    <div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-heading font-bold text-[#292524]">Staff Gudang</span>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-[#FAF7F4] text-[#78716C] border border-[#E7E0DA]">Operator</span>
                        </div>
                        <p class="text-xs text-[#78716C] mt-2 leading-relaxed">
                            Akses operasional harian: melihat katalog produk, mencatat barang masuk dari supplier, dan menyiapkan barang keluar.
                        </p>
                    </div>
                    <form action="{{ route('login.post') }}" method="POST">
                        @csrf
                        <input type="hidden" name="email" value="staff@stockify.test">
                        <input type="hidden" name="password" value="password">
                        <button type="submit"
                            class="w-full py-3 px-4 rounded-xl bg-[#FAF7F4] border border-[#E7E0DA] hover:border-[#A34E30] text-[#292524] text-xs font-heading font-semibold hover:bg-[#F5E6DE] hover:text-[#A34E30] transition cursor-pointer flex items-center justify-center gap-1.5">
                            <span>Masuk sebagai Staff</span> &rarr;
                        </button>
                    </form>
                </div>

            </div>

        </section>

    </main>

    <!-- FOOTER RESMI BERSIH -->
    <footer class="border-t border-[#E7E0DA] bg-[#FFFFFF] py-12">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-6">

            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-[#F5E6DE] border border-[#BD6745]/30 flex items-center justify-center text-[#A34E30] font-heading font-bold text-xs">
                    S
                </div>
                <span class="font-heading font-bold text-base text-[#292524]">Stockify Warehouse System</span>
            </div>

            <div class="text-xs text-[#78716C] text-center md:text-left">
                &copy; {{ date('Y') }} Stockify. Warm Ivory & Deep Terracotta Palette.
            </div>

            <div class="flex items-center gap-2 text-xs text-[#78716C]">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span>Semua Node Gudang Operasional (Normal)</span>
            </div>

        </div>
    </footer>

    <!-- Video Play/Pause & Scroll Script Fungsional -->
    <script>
        const video = document.getElementById('company-portfolio-video');
        const overlay = document.getElementById('video-overlay');
        const btnPlay = document.getElementById('btn-play-video');

        function playShowroomVideo() {
            const showroomSection = document.getElementById('showroom');
            if (showroomSection) {
                showroomSection.scrollIntoView({ behavior: 'smooth' });
            }
            if (video) {
                setTimeout(() => {
                    video.play();
                    if (overlay) overlay.classList.add('opacity-0', 'pointer-events-none');
                }, 400);
            }
        }

        if (btnPlay && video) {
            btnPlay.addEventListener('click', () => {
                video.play();
                if (overlay) overlay.classList.add('opacity-0', 'pointer-events-none');
            });

            video.addEventListener('play', () => {
                if (overlay) overlay.classList.add('opacity-0', 'pointer-events-none');
            });

            video.addEventListener('pause', () => {
                if (overlay) overlay.classList.remove('opacity-0', 'pointer-events-none');
            });

            video.addEventListener('ended', () => {
                if (overlay) overlay.classList.remove('opacity-0', 'pointer-events-none');
            });
        }
    </script>
</body>

</html>