<nav class="fixed z-50 w-full bg-[#181C20] border-b border-[#343A40] shadow-[0_1px_2px_0_rgba(0,0,0,0.3)]">
    <div class="px-5 py-3">
        <div class="flex items-center justify-between">
            
            <!-- Sisi Kiri: Menu Hamburger (Mobile) & Logo -->
            <div class="flex items-center gap-3">
                <button id="toggleSidebarMobile" type="button" aria-expanded="false" aria-controls="sidebar" 
                    class="p-2 text-[#A1A1AA] rounded-lg lg:hidden hover:text-[#F5F5F5] hover:bg-[#2A3036] focus:outline-none focus:ring-1 focus:ring-[#F5C842]"
                    title="Buka Menu Navigasi">
                    <svg id="toggleSidebarMobileHamburger" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <svg id="toggleSidebarMobileClose" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>


                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                    <div class="w-8 h-8 rounded-lg bg-[#F5C842] border border-[#F5C842]/40 text-[#101214] flex items-center justify-center font-heading font-bold text-base shadow-sm group-hover:bg-[#E8B92E] transition">
                        S
                    </div>
                    <div class="flex flex-col">
                        <span class="font-heading text-lg font-semibold tracking-tight text-[#F5F5F5]">Stockify</span>
                        <span class="text-[9px] uppercase tracking-[0.18em] text-[#A1A1AA] -mt-0.5 font-medium">Warehouse OS</span>
                    </div>
                </a>

                <!-- Quick Link to Landing Page -->
                <a href="{{ route('landing') }}" target="_blank"
                    class="hidden md:inline-flex items-center gap-1.5 ml-4 px-2.5 py-1 rounded-md text-[11px] font-medium text-[#A1A1AA] hover:text-[#F5C842] hover:bg-[#2A3036] border border-transparent hover:border-[#343A40] transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    <span>Halaman Depan</span>
                </a>
            </div>

            <!-- Sisi Kanan: Info Pengguna & Logout -->
            <div class="flex items-center gap-3">
                @auth
                    @php
                        $roleBadge = match(Auth::user()->role) {
                            'admin' => ['bg' => 'bg-[#F5C842]/15 text-[#F5C842] border-[#F5C842]/30', 'label' => 'Administrator'],
                            'manager' => ['bg' => 'bg-[#202429] text-[#F5F5F5] border-[#343A40]', 'label' => 'Manajer Gudang'],
                            default => ['bg' => 'bg-[#202429] text-[#A1A1AA] border-[#343A40]', 'label' => 'Staff Gudang'],
                        };
                    @endphp

                    <div class="hidden sm:flex items-center gap-2 pr-3 border-r border-[#343A40]">
                        <div class="text-right">
                            <div class="text-xs font-semibold text-[#F5F5F5] leading-none">
                                {{ Auth::user()->name }}
                            </div>
                            <span class="inline-block mt-1 px-2 py-0.5 text-[10px] font-semibold tracking-wide rounded border {{ $roleBadge['bg'] }}">
                                {{ $roleBadge['label'] }}
                            </span>
                        </div>
                    </div>

                    <!-- Tombol Keluar -->
                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" 
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-[#A1A1AA] hover:text-[#101214] bg-[#202429] hover:bg-[#F5C842] border border-[#343A40] hover:border-[#F5C842] rounded-lg transition"
                            title="Keluar dari akun">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span>Keluar</span>
                        </button>
                    </form>
                @endauth
            </div>

        </div>
    </div>
</nav>
