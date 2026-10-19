<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Stockify — Warehouse Management System</title>

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Google Fonts: Space Grotesk (Editorial Headings) + IBM Plex Sans (Body) + IBM Plex Mono (Tabular/Code) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600&family=IBM+Plex+Sans:wght@400;500;600&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'IBM Plex Sans', sans-serif;
            background-color: #101214;
            color: #F5F5F5;
        }

        h1, h2, h3, h4, h5, h6, .font-heading {
            font-family: 'Space Grotesk', sans-serif;
        }

        .font-mono, [data-tabular] {
            font-family: 'IBM Plex Mono', monospace;
            font-variant-numeric: tabular-nums;
        }
    </style>
</head>

<body class="bg-[#101214] text-[#F5F5F5] antialiased selection:bg-[#F5C842] selection:text-[#101214]">

    <!-- Navigasi Utama (Sticky Nav) -->
    <header class="sticky top-0 z-50 backdrop-blur-md bg-[#181C20]/90 border-b border-[#343A40]">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">

            <!-- Brand Logo -->
            <a href="/" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-xl bg-[#F5C842] border border-[#F5C842]/40 flex items-center justify-center transition group-hover:bg-[#E8B92E]">
                    <span class="text-[#101214] font-heading font-bold text-base tracking-wider transition">S</span>
                </div>
                <div class="flex flex-col">
                    <span class="font-heading text-xl font-bold tracking-tight text-[#F5F5F5]">Stockify</span>
                    <span class="text-[9px] uppercase tracking-[0.2em] text-[#A1A1AA] font-semibold">Warehouse Management System</span>
                </div>
            </a>

            <!-- Nav Links Fungsional -->
            <nav class="hidden md:flex items-center gap-7 text-xs font-semibold text-[#A1A1AA]">
                <a href="#simulator" class="hover:text-[#F5C842] transition">Simulator Stok</a>
                <a href="#fitur" class="hover:text-[#F5C842] transition">Fitur Inti</a>
                <a href="#arsitektur" class="hover:text-[#F5C842] transition">Alur Logistik</a>
                <a href="#akses-demo" class="hover:text-[#F5C842] transition">Akses Demo</a>
            </nav>

            <!-- CTA Masuk / Dashboard (INDUSTRIAL YELLOW #F5C842) -->
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

        <!-- ============================================================ -->
        <!-- 1. HERO SECTION                                              -->
        <!-- ============================================================ -->
        <section class="relative pt-12 sm:pt-20 max-w-5xl mx-auto px-6 text-center">

            <!-- Headline Tajam Space Grotesk -->
            <h1 class="font-heading text-4xl sm:text-6xl lg:text-7xl font-bold tracking-tight text-[#F5F5F5] leading-[1.1]">
                Kelola Persediaan Gudang dengan Akurasi dan Presisi Tinggi.
            </h1>

            <!-- Subheading Editorial -->
            <p class="mt-6 max-w-2xl mx-auto text-sm sm:text-base text-[#A1A1AA] leading-relaxed">
                Platform pergudangan terpadu yang dirancang untuk brand ritel, pusat distribusi, dan rantai pasok modern. Mengeliminasi selisih stok melalui pencatatan mutasi ganda dan audit log tanpa kompromi.
            </p>

            <!-- Dua Tombol Aksi Fungsional -->
            <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                <!-- Tombol Primer: Industrial Yellow (#F5C842) -->
                <a href="#akses-demo"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-7 py-3.5 rounded-xl bg-[#F5C842] text-[#101214] text-xs font-heading font-semibold hover:bg-[#E8B92E] transition shadow-xs">
                    <span>Mulai Akses Demo 1-Klik</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 13.5L12 21m0 0l-7.5-7.5M12 21V3" />
                    </svg>
                </a>
                
                <!-- Tombol Sekunder: Simulator Mutasi Stok -->
                <a href="#simulator"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-7 py-3.5 rounded-xl bg-[#202429] border border-[#343A40] text-[#F5F5F5] text-xs font-heading font-semibold hover:border-[#F5C842] hover:text-[#F5C842] transition shadow-xs">
                    <svg class="w-4 h-4 text-[#F5C842]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                    <span>Coba Simulator Mutasi Stok</span>
                </a>
            </div>

            <!-- Tiga Metrik Utama Real-Time dari Database -->
            <div class="mt-16 pt-12 border-t border-[#343A40] grid grid-cols-1 sm:grid-cols-3 gap-6 text-left">
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

        </section>


        <!-- ============================================================ -->
        <!-- 2. GIMMICK: INTERACTIVE LIVE STOCK SIMULATOR                -->
        <!-- ============================================================ -->
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

            <!-- Simulator Container -->
            <div class="rounded-3xl bg-[#202429] border border-[#343A40] shadow-md overflow-hidden">

                <!-- Header Simulator & Product Selector Tabs -->
                <div class="p-6 bg-[#181C20] border-b border-[#343A40] flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                    <div>
                        <span class="text-[10px] font-mono uppercase tracking-wider text-[#A1A1AA] font-semibold">Pilih SKU Produk untuk Diuji:</span>
                        <div class="mt-2 flex flex-wrap gap-2" id="sim-product-tabs">
                            <button type="button" onclick="selectSimProduct(0)" id="sim-tab-0"
                                class="sim-tab px-3.5 py-1.5 rounded-lg text-xs font-heading font-semibold transition cursor-pointer bg-[#F5C842] text-[#101214] shadow-xs">
                                ⌨️ Keyboard Mekanikal Pro
                            </button>
                            <button type="button" onclick="selectSimProduct(1)" id="sim-tab-1"
                                class="sim-tab px-3.5 py-1.5 rounded-lg text-xs font-heading font-semibold transition cursor-pointer bg-[#202429] border border-[#343A40] text-[#F5F5F5] hover:border-[#F5C842]">
                                🪑 Kursi Ergonomis Mesh
                            </button>
                            <button type="button" onclick="selectSimProduct(2)" id="sim-tab-2"
                                class="sim-tab px-3.5 py-1.5 rounded-lg text-xs font-heading font-semibold transition cursor-pointer bg-[#202429] border border-[#343A40] text-[#F5F5F5] hover:border-[#F5C842]">
                                🍶 Termos Insulasi 750ml
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="text-xs font-mono font-medium text-[#A1A1AA]">Simulator Engine Aktif</span>
                    </div>
                </div>

                <!-- Main Interactive Grid -->
                <div class="p-6 sm:p-8 grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

                    <!-- Kolom Kiri: Kartu Status Produk & Kontrol Mutasi (7 Cols) -->
                    <div class="lg:col-span-7 space-y-6">

                        <!-- Detail Produk Aktif -->
                        <div class="p-6 rounded-2xl bg-[#181C20] border border-[#343A40]">
                            <div class="flex items-center justify-between gap-2 mb-3">
                                <div class="flex items-center gap-2">
                                    <span id="sim-sku" class="px-2.5 py-0.5 rounded font-mono text-xs font-semibold bg-[#F5C842]/15 text-[#F5C842] border border-[#F5C842]/30">
                                        PRD-ELC-001
                                    </span>
                                    <span id="sim-category" class="text-xs text-[#A1A1AA]">Elektronik & Periferal</span>
                                </div>
                                <span id="sim-status-badge" class="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/15 text-emerald-400 border border-emerald-500/30">
                                    ✓ Stok Aman (Normal)
                                </span>
                            </div>

                            <h3 id="sim-name" class="font-heading text-2xl font-bold text-[#F5F5F5]">
                                Keyboard Mekanikal Pro Wireless
                            </h3>
                            <p id="sim-desc" class="text-xs text-[#A1A1AA] mt-1 leading-relaxed">
                                Dilengkapi switch hot-swappable dengan varian atribut warna Onyx Black dan Grey.
                            </p>

                            <!-- Indikator Angka Stok Besar & Ambang Batas -->
                            <div class="mt-6 pt-6 border-t border-[#343A40] flex items-end justify-between">
                                <div>
                                    <span class="text-[11px] text-[#A1A1AA] uppercase tracking-wider block font-medium">Sisa Stok Fisik Gudang</span>
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

                        <!-- Tombol Aksi Mutasi Interaktif -->
                        <div>
                            <span class="text-[11px] font-semibold text-[#A1A1AA] uppercase tracking-wider block mb-3">
                                Eksekusi Mutasi Cepat:
                            </span>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                                <button type="button" onclick="adjustSimStock(5, 'in')"
                                    class="py-3 px-3 rounded-xl bg-emerald-500/15 hover:bg-emerald-500/25 text-emerald-400 border border-emerald-500/30 text-xs font-heading font-semibold transition cursor-pointer flex flex-col items-center justify-center gap-1 active:scale-95">
                                    <span class="text-sm">📥 +5 Unit</span>
                                    <span class="text-[10px] opacity-80">Inbound Masuk</span>
                                </button>
                                <button type="button" onclick="adjustSimStock(20, 'in')"
                                    class="py-3 px-3 rounded-xl bg-emerald-500/15 hover:bg-emerald-500/25 text-emerald-400 border border-emerald-500/30 text-xs font-heading font-semibold transition cursor-pointer flex flex-col items-center justify-center gap-1 active:scale-95">
                                    <span class="text-sm">📦 +20 Unit</span>
                                    <span class="text-[10px] opacity-80">Pasokan Besar</span>
                                </button>
                                <button type="button" onclick="adjustSimStock(5, 'out')"
                                    class="py-3 px-3 rounded-xl bg-[#F87171]/15 hover:bg-[#F87171]/25 text-[#F87171] border border-[#F87171]/30 text-xs font-heading font-semibold transition cursor-pointer flex flex-col items-center justify-center gap-1 active:scale-95">
                                    <span class="text-sm">📤 -5 Unit</span>
                                    <span class="text-[10px] opacity-80">Kirim Outbound</span>
                                </button>
                                <button type="button" onclick="adjustSimStock(20, 'out')"
                                    class="py-3 px-3 rounded-xl bg-[#F87171]/15 hover:bg-[#F87171]/25 text-[#F87171] border border-[#F87171]/30 text-xs font-heading font-semibold transition cursor-pointer flex flex-col items-center justify-center gap-1 active:scale-95">
                                    <span class="text-sm">🚚 -20 Unit</span>
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

                    <!-- Kolom Kanan: Live Ledger Audit Terminal (5 Cols) -->
                    <div class="lg:col-span-5 flex flex-col h-full space-y-3">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-heading font-bold text-[#F5F5F5] flex items-center gap-2">
                                <svg class="w-4 h-4 text-[#F5C842]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Buku Besar Mutasi (Live Ledger)
                            </span>
                            <button type="button" onclick="clearSimLogs()" class="text-[11px] text-[#A1A1AA] hover:text-[#F5C842] transition cursor-pointer">
                                Bersihkan Log
                            </button>
                        </div>

                        <!-- Console Box -->
                        <div class="rounded-2xl bg-[#101214] border border-[#343A40] p-4 text-xs font-mono text-[#F5F5F5] flex-1 flex flex-col justify-between shadow-inner min-h-[320px]">
                            <!-- Terminal Header -->
                            <div class="flex items-center justify-between pb-3 border-b border-[#343A40] text-[10px] text-[#A1A1AA]">
                                <div class="flex items-center gap-1.5">
                                    <span class="w-2.5 h-2.5 rounded-full bg-red-500/80"></span>
                                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500/80"></span>
                                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500/80"></span>
                                </div>
                                <span>STOCKIFY-AUDIT-STREAM</span>
                            </div>

                            <!-- Log Entries Stream -->
                            <div id="sim-log-entries" class="py-3 space-y-2 overflow-y-auto max-h-[260px] text-[11px] flex-1">
                                <div class="text-[#A1A1AA]">[INIT] Engine siap mencatat mutasi stok fisik...</div>
                            </div>

                            <!-- Terminal Status Prompt -->
                            <div class="pt-3 border-t border-[#343A40] flex items-center justify-between text-[10px] text-[#A1A1AA]">
                                <span>Status: <span class="text-emerald-400">Tersinkronisasi</span></span>
                                <span>Double-Entry Ledger</span>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </section>


        <!-- ============================================================ -->
        <!-- 3. EMPAT PILAR FITUR INTI GUDANG                             -->
        <!-- ============================================================ -->
        <section id="fitur" class="max-w-6xl mx-auto px-6">

            <div class="text-center max-w-2xl mx-auto mb-12">
                <span class="text-[11px] uppercase tracking-[0.2em] text-[#F5C842] font-semibold">Kapabilitas Sistem</span>
                <h2 class="font-heading text-3xl sm:text-4xl font-bold text-[#F5F5F5] mt-1">
                    Dirancang untuk Menjawab Kompleksitas Fisik Gudang.
                </h2>
                <p class="mt-2 text-xs text-[#A1A1AA]">
                    Setiap modul terhubung langsung ke basis data terpusat dengan integritas transaksi atomik.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

                <!-- Pilar 1: Master Data -->
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

                <!-- Pilar 2: Mutasi Arus Barang -->
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

                <!-- Pilar 3: Stock Opname & Audit -->
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

                <!-- Pilar 4: Analitik & Pelaporan -->
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


        <!-- ============================================================ -->
        <!-- 4. STANDAR OPERASIONAL LOGISTIK                              -->
        <!-- ============================================================ -->
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
                    <!-- Step 1 -->
                    <div class="p-6 rounded-2xl bg-[#181C20] border border-[#343A40] space-y-2.5">
                        <div class="w-8 h-8 rounded-lg bg-[#F5C842] text-[#101214] flex items-center justify-center font-heading font-bold">1</div>
                        <h4 class="font-heading font-bold text-[#F5F5F5] text-sm">Penerimaan Inbound</h4>
                        <p class="text-[#A1A1AA] leading-relaxed">
                            Pencatatan nomor surat jalan supplier, SKU barang, dan kuantitas masuk. Stok fisik langsung terbarui secara atomic dalam database.
                        </p>
                    </div>

                    <!-- Step 2 -->
                    <div class="p-6 rounded-2xl bg-[#181C20] border border-[#343A40] space-y-2.5">
                        <div class="w-8 h-8 rounded-lg bg-[#F5C842] text-[#101214] flex items-center justify-center font-heading font-bold">2</div>
                        <h4 class="font-heading font-bold text-[#F5F5F5] text-sm">Validasi Stok Outbound</h4>
                        <p class="text-[#A1A1AA] leading-relaxed">
                            Sistem secara proaktif menolak pengeluaran jika permintaan melebihi sisa fisik di gudang. Dilengkapi klasifikasi alasan mutasi.
                        </p>
                    </div>

                    <!-- Step 3 -->
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


        <!-- ============================================================ -->
        <!-- 5. 1-CLICK DEMO SANDBOX BANNER                               -->
        <!-- ============================================================ -->
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

                <!-- 1. Admin Card (INDUSTRIAL YELLOW #F5C842) -->
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

                <!-- 2. Manager Card -->
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

                <!-- 3. Staff Card -->
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

    <!-- ============================================================ -->
    <!-- FOOTER SIMPEL & ELEGAN (TANPA NODE STATUS)                    -->
    <!-- ============================================================ -->
    <footer class="border-t border-[#343A40] bg-[#101214] py-8">
        <div class="max-w-7xl mx-auto px-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-[#A1A1AA]">

            <!-- Logo & Nama Sistem -->
            <div class="flex items-center gap-3">
                <div class="w-7 h-7 rounded-lg bg-[#F5C842] border border-[#F5C842]/40 flex items-center justify-center text-[#101214] font-heading font-bold text-xs">
                    S
                </div>
                <span class="font-heading font-semibold text-sm text-[#F5F5F5]">Stockify</span>
                <span class="text-[#343A40]">•</span>
                <span>Warehouse Management System</span>
            </div>

            <!-- Copyright Sederhana -->
            <div class="text-[11px] text-[#A1A1AA]">
                &copy; {{ date('Y') }} Stockify. Seluruh hak cipta dilindungi.
            </div>

        </div>
    </footer>

    <!-- ============================================================ -->
    <!-- SCRIPT GIMMICK: INTERACTIVE STOCK SIMULATOR                 -->
    <!-- ============================================================ -->
    <script>
        const simProducts = [
            {
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

        // Jalankan inisialisasi awal UI
        document.addEventListener('DOMContentLoaded', () => {
            updateSimUI();
            appendSimLog('Simulator siap. Coba klik tombol Inbound atau Outbound untuk menguji!', 'normal');
        });
    </script>
</body>

</html>