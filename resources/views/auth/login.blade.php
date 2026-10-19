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
    </style>
</head>
<body class="bg-[#FAF7F4] text-[#292524] min-h-screen flex flex-col justify-center items-center p-4 antialiased selection:bg-[#F5E6DE] selection:text-[#1C1917]">

    <div class="w-full max-w-sm">

        <!-- Logo Brand & Identitas -->
        <div class="text-center mb-8">
            <a href="{{ route('landing') }}" class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-[#F5E6DE] border border-[#BD6745]/30 text-[#BD6745] font-editorial font-bold text-xl shadow-xs mb-3 hover:bg-[#BD6745] hover:text-[#FFFFFF] transition duration-150">
                S
            </a>
            <h1 class="font-editorial text-3xl font-medium tracking-tight text-[#292524]">Stockify</h1>
            <p class="text-xs text-[#78716C] mt-1 font-medium">Warehouse Management System</p>
        </div>

        <!-- Kartu Login Pure White (30%) -->
        <div class="bg-[#FFFFFF] rounded-2xl border border-[#E7E0DA] shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] p-7 sm:p-8">
            <div class="flex items-center justify-between mb-1.5">
                <h2 class="font-editorial text-xl font-medium text-[#292524]">Autentikasi Akun</h2>
                <span class="w-2 h-2 rounded-full bg-[#BD6745]"></span>
            </div>
            <p class="text-xs text-[#78716C] mb-6">Akses portal inventaris dan logistik pergudangan.</p>

            <!-- Pesan Error / Notifikasi -->
            @if ($errors->any())
                <div class="p-3 mb-5 rounded-lg bg-red-50 border border-red-200 text-red-800 text-xs flex items-center gap-2">
                    <svg class="w-4 h-4 text-red-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            @if (session('info'))
                <div class="p-3 mb-5 rounded-lg bg-[#F5E6DE] border border-[#BD6745]/40 text-[#1C1917] text-xs flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#BD6745] flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                    <span>{{ session('info') }}</span>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Input Email -->
                <div>
                    <label for="email" class="block text-xs font-medium text-[#292524] mb-1.5 uppercase tracking-wider">
                        Alamat Email
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email', 'admin@stockify.test') }}" required
                        class="w-full px-3.5 py-2.5 text-xs bg-[#FFFFFF] border border-[#E7E0DA] rounded-xl text-[#292524] placeholder-[#78716C]/60 focus:outline-none focus:border-[#BD6745] focus:ring-2 focus:ring-[#F5E6DE] transition"
                        placeholder="nama@perusahaan.com">
                </div>

                <!-- Input Password -->
                <div>
                    <label for="password" class="block text-xs font-medium text-[#292524] mb-1.5 uppercase tracking-wider">
                        Kata Sandi
                    </label>
                    <input type="password" name="password" id="password" value="password" required
                        class="w-full px-3.5 py-2.5 text-xs bg-[#FFFFFF] border border-[#E7E0DA] rounded-xl text-[#292524] placeholder-[#78716C]/60 focus:outline-none focus:border-[#BD6745] focus:ring-2 focus:ring-[#F5E6DE] transition"
                        placeholder="••••••••">
                </div>

                <!-- Ingat Saya -->
                <div class="flex items-center pt-1">
                    <input type="checkbox" name="remember" id="remember" checked
                        class="w-4 h-4 rounded border-[#E7E0DA] text-[#BD6745] focus:ring-[#BD6745]">
                    <label for="remember" class="ml-2 text-xs text-[#78716C] select-none cursor-pointer">
                        Ingat sesi saya di peramban ini
                    </label>
                </div>

                <!-- Tombol Masuk Soft Terracotta (10%) -->
                <button type="submit"
                    class="w-full py-3 px-4 rounded-xl text-xs font-bold uppercase tracking-wider text-[#1C1917] bg-[#BD6745] hover:bg-[#A55636] transition shadow-xs mt-2 cursor-pointer">
                    Masuk ke Sistem
                </button>
            </form>

            <!-- Quick 1-Click Demo Fill -->
            <div class="mt-6 pt-5 border-t border-[#E7E0DA]">
                <p class="text-[11px] font-medium text-[#78716C] text-center mb-3">
                    Akses Cepat Akun Demo:
                </p>
                <div class="grid grid-cols-3 gap-2">
                    <button type="button" onclick="fillDemo('admin@stockify.test')"
                        class="p-2 rounded-lg bg-[#FAF7F4] hover:bg-[#F5E6DE] border border-[#E7E0DA] hover:border-[#BD6745]/50 text-center transition group cursor-pointer">
                        <span class="block text-xs font-semibold text-[#292524] group-hover:text-[#BD6745]">Admin</span>
                        <span class="block text-[10px] text-[#78716C] mt-0.5">Semua Hak</span>
                    </button>
                    <button type="button" onclick="fillDemo('manager@stockify.test')"
                        class="p-2 rounded-lg bg-[#FAF7F4] hover:bg-[#F5E6DE] border border-[#E7E0DA] hover:border-[#BD6745]/50 text-center transition group cursor-pointer">
                        <span class="block text-xs font-semibold text-[#292524] group-hover:text-[#BD6745]">Manajer</span>
                        <span class="block text-[10px] text-[#78716C] mt-0.5">Otorisasi</span>
                    </button>
                    <button type="button" onclick="fillDemo('staff@stockify.test')"
                        class="p-2 rounded-lg bg-[#FAF7F4] hover:bg-[#F5E6DE] border border-[#E7E0DA] hover:border-[#BD6745]/50 text-center transition group cursor-pointer">
                        <span class="block text-xs font-semibold text-[#292524] group-hover:text-[#BD6745]">Staff</span>
                        <span class="block text-[10px] text-[#78716C] mt-0.5">Operasional</span>
                    </button>
                </div>
            </div>

        </div>

        <div class="text-center mt-6">
            <a href="{{ route('landing') }}" class="text-xs text-[#78716C] hover:text-[#BD6745] transition">
                &larr; Kembali ke Halaman Depan & Showroom
            </a>
        </div>

    </div>

    <script>
        function fillDemo(email) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = 'password';
        }
    </script>
</body>
</html>