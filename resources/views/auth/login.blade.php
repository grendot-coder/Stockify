<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk — Stockify Warehouse Management System</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600&family=IBM+Plex+Sans:wght@400;500;600&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { 
            font-family: 'IBM Plex Sans', sans-serif; 
            background-color: #FAF7F4;
            color: #292524;
        }
        h1, h2, h3, h4, h5, h6, .font-heading, .font-brand, .font-editorial {
            font-family: 'Space Grotesk', sans-serif;
        }
        .tabular-nums, .font-mono {
            font-variant-numeric: tabular-nums;
            font-feature-settings: "tnum" 1;
        }
    </style>
</head>
<body class="bg-[#FAF7F4] text-[#292524] min-h-screen antialiased selection:bg-[#F5E6DE] selection:text-[#1C1917]">

    <div class="min-h-screen grid grid-cols-1 lg:grid-cols-12">
        
        <!-- ========================================== -->
        <!-- SISI KIRI: SHOWCASE CARD ALA ZOHO/SHOPIFY  -->
        <!-- ========================================== -->
        <div class="hidden lg:flex lg:col-span-5 xl:col-span-6 p-6 sm:p-8 lg:p-10 flex-col justify-between">
            <div class="h-full w-full rounded-3xl bg-gradient-to-b from-[#FAF7F4] via-[#F5E6DE]/60 to-[#F5E6DE] border border-[#E7E0DA] p-8 xl:p-12 flex flex-col justify-between relative overflow-hidden shadow-xs">
                
                <!-- Pola Grid Halus di Latar Belakang -->
                <div class="absolute inset-0 opacity-[0.035] pointer-events-none" style="background-image: radial-gradient(#292524 1px, transparent 1px); background-size: 20px 20px;"></div>

                <!-- Bagian Konten Carousel 3 Slide -->
                <div class="relative z-10">

                    <!-- SLIDE 1: Testimonial & Rak Gudang (Default Aktif) -->
                    <div id="slide-1" class="carousel-slide transition-opacity duration-300">
                        <div class="text-[#BD6745] text-5xl font-editorial font-bold leading-none mb-4 select-none">&ldquo;</div>
                        <h2 class="font-heading text-2xl xl:text-3xl font-semibold text-[#292524] leading-snug tracking-tight">
                            Dengan Stockify, Anda dapat benar-benar mengorganisir seluruh inventaris bisnis tanpa celah.
                        </h2>

                        <!-- Profil Pelanggan / Manajer Gudang -->
                        <div class="flex items-center gap-3.5 mt-8">
                            <div class="w-12 h-12 rounded-full bg-[#BD6745] text-white font-heading font-semibold text-base flex items-center justify-center shadow-xs flex-shrink-0">
                                RS
                            </div>
                            <div>
                                <div class="font-heading font-semibold text-sm text-[#292524]">Raghav Sukhadia</div>
                                <div class="text-xs text-[#78716C]">Direktur Operasional, Sunkool Logistics</div>
                            </div>
                        </div>

                        <!-- Sketsa Isometrik Rak Penyimpanan Gudang -->
                        <div class="mt-8 pt-6 border-t border-[#E7E0DA]/70">
                            <svg class="w-full max-w-sm h-48 mx-auto text-[#BD6745]/80" viewBox="0 0 400 240" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <!-- Tiang Utama Rak Gudang -->
                                <line x1="60" y1="20" x2="60" y2="220" stroke="#BD6745" stroke-width="2.5" stroke-linecap="round"/>
                                <line x1="340" y1="20" x2="340" y2="220" stroke="#BD6745" stroke-width="2.5" stroke-linecap="round"/>
                                <line x1="200" y1="20" x2="200" y2="220" stroke="#BD6745" stroke-width="1.5" stroke-dasharray="3 3"/>
                                
                                <!-- Hambatan Rak Baris 1 (Atas) -->
                                <rect x="55" y="60" width="290" height="6" rx="2" fill="#E7E0DA" stroke="#BD6745" stroke-width="1.5"/>
                                <!-- Kotak/Karton Baris 1 -->
                                <rect x="80" y="25" width="45" height="35" rx="3" fill="#FFFFFF" stroke="#BD6745" stroke-width="1.5"/>
                                <line x1="80" y1="42" x2="125" y2="42" stroke="#BD6745" stroke-width="1"/>
                                <rect x="135" y="15" width="55" height="45" rx="3" fill="#F5E6DE" stroke="#BD6745" stroke-width="1.5"/>
                                <line x1="162" y1="15" x2="162" y2="60" stroke="#BD6745" stroke-width="1"/>
                                <rect x="220" y="30" width="40" height="30" rx="3" fill="#FFFFFF" stroke="#BD6745" stroke-width="1.5"/>
                                <rect x="270" y="20" width="55" height="40" rx="3" fill="#FAF7F4" stroke="#BD6745" stroke-width="1.5"/>

                                <!-- Hambatan Rak Baris 2 (Tengah) -->
                                <rect x="55" y="130" width="290" height="6" rx="2" fill="#E7E0DA" stroke="#BD6745" stroke-width="1.5"/>
                                <!-- Kotak/Karton Baris 2 -->
                                <rect x="75" y="85" width="60" height="45" rx="3" fill="#F5E6DE" stroke="#BD6745" stroke-width="1.5"/>
                                <rect x="145" y="95" width="45" height="35" rx="3" fill="#FFFFFF" stroke="#BD6745" stroke-width="1.5"/>
                                <rect x="200" y="80" width="65" height="50" rx="3" fill="#FFFFFF" stroke="#BD6745" stroke-width="1.5"/>
                                <line x1="200" y1="105" x2="265" y2="105" stroke="#BD6745" stroke-width="1"/>
                                <rect x="275" y="90" width="50" height="40" rx="3" fill="#F5E6DE" stroke="#BD6745" stroke-width="1.5"/>

                                <!-- Hambatan Rak Baris 3 (Bawah) -->
                                <rect x="55" y="200" width="290" height="6" rx="2" fill="#E7E0DA" stroke="#BD6745" stroke-width="1.5"/>
                                <!-- Palet Kayu & Kotak Besar Baris 3 -->
                                <rect x="75" y="150" width="80" height="50" rx="3" fill="#FFFFFF" stroke="#BD6745" stroke-width="1.5"/>
                                <line x1="75" y1="175" x2="155" y2="175" stroke="#BD6745" stroke-width="1"/>
                                <rect x="165" y="145" width="75" height="55" rx="3" fill="#F5E6DE" stroke="#BD6745" stroke-width="1.5"/>
                                <rect x="250" y="155" width="80" height="45" rx="3" fill="#FFFFFF" stroke="#BD6745" stroke-width="1.5"/>
                            </svg>
                        </div>
                    </div>

                    <!-- SLIDE 2: Metrik Efisiensi Terukur -->
                    <div id="slide-2" class="carousel-slide hidden transition-opacity duration-300">
                        <span class="inline-block text-[11px] uppercase tracking-[0.2em] text-[#A34E30] font-semibold mb-2">Dampak Nyata Sistem</span>
                        <h2 class="font-heading text-2xl xl:text-3xl font-semibold text-[#292524] leading-snug tracking-tight">
                            Lebih dari 80% pelanggan pergudangan kami merasakan peningkatan efisiensi yang konsisten.*
                        </h2>

                        <!-- 3 Kartu Metrik -->
                        <div class="space-y-3.5 mt-7">
                            <div class="flex items-center gap-3.5 p-3 rounded-xl bg-[#FFFFFF] border border-[#E7E0DA] shadow-xs">
                                <div class="w-9 h-9 rounded-lg bg-[#F5E6DE] text-[#A34E30] flex items-center justify-center flex-shrink-0 font-bold text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div>
                                    <div class="font-heading font-semibold text-sm text-[#292524]">Hemat 1.3 Jam per Hari</div>
                                    <div class="text-xs text-[#78716C]">Pada alur pemrosesan pesanan & mutasi barang</div>
                                </div>
                            </div>

                            <div class="flex items-center gap-3.5 p-3 rounded-xl bg-[#FFFFFF] border border-[#E7E0DA] shadow-xs">
                                <div class="w-9 h-9 rounded-lg bg-[#F5E6DE] text-[#A34E30] flex items-center justify-center flex-shrink-0 font-bold text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                                </div>
                                <div>
                                    <div class="font-heading font-semibold text-sm text-[#292524]">20–30% Pengurangan Biaya</div>
                                    <div class="text-xs text-[#78716C]">Mencegah dead-stock & kelebihan stok menumpuk</div>
                                </div>
                            </div>

                            <div class="flex items-center gap-3.5 p-3 rounded-xl bg-[#FFFFFF] border border-[#E7E0DA] shadow-xs">
                                <div class="w-9 h-9 rounded-lg bg-[#F5E6DE] text-[#A34E30] flex items-center justify-center flex-shrink-0 font-bold text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div>
                                    <div class="font-heading font-semibold text-sm text-[#292524]">35–50% Otomatisasi Tugas</div>
                                    <div class="text-xs text-[#78716C]">Penyesuaian stok opname dan pelaporan otomatis</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SLIDE 3: 3 Pilar Utama Pergudangan -->
                    <div id="slide-3" class="carousel-slide hidden transition-opacity duration-300">
                        <span class="inline-block text-[11px] uppercase tracking-[0.2em] text-[#A34E30] font-semibold mb-2">Arsitektur Logistik Modern</span>
                        <h2 class="font-heading text-2xl xl:text-3xl font-semibold text-[#292524] leading-snug tracking-tight">
                            Tiga pilar kontrol terintegrasi untuk kelancaran operasional gudang Anda.
                        </h2>

                        <!-- 3 Pilar -->
                        <div class="grid grid-cols-3 gap-2.5 mt-7 text-center">
                            <div class="p-3 rounded-xl bg-[#FFFFFF] border border-[#E7E0DA]">
                                <div class="w-8 h-8 mx-auto rounded-lg bg-[#FAF7F4] text-[#BD6745] flex items-center justify-center mb-1.5 font-bold text-xs">
                                    01
                                </div>
                                <div class="font-heading font-semibold text-xs text-[#292524]">Katalog SKU</div>
                                <div class="text-[10px] text-[#78716C] mt-0.5">Multi-atribut & ambang batas</div>
                            </div>

                            <div class="p-3 rounded-xl bg-[#FFFFFF] border border-[#E7E0DA]">
                                <div class="w-8 h-8 mx-auto rounded-lg bg-[#FAF7F4] text-[#BD6745] flex items-center justify-center mb-1.5 font-bold text-xs">
                                    02
                                </div>
                                <div class="font-heading font-semibold text-xs text-[#292524]">Mutasi Arus</div>
                                <div class="text-[10px] text-[#78716C] mt-0.5">Barang Masuk & Keluar</div>
                            </div>

                            <div class="p-3 rounded-xl bg-[#FFFFFF] border border-[#E7E0DA]">
                                <div class="w-8 h-8 mx-auto rounded-lg bg-[#FAF7F4] text-[#BD6745] flex items-center justify-center mb-1.5 font-bold text-xs">
                                    03
                                </div>
                                <div class="font-heading font-semibold text-xs text-[#292524]">Opname Audit</div>
                                <div class="text-[10px] text-[#78716C] mt-0.5">Rekonsiliasi fisik & log</div>
                            </div>
                        </div>

                        <!-- Sketsa Ban Berjalan & Kotak Tersegel -->
                        <div class="mt-8 pt-6 border-t border-[#E7E0DA]/70">
                            <svg class="w-full max-w-sm h-40 mx-auto text-[#BD6745]/80" viewBox="0 0 360 160" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <!-- Ban Berjalan (Conveyor Belt) -->
                                <path d="M30 110 L330 110 Q340 110 340 120 Q340 130 330 130 L30 130 Q20 130 20 120 Q20 110 30 110 Z" fill="#FAF7F4" stroke="#BD6745" stroke-width="2"/>
                                <!-- Roda Pemutar -->
                                <circle cx="40" cy="120" r="6" stroke="#BD6745" stroke-width="1.5" fill="#FFFFFF"/>
                                <circle cx="100" cy="120" r="6" stroke="#BD6745" stroke-width="1.5" fill="#FFFFFF"/>
                                <circle cx="180" cy="120" r="6" stroke="#BD6745" stroke-width="1.5" fill="#FFFFFF"/>
                                <circle cx="260" cy="120" r="6" stroke="#BD6745" stroke-width="1.5" fill="#FFFFFF"/>
                                <circle cx="320" cy="120" r="6" stroke="#BD6745" stroke-width="1.5" fill="#FFFFFF"/>
                                
                                <!-- Paket / Karton di atas Ban -->
                                <rect x="50" y="65" width="50" height="45" rx="3" fill="#FFFFFF" stroke="#BD6745" stroke-width="1.5"/>
                                <line x1="75" y1="65" x2="75" y2="110" stroke="#BD6745" stroke-width="1"/>
                                
                                <rect x="130" y="55" width="60" height="55" rx="3" fill="#F5E6DE" stroke="#BD6745" stroke-width="1.5"/>
                                <line x1="160" y1="55" x2="160" y2="110" stroke="#BD6745" stroke-width="1"/>
                                <line x1="130" y1="82" x2="190" y2="82" stroke="#BD6745" stroke-width="1"/>

                                <rect x="220" y="60" width="55" height="50" rx="3" fill="#FFFFFF" stroke="#BD6745" stroke-width="1.5"/>
                                <line x1="247" y1="60" x2="247" y2="110" stroke="#BD6745" stroke-width="1"/>
                            </svg>
                        </div>
                    </div>

                </div>

                <!-- Indikator Paginasi Dot 3 Slide (Interaktif) -->
                <div class="flex items-center gap-2 mt-8 pt-4 border-t border-[#E7E0DA]/70 z-10">
                    <button type="button" onclick="setSlide(1)" id="dot-1" class="h-2 w-8 rounded-full bg-[#A34E30] transition-all duration-200 cursor-pointer" title="Slide 1"></button>
                    <button type="button" onclick="setSlide(2)" id="dot-2" class="h-2 w-2 rounded-full bg-[#E7E0DA] hover:bg-[#BD6745]/60 transition-all duration-200 cursor-pointer" title="Slide 2"></button>
                    <button type="button" onclick="setSlide(3)" id="dot-3" class="h-2 w-2 rounded-full bg-[#E7E0DA] hover:bg-[#BD6745]/60 transition-all duration-200 cursor-pointer" title="Slide 3"></button>
                </div>

            </div>
        </div>

        <!-- ========================================== -->
        <!-- SISI KANAN: FORM LOGIN SHOPIFY-GRADE       -->
        <!-- ========================================== -->
        <div class="lg:col-span-7 xl:col-span-6 flex flex-col justify-center px-6 sm:px-12 lg:px-16 xl:px-24 py-10 bg-[#FFFFFF]">
            <div class="w-full max-w-md mx-auto">

                <!-- Header Logo Brand -->
                <div class="flex items-center justify-between mb-8">
                    <a href="{{ route('landing') }}" class="flex items-center gap-3 group">
                        <div class="w-10 h-10 rounded-xl bg-[#F5E6DE] border border-[#BD6745]/30 flex items-center justify-center text-[#BD6745] font-heading font-bold text-lg shadow-xs group-hover:bg-[#A34E30] group-hover:text-white transition">
                            S
                        </div>
                        <div>
                            <span class="font-heading text-xl font-bold tracking-tight text-[#292524] block">Stockify</span>
                            <span class="text-[9px] uppercase tracking-[0.2em] text-[#78716C] font-semibold block">Warehouse System</span>
                        </div>
                    </a>
                    
                    <a href="{{ route('landing') }}" class="text-xs font-semibold text-[#78716C] hover:text-[#A34E30] transition flex items-center gap-1">
                        <span>Showroom</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </a>
                </div>

                <!-- Judul Utama -->
                <div class="mb-6">
                    <h1 class="font-heading text-3xl font-bold tracking-tight text-[#292524]">
                        Let's get started
                    </h1>
                    <p class="text-xs text-[#78716C] mt-1.5">
                        Masuk ke portal kendali inventaris & logistik pergudangan terpadu.
                    </p>
                </div>

                <!-- Notifikasi Error / Info -->
                @if ($errors->any())
                    <div class="p-3.5 mb-5 rounded-xl bg-red-50 border border-red-200 text-red-800 text-xs flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-red-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                @if (session('info'))
                    <div class="p-3.5 mb-5 rounded-xl bg-[#F5E6DE] border border-[#BD6745]/40 text-[#1C1917] text-xs flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-[#A34E30] flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                        <span>{{ session('info') }}</span>
                    </div>
                @endif

                <!-- Formulir Autentikasi -->
                <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                    @csrf

                    <!-- Field Email -->
                    <div>
                        <label for="email" class="block text-xs font-semibold text-[#292524] mb-1.5">
                            Alamat Email Pengguna
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#78716C]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"/></svg>
                            </div>
                            <input type="email" name="email" id="email" value="{{ old('email', 'admin@stockify.test') }}" required
                                class="w-full pl-10 pr-3.5 py-2.5 text-xs bg-[#FFFFFF] border border-[#E7E0DA] rounded-xl text-[#292524] placeholder-[#78716C]/60 focus:outline-none focus:border-[#A34E30] focus:ring-2 focus:ring-[#F5E6DE] transition"
                                placeholder="nama@perusahaan.com">
                        </div>
                    </div>

                    <!-- Field Password dengan Toggle Eye -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-xs font-semibold text-[#292524]">
                                Kata Sandi
                            </label>
                            <span class="text-[11px] text-[#78716C]">Minimal 8 karakter</span>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#78716C]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </div>
                            <input type="password" name="password" id="password" value="password" required
                                class="w-full pl-10 pr-10 py-2.5 text-xs bg-[#FFFFFF] border border-[#E7E0DA] rounded-xl text-[#292524] placeholder-[#78716C]/60 focus:outline-none focus:border-[#A34E30] focus:ring-2 focus:ring-[#F5E6DE] transition"
                                placeholder="••••••••">
                            <button type="button" onclick="togglePasswordVisibility()" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-[#78716C] hover:text-[#A34E30] transition cursor-pointer" title="Tampilkan/Sembunyikan sandi">
                                <svg id="eye-icon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Checkbox Ingat Sesi -->
                    <div class="flex items-center justify-between pt-1">
                        <label class="flex items-center cursor-pointer select-none">
                            <input type="checkbox" name="remember" id="remember" checked
                                class="w-4 h-4 rounded border-[#E7E0DA] text-[#A34E30] focus:ring-[#A34E30]">
                            <span class="ml-2 text-xs text-[#78716C]">Ingat sesi saya di perangkat ini</span>
                        </label>
                    </div>

                    <!-- Tombol Masuk Primer DEEP TERRACOTTA (#A34E30) -->
                    <button type="submit"
                        class="w-full py-3 px-4 rounded-xl text-xs font-heading font-semibold tracking-wide text-white bg-[#A34E30] hover:bg-[#8C3D22] transition shadow-xs mt-3 cursor-pointer flex items-center justify-center gap-2">
                        <span>Masuk ke Dashboard</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </form>

                <!-- Pilihan Akun Demo 1-Klik Fungsional -->
                <div class="mt-8 pt-6 border-t border-[#E7E0DA]">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-[11px] font-semibold uppercase tracking-wider text-[#78716C]">Akses Cepat Akun Demo:</span>
                        <span class="text-[10px] text-[#A34E30] font-medium font-mono">1-Klik Isi Otomatis</span>
                    </div>

                    <div class="grid grid-cols-3 gap-2">
                        <button type="button" onclick="fillDemo('admin@stockify.test')"
                            class="p-2.5 rounded-xl bg-[#FAF7F4] hover:bg-[#F5E6DE] border border-[#E7E0DA] hover:border-[#A34E30]/40 text-center transition group cursor-pointer">
                            <span class="block text-xs font-heading font-semibold text-[#292524] group-hover:text-[#A34E30]">Admin</span>
                            <span class="block text-[10px] text-[#78716C] mt-0.5">Akses Penuh</span>
                        </button>
                        
                        <button type="button" onclick="fillDemo('manager@stockify.test')"
                            class="p-2.5 rounded-xl bg-[#FAF7F4] hover:bg-[#F5E6DE] border border-[#E7E0DA] hover:border-[#A34E30]/40 text-center transition group cursor-pointer">
                            <span class="block text-xs font-heading font-semibold text-[#292524] group-hover:text-[#A34E30]">Manajer</span>
                            <span class="block text-[10px] text-[#78716C] mt-0.5">Otorisasi</span>
                        </button>

                        <button type="button" onclick="fillDemo('staff@stockify.test')"
                            class="p-2.5 rounded-xl bg-[#FAF7F4] hover:bg-[#F5E6DE] border border-[#E7E0DA] hover:border-[#A34E30]/40 text-center transition group cursor-pointer">
                            <span class="block text-xs font-heading font-semibold text-[#292524] group-hover:text-[#A34E30]">Staff</span>
                            <span class="block text-[10px] text-[#78716C] mt-0.5">Operasional</span>
                        </button>
                    </div>
                </div>

                <!-- Footer Kembali -->
                <div class="text-center mt-8 pt-4">
                    <a href="{{ route('landing') }}" class="text-xs text-[#78716C] hover:text-[#A34E30] transition inline-flex items-center gap-1.5 font-medium">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        <span>Kembali ke Halaman Depan & Showroom</span>
                    </a>
                </div>

            </div>
        </div>

    </div>

    <!-- Script Interaktif Carousel & Demo Filler -->
    <script>
        let currentSlide = 1;
        let slideTimer;

        function setSlide(slideNum) {
            currentSlide = slideNum;
            for (let i = 1; i <= 3; i++) {
                const slide = document.getElementById('slide-' + i);
                const dot = document.getElementById('dot-' + i);
                if (slide && dot) {
                    if (i === slideNum) {
                        slide.classList.remove('hidden');
                        dot.className = 'h-2 w-8 rounded-full bg-[#A34E30] transition-all duration-200 cursor-pointer';
                    } else {
                        slide.classList.add('hidden');
                        dot.className = 'h-2 w-2 rounded-full bg-[#E7E0DA] hover:bg-[#BD6745]/60 transition-all duration-200 cursor-pointer';
                    }
                }
            }
            resetTimer();
        }

        function resetTimer() {
            clearInterval(slideTimer);
            slideTimer = setInterval(() => {
                let next = currentSlide >= 3 ? 1 : currentSlide + 1;
                setSlide(next);
            }, 7000);
        }

        function fillDemo(email) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = 'password';
        }

        function togglePasswordVisibility() {
            const passInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            if (passInput.type === 'password') {
                passInput.type = 'text';
                eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>';
            } else {
                passInput.type = 'password';
                eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            resetTimer();
        });
    </script>
</body>
</html>