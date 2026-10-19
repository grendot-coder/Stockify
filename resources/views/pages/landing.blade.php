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
                <div class="w-9 h-9 rounded-lg bg-[#F5E6DE] border border-[#BD6745]/30 flex items-center justify-center transition group-hover:bg-[#BD6745] group-hover:text-[#FFFFFF]">
                    <span class="text-[#BD6745] group-hover:text-[#FFFFFF] font-bold text-sm tracking-wider transition">S</span>
                </div>
                <div class="flex flex-col">
                    <span class="font-editorial text-xl font-medium tracking-tight text-[#292524]">Stockify</span>
                    <span class="text-[9px] uppercase tracking-[0.2em] text-[#78716C] font-semibold">Warehouse Management System</span>
                </div>
            </a>

            <!-- Nav Links -->
            <nav class="hidden md:flex items-center gap-8 text-xs font-medium text-[#78716C]">
                <a href="#showroom" class="hover:text-[#BD6745] transition">Portofolio & Showroom</a>
                <a href="#klien" class="hover:text-[#BD6745] transition">Klien & Mitra</a>
                <a href="#arsitektur" class="hover:text-[#BD6745] transition">Alur Operasional</a>
                <a href="#akses-demo" class="hover:text-[#BD6745] transition">Akses Demo</a>
            </nav>

            <!-- CTA Masuk / Dashboard -->
            <div class="flex items-center gap-3">
                @auth
                <a href="{{ route('dashboard') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-[#BD6745] text-[#1C1917] text-xs font-semibold hover:bg-[#A55636] transition shadow-xs">
                    <span>Buka Dashboard</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </a>
                @else
                <a href="{{ route('login') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-[#BD6745] text-[#1C1917] text-xs font-semibold hover:bg-[#A55636] transition shadow-xs">
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

        <!-- 1. HERO SECTION -->
        <section class="relative pt-16 sm:pt-24 max-w-5xl mx-auto px-6 text-center">

            <!-- Pill Badge -->
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#FFFFFF] border border-[#E7E0DA] text-xs text-[#78716C] mb-8 shadow-xs">
                <span class="w-1.5 h-1.5 rounded-full bg-[#BD6745]"></span>
                <span class="text-[#292524] font-semibold">Stockify Enterprise</span>
                <span class="text-[#E7E0DA]">•</span>
                <span>Manajemen Pergudangan Modern & Terpadu</span>
            </div>

            <!-- Headline Editorial -->
            <h1 class="font-heading text-4xl sm:text-6xl lg:text-7xl font-semibold tracking-tight text-[#292524] leading-[1.12]">
                Kelola Persediaan Gudang dengan Akurasi dan Presisi Tinggi.
            </h1>

            <!-- Subhead Warm Minimal -->
            <p class="mt-6 max-w-2xl mx-auto text-sm sm:text-base text-[#78716C] leading-relaxed">
                Platform pergudangan terpadu yang dirancang untuk brand ritel, pusat distribusi, dan rantai pasok modern. Mengeliminasi selisih stok melalui pencatatan mutasi ganda dan audit log tanpa kompromi.
            </p>

            <!-- Action Buttons -->
            <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="#akses-demo"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-[#BD6745] text-[#1C1917] text-xs font-bold tracking-wide uppercase hover:bg-[#A55636] transition shadow-xs">
                    <span>Mulai Eksplorasi Demo</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 13.5L12 21m0 0l-7.5-7.5M12 21V3" />
                    </svg>
                </a>
                <a href="#showroom"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-[#FFFFFF] border border-[#E7E0DA] text-[#292524] text-xs font-semibold hover:border-[#BD6745] hover:text-[#BD6745] transition shadow-xs">
                    <svg class="w-4 h-4 text-[#BD6745]" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M6.3 2.841A1.5 1.5 0 004 4.11v11.78a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z" />
                    </svg>
                    <span>Tonton Video Showroom</span>
                </a>
            </div>

            <!-- Key Indicators 3 Columns -->
            <div class="mt-16 pt-12 border-t border-[#E7E0DA] grid grid-cols-1 sm:grid-cols-3 gap-6 text-left">
                <div class="p-5 rounded-2xl bg-[#FFFFFF] border border-[#E7E0DA] shadow-xs">
                    <div class="text-3xl font-bold font-heading tabular-nums text-[#BD6745]">99.98%</div>
                    <div class="text-xs font-semibold text-[#292524] mt-1">Akurasi Fisik & Opname</div>
                    <div class="text-[11px] text-[#78716C] mt-0.5">Penyesuaian stok tercatat otomatis dengan jejak waktu.</div>
                </div>
                <div class="p-5 rounded-2xl bg-[#FFFFFF] border border-[#E7E0DA] shadow-xs">
                    <div class="text-3xl font-bold font-heading tabular-nums text-[#BD6745]">
                        {{ $productCount > 0 ? $productCount . ' SKU' : '15.000+' }}
                    </div>
                    <div class="text-xs font-semibold text-[#292524] mt-1">Kapasitas Multi-Atribut</div>
                    <div class="text-[11px] text-[#78716C] mt-0.5">Varian ukuran, warna, nomor seri, dan masa garansi.</div>
                </div>
                <div class="p-5 rounded-2xl bg-[#FFFFFF] border border-[#E7E0DA] shadow-xs">
                    <div class="text-3xl font-bold font-heading tabular-nums text-[#BD6745]">
                        {{ $transactionCount > 0 ? $transactionCount . ' Mutasi' : 'Real-time' }}
                    </div>
                    <div class="text-xs font-semibold text-[#292524] mt-1">Alur Mutasi Terotorisasi</div>
                    <div class="text-[11px] text-[#78716C] mt-0.5">Otorisasi persetujuan terpisah antara Staff dan Manajer.</div>
                </div>
            </div>

        </section>


        <!-- 2. PORTOFOLIO VIDEO OF THE COMPANY (SHOWROOM SECTION) -->
        <section id="showroom" class="max-w-6xl mx-auto px-6">

            <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <span class="text-[11px] uppercase tracking-[0.2em] text-[#BD6745] font-semibold">Portofolio Showroom</span>
                    <h2 class="font-editorial text-3xl sm:text-4xl text-[#292524] mt-1">
                        Di Balik Ketepatan Distribusi Skala Modern.
                    </h2>
                </div>
                <p class="text-xs text-[#78716C] max-w-md leading-relaxed">
                    {{ $companyVideo['director_note'] }}
                </p>
            </div>

            <!-- Cinema Frame Video Player -->
            <div class="relative rounded-2xl bg-[#FFFFFF] border border-[#E7E0DA] overflow-hidden shadow-lg group">

                <!-- Video Top Status Bar -->
                <div class="px-5 py-3.5 bg-[#FAF7F4] border-b border-[#E7E0DA] flex items-center justify-between text-xs text-[#78716C]">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-[#BD6745] animate-pulse"></span>
                        <span class="font-mono text-[11px] text-[#292524] font-medium">{{ $companyVideo['title'] }}</span>
                    </div>
                    <div class="flex items-center gap-3 font-mono text-[11px]">
                        <span class="px-2 py-0.5 rounded bg-[#FFFFFF] border border-[#E7E0DA] text-[#BD6745] font-semibold">{{ $companyVideo['resolution'] }}</span>
                        <span>{{ $companyVideo['duration'] }}</span>
                    </div>
                </div>

                <!-- Video Element / Interactive Media Container -->
                <div class="relative aspect-video bg-[#1C1917] flex items-center justify-center overflow-hidden">

                    <video id="company-portfolio-video"
                        class="w-full h-full object-cover"
                        poster="{{ $companyVideo['poster_image'] }}"
                        preload="metadata"
                        playsinline>
                        <source src="{{ $companyVideo['video_url'] }}" type="video/mp4">
                        Peramban Anda tidak mendukung elemen video HTML5.
                    </video>

                    <!-- Overlay Cover & Play Trigger -->
                    <div id="video-overlay" class="absolute inset-0 bg-[#1C1917]/40 flex flex-col items-center justify-center transition-all duration-300">
                        <button type="button" id="btn-play-video"
                            class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-[#BD6745] text-[#1C1917] flex items-center justify-center shadow-lg transition-transform transform group-hover:scale-105 active:scale-95 focus:outline-none cursor-pointer">
                            <svg class="w-7 h-7 sm:w-8 sm:h-8 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M6.3 2.841A1.5 1.5 0 004 4.11v11.78a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z" />
                            </svg>
                        </button>
                        <p class="mt-4 text-xs font-semibold text-[#FFFFFF] tracking-wide">Klik untuk Memutar Video Portofolio</p>
                    </div>

                    <!-- Custom Player Controls Bar -->
                    <div id="video-controls" class="absolute bottom-0 inset-x-0 p-4 bg-gradient-to-t from-[#1C1917]/80 via-[#1C1917]/40 to-transparent flex items-center justify-between opacity-0 hover:opacity-100 transition-opacity">
                        <button type="button" id="btn-toggle-play" class="text-xs text-[#FFFFFF] hover:text-[#F5E6DE] font-semibold flex items-center gap-1.5 cursor-pointer">
                            <span id="play-state-label">Jeda (Pause)</span>
                        </button>
                        <div class="text-[11px] text-[#FAF7F4] font-mono">
                            <span id="current-time">00:00</span> / <span id="duration-time">--:--</span>
                        </div>
                    </div>
                </div>

                <!-- Video Footer Description Card -->
                <div class="p-6 bg-[#FFFFFF] border-t border-[#E7E0DA] grid grid-cols-1 md:grid-cols-3 gap-6 text-xs">
                    <div>
                        <span class="text-[#78716C] block mb-1 font-semibold uppercase tracking-wider text-[10px]">Fokus Dokumentasi:</span>
                        <p class="text-[#292524] font-medium leading-relaxed">
                            Integrasi alur mutasi barang Inbound & Outbound dengan pencatatan invoice digital real-time.
                        </p>
                    </div>
                    <div>
                        <span class="text-[#78716C] block mb-1 font-semibold uppercase tracking-wider text-[10px]">Standar Keamanan Gudang:</span>
                        <p class="text-[#292524] font-medium leading-relaxed">
                            Pemisahan wewenang otorisasi multi-role (RBAC) untuk menjaga integritas data stock opname.
                        </p>
                    </div>
                    <div>
                        <span class="text-[#78716C] block mb-1 font-semibold uppercase tracking-wider text-[10px]">Catatan Teknis / Pembaruan:</span>
                        <p class="text-[#78716C] leading-relaxed">
                            Placeholder video ini dapat diperbarui kapan saja melalui <code class="text-[#BD6745] bg-[#F5E6DE] px-1.5 py-0.5 rounded font-mono">LandingController.php</code>.
                        </p>
                    </div>
                </div>

            </div>

        </section>


        <!-- 3. LIST OF COMPANIES USING STOCKIFY (UPDATABLE PLACEHOLDER) -->
        <section id="klien" class="max-w-6xl mx-auto px-6">

            <div class="text-center max-w-2xl mx-auto mb-12">
                <span class="text-[11px] uppercase tracking-[0.2em] text-[#BD6745] font-semibold">Portofolio Klien & Ekosistem</span>
                <h2 class="font-editorial text-3xl sm:text-4xl text-[#292524] mt-1">
                    Dipercaya oleh Studio & Jaringan Retail Terkemuka.
                </h2>
                <p class="mt-2 text-xs text-[#78716C]">
                    Mulai dari studio kerajinan independen hingga jaringan distribusi nasional mengandalkan Stockify untuk menjaga ketepatan perputaran stok.
                </p>
            </div>

            <!-- Client Grid Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($clientCompanies as $company)
                <div class="p-6 rounded-2xl bg-[#FFFFFF] border border-[#E7E0DA] hover:border-[#BD6745]/60 hover:shadow-md transition-all duration-200 flex flex-col justify-between group">

                    <div>
                        <!-- Header Monogram & Badge -->
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-xl bg-[#F5E6DE] border border-[#BD6745]/20 flex items-center justify-center font-editorial text-lg text-[#BD6745] font-bold group-hover:bg-[#BD6745] group-hover:text-[#FFFFFF] transition">
                                {{ $company['monogram'] }}
                            </div>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold tracking-wide bg-[#FAF7F4] text-[#BD6745] border border-[#E7E0DA]">
                                {{ $company['badge'] }}
                            </span>
                        </div>

                        <!-- Name & Industry -->
                        <h3 class="font-editorial text-xl font-medium text-[#292524]">
                            {{ $company['name'] }}
                        </h3>
                        <p class="text-xs text-[#78716C] mt-0.5">
                            {{ $company['industry'] }}
                        </p>

                        <!-- Testimonial Quote -->
                        <blockquote class="mt-4 text-xs text-[#292524] italic font-editorial leading-relaxed pl-3 border-l-2 border-[#BD6745]">
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
                            <span class="font-semibold text-[#BD6745] font-mono">{{ $company['units_tracked'] }}</span>
                        </div>
                    </div>

                </div>
                @endforeach
            </div>

            <!-- Notice for Developer / Admin -->
            <div class="mt-8 text-center">
                <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-[11px] text-[#78716C] bg-[#FFFFFF] border border-[#E7E0DA] shadow-xs">
                    <svg class="w-3.5 h-3.5 text-[#BD6745]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Daftar perusahaan ini bersifat dinamis dan dapat disesuaikan pada array <code class="text-[#BD6745] font-mono">$clientCompanies</code> di <code class="text-[#BD6745] font-mono">LandingController.php</code>.</span>
                </span>
            </div>

        </section>


        <!-- 4. CORE ARCHITECTURE WORKFLOW (ARSITEKTUR MUTASI) -->
        <section id="arsitektur" class="max-w-6xl mx-auto px-6">

            <div class="p-8 sm:p-12 rounded-3xl bg-[#FFFFFF] border border-[#E7E0DA] shadow-sm relative overflow-hidden">
                <div class="max-w-2xl">
                    <span class="text-[11px] uppercase tracking-[0.2em] text-[#BD6745] font-semibold">Standar Operasional</span>
                    <h2 class="font-editorial text-3xl sm:text-4xl text-[#292524] mt-1">
                        Siklus Mutasi Stok Tanpa Ambang Human Error.
                    </h2>
                    <p class="mt-3 text-xs sm:text-sm text-[#78716C] leading-relaxed">
                        Setiap perubahan jumlah barang diikat dengan nomor bukti transaksi (Invoice), validasi kapasitas stok sebelum barang keluar, dan status konfirmasi bertingkat.
                    </p>
                </div>

                <div class="mt-10 grid grid-cols-1 md:grid-cols-3 gap-6 text-xs">
                    <!-- Step 1 -->
                    <div class="p-5 rounded-2xl bg-[#FAF7F4] border border-[#E7E0DA] space-y-2">
                        <div class="w-8 h-8 rounded-lg bg-[#F5E6DE] text-[#BD6745] flex items-center justify-center font-bold">1</div>
                        <h4 class="font-bold text-[#292524] text-sm">Penerimaan Inbound</h4>
                        <p class="text-[#78716C] leading-relaxed">
                            Pencatatan nomor surat jalan supplier, SKU barang, dan kuantitas masuk. Stok fisik langsung terbarui secara atomic dalam database.
                        </p>
                    </div>

                    <!-- Step 2 -->
                    <div class="p-5 rounded-2xl bg-[#FAF7F4] border border-[#E7E0DA] space-y-2">
                        <div class="w-8 h-8 rounded-lg bg-[#F5E6DE] text-[#BD6745] flex items-center justify-center font-bold">2</div>
                        <h4 class="font-bold text-[#292524] text-sm">Validasi Stok Outbound</h4>
                        <p class="text-[#78716C] leading-relaxed">
                            Sistem secara proaktif menolak pengeluaran jika permintaan melebihi sisa fisik di gudang. Dilengkapi klasifikasi alasan mutasi.
                        </p>
                    </div>

                    <!-- Step 3 -->
                    <div class="p-5 rounded-2xl bg-[#FAF7F4] border border-[#E7E0DA] space-y-2">
                        <div class="w-8 h-8 rounded-lg bg-[#F5E6DE] text-[#BD6745] flex items-center justify-center font-bold">3</div>
                        <h4 class="font-bold text-[#292524] text-sm">Otorisasi & Audit Trail</h4>
                        <p class="text-[#78716C] leading-relaxed">
                            Transaksi pending ditinjau oleh Manajer Gudang. Setiap langkah tercatat dalam tabel log aktivitas dengan alamat IP dan timestamp.
                        </p>
                    </div>
                </div>
            </div>

        </section>


        <!-- 5. 1-CLICK DEMO SANDBOX BANNER -->
        <section id="akses-demo" class="max-w-5xl mx-auto px-6">

            <div class="text-center mb-8">
                <span class="text-[11px] uppercase tracking-[0.2em] text-[#BD6745] font-semibold">Sandbox & Demo Eksklusif</span>
                <h2 class="font-editorial text-3xl sm:text-4xl text-[#292524] mt-1">
                    Uji Coba Langsung Berdasarkan Peran Pengguna.
                </h2>
                <p class="text-xs text-[#78716C] mt-2">
                    Gunakan kredensial instan berikut untuk langsung masuk ke sistem sesuai hak akses masing-masing.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                <!-- Admin Card -->
                <div class="p-6 rounded-2xl bg-[#FFFFFF] border border-[#E7E0DA] shadow-xs flex flex-col justify-between space-y-4">
                    <div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-[#292524]">Administrator</span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-[#F5E6DE] text-[#BD6745] border border-[#BD6745]/30">Hak Penuh</span>
                        </div>
                        <p class="text-xs text-[#78716C] mt-2">
                            Akses kelola Master Data (Kategori, Supplier, Produk), hapus data, audit log aktivitas, dan konfirmasi transaksi.
                        </p>
                    </div>
                    <form action="{{ route('login.post') }}" method="POST">
                        @csrf
                        <input type="hidden" name="email" value="admin@stockify.test">
                        <input type="hidden" name="password" value="password">
                        <button type="submit"
                            class="w-full py-2.5 px-4 rounded-xl bg-[#BD6745] text-[#1C1917] text-xs font-semibold hover:bg-[#A55636] transition shadow-xs cursor-pointer">
                            Masuk sebagai Admin &rarr;
                        </button>
                    </form>
                </div>

                <!-- Manager Card -->
                <div class="p-6 rounded-2xl bg-[#FFFFFF] border border-[#E7E0DA] shadow-xs flex flex-col justify-between space-y-4">
                    <div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-[#292524]">Manajer Gudang</span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-[#FAF7F4] text-[#292524] border border-[#E7E0DA]">Supervisor</span>
                        </div>
                        <p class="text-xs text-[#78716C] mt-2">
                            Akses monitoring stok tipis, input produk baru, persetujuan transaksi mutasi, dan verifikasi barang masuk/keluar.
                        </p>
                    </div>
                    <form action="{{ route('login.post') }}" method="POST">
                        @csrf
                        <input type="hidden" name="email" value="manager@stockify.test">
                        <input type="hidden" name="password" value="password">
                        <button type="submit"
                            class="w-full py-2.5 px-4 rounded-xl bg-[#FAF7F4] border border-[#E7E0DA] hover:border-[#BD6745] text-[#292524] text-xs font-semibold hover:bg-[#F5E6DE] transition cursor-pointer">
                            Masuk sebagai Manajer &rarr;
                        </button>
                    </form>
                </div>

                <!-- Staff Card -->
                <div class="p-6 rounded-2xl bg-[#FFFFFF] border border-[#E7E0DA] shadow-xs flex flex-col justify-between space-y-4">
                    <div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-[#292524]">Staff Gudang</span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-[#FAF7F4] text-[#78716C] border border-[#E7E0DA]">Operator</span>
                        </div>
                        <p class="text-xs text-[#78716C] mt-2">
                            Akses operasional harian: melihat katalog produk, mencatat barang masuk dari supplier, dan menyiapkan barang keluar.
                        </p>
                    </div>
                    <form action="{{ route('login.post') }}" method="POST">
                        @csrf
                        <input type="hidden" name="email" value="staff@stockify.test">
                        <input type="hidden" name="password" value="password">
                        <button type="submit"
                            class="w-full py-2.5 px-4 rounded-xl bg-[#FAF7F4] border border-[#E7E0DA] hover:border-[#BD6745] text-[#292524] text-xs font-semibold hover:bg-[#F5E6DE] transition cursor-pointer">
                            Masuk sebagai Staff &rarr;
                        </button>
                    </form>
                </div>

            </div>

        </section>

    </main>

    <!-- FOOTER -->
    <footer class="border-t border-[#E7E0DA] bg-[#FFFFFF] py-12">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-6">

            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-[#F5E6DE] border border-[#BD6745]/30 flex items-center justify-center text-[#BD6745] font-bold text-xs">
                    S
                </div>
                <span class="font-editorial text-lg text-[#292524]">Stockify Warehouse System</span>
            </div>

            <div class="text-xs text-[#78716C] text-center md:text-left">
                &copy; {{ date('Y') }} Stockify. Warm Ivory & Soft Terracotta Palette.
            </div>

            <div class="flex items-center gap-2 text-xs text-[#78716C]">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span>Semua Node Gudang Operasional</span>
            </div>

        </div>
    </footer>

    <!-- Video Play/Pause Script -->
    <script>
        const video = document.getElementById('company-portfolio-video');
        const overlay = document.getElementById('video-overlay');
        const btnPlay = document.getElementById('btn-play-video');
        const btnToggle = document.getElementById('btn-toggle-play');
        const playStateLabel = document.getElementById('play-state-label');
        const currentTimeLabel = document.getElementById('current-time');
        const durationTimeLabel = document.getElementById('duration-time');

        function formatTime(seconds) {
            const m = Math.floor(seconds / 60);
            const s = Math.floor(seconds % 60);
            return `${m < 10 ? '0' : ''}${m}:${s < 10 ? '0' : ''}${s}`;
        }

        btnPlay.addEventListener('click', () => {
            video.play();
            overlay.classList.add('opacity-0', 'pointer-events-none');
            playStateLabel.textContent = 'Jeda (Pause)';
        });

        btnToggle.addEventListener('click', () => {
            if (video.paused) {
                video.play();
                overlay.classList.add('opacity-0', 'pointer-events-none');
                playStateLabel.textContent = 'Jeda (Pause)';
            } else {
                video.pause();
                overlay.classList.remove('opacity-0', 'pointer-events-none');
                playStateLabel.textContent = 'Putar (Play)';
            }
        });

        video.addEventListener('timeupdate', () => {
            currentTimeLabel.textContent = formatTime(video.currentTime);
            if (video.duration) {
                durationTimeLabel.textContent = formatTime(video.duration);
            }
        });

        video.addEventListener('ended', () => {
            overlay.classList.remove('opacity-0', 'pointer-events-none');
            playStateLabel.textContent = 'Putar Ulang';
        });
    </script>
</body>

</html>