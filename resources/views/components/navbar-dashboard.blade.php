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


                @php
                    $navAppName = \App\Models\Setting::get('app_name', 'Stockify');
                    $navAppTagline = \App\Models\Setting::get('app_tagline', 'Warehouse OS');
                    $navAppLogo = \App\Models\Setting::get('app_logo');
                @endphp
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                    @if($navAppLogo && file_exists(public_path('storage/' . $navAppLogo)))
                        <img src="{{ asset('storage/' . $navAppLogo) }}" alt="{{ $navAppName }}" class="w-8 h-8 rounded-lg object-contain bg-[#202429] border border-[#343A40]">
                    @else
                        <div class="w-8 h-8 rounded-lg bg-[#F5C842] border border-[#F5C842]/40 text-[#101214] flex items-center justify-center font-heading font-bold text-base shadow-sm group-hover:bg-[#E8B92E] transition">
                            S
                        </div>
                    @endif
                    <div class="flex flex-col">
                        <span class="font-heading text-lg font-semibold tracking-tight text-[#F5F5F5]">{{ $navAppName }}</span>
                        <span class="text-[9px] uppercase tracking-[0.18em] text-[#A1A1AA] -mt-0.5 font-medium">{{ $navAppTagline }}</span>
                    </div>
                </a>

                <!-- Quick Link to Landing Page -->
                <a href="{{ route('landing') }}" target="_blank"
                    class="hidden md:inline-flex items-center gap-1.5 ml-4 px-2.5 py-1 rounded-md text-[11px] font-medium text-[#A1A1AA] hover:text-[#F5C842] hover:bg-[#2A3036] border border-transparent hover:border-[#343A40] transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    <span>Halaman Depan</span>
                </a>
            </div>

            <div class="relative" id="user-menu-wrapper">
                @auth
                    @php
                        $user = Auth::user();
                        $roleBadge = match($user->role) {
                            'admin' => ['bg' => 'bg-[#F5C842]/15 text-[#F5C842] border-[#F5C842]/30', 'label' => 'Administrator'],
                            'manager' => ['bg' => 'bg-[#202429] text-[#F5F5F5] border-[#343A40]', 'label' => 'Manajer Gudang'],
                            default => ['bg' => 'bg-[#202429] text-[#A1A1AA] border-[#343A40]', 'label' => 'Staff Gudang'],
                        };
                        $avatarPath = file_exists(public_path('images/default-avatar.png')) 
                            ? asset('images/default-avatar.png') 
                            : asset('images/default-avatar.svg');
                    @endphp

                    <button type="button" id="user-menu-btn"
                        class="flex items-center gap-2.5 p-1 sm:px-2.5 sm:py-1.5 rounded-xl hover:bg-[#202429] border border-transparent hover:border-[#343A40] transition cursor-pointer select-none">
                        <div class="w-8 h-8 rounded-full overflow-hidden bg-[#202429] border border-[#343A40] flex items-center justify-center flex-shrink-0">
                            <img src="{{ $avatarPath }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                        </div>

                        <div class="hidden sm:flex flex-col text-left">
                            <span class="text-xs font-semibold text-[#F5F5F5] leading-tight max-w-[140px] truncate">
                                {{ $user->name }}
                            </span>
                            <span class="text-[10px] text-[#A1A1AA]">
                                {{ $roleBadge['label'] }}
                            </span>
                        </div>

                        <svg class="w-3.5 h-3.5 text-[#A1A1AA] transition-transform duration-200" id="user-menu-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div id="user-dropdown-menu" 
                        class="hidden absolute right-0 mt-2 w-56 rounded-2xl bg-[#202429] border border-[#343A40] shadow-xl z-50 py-1.5 divide-y divide-[#343A40]">
                        
                        <div class="px-4 py-2.5">
                            <p class="text-xs font-semibold text-[#F5F5F5] truncate">{{ $user->name }}</p>
                            <p class="text-[11px] text-[#A1A1AA] truncate mt-0.5">{{ $user->email }}</p>
                            <span class="inline-block mt-2 px-2 py-0.5 text-[9px] font-mono font-semibold tracking-wider rounded border {{ $roleBadge['bg'] }}">
                                {{ $roleBadge['label'] }}
                            </span>
                        </div>

                        <div class="py-1 text-xs">
                            @if($user->role === 'admin')
                                <a href="{{ route('settings.index') }}" class="flex items-center gap-2.5 px-4 py-2 text-[#A1A1AA] hover:text-[#F5F5F5] hover:bg-[#181C20] transition">
                                    <svg class="w-4 h-4 text-[#F5C842]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span>Pengaturan Aplikasi</span>
                                </a>
                                <a href="{{ route('logs.index') }}" class="flex items-center gap-2.5 px-4 py-2 text-[#A1A1AA] hover:text-[#F5F5F5] hover:bg-[#181C20] transition">
                                    <svg class="w-4 h-4 text-[#A1A1AA]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Log Aktivitas</span>
                                </a>
                            @elseif($user->role === 'manager')
                                <a href="{{ route('reports.stock') }}" class="flex items-center gap-2.5 px-4 py-2 text-[#A1A1AA] hover:text-[#F5F5F5] hover:bg-[#181C20] transition">
                                    <svg class="w-4 h-4 text-[#F5C842]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <span>Laporan Stok</span>
                                </a>
                            @else
                                <a href="{{ route('products.index') }}" class="flex items-center gap-2.5 px-4 py-2 text-[#A1A1AA] hover:text-[#F5F5F5] hover:bg-[#181C20] transition">
                                    <svg class="w-4 h-4 text-[#F5C842]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                    <span>Katalog Produk</span>
                                </a>
                            @endif
                        </div>

                        <div class="py-1">
                            <form action="{{ route('logout') }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" 
                                    class="w-full flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-rose-400 hover:text-rose-300 hover:bg-rose-500/10 transition cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    <span>Keluar</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth
            </div>

        </div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const userMenuBtn = document.getElementById('user-menu-btn');
        const userDropdownMenu = document.getElementById('user-dropdown-menu');
        const userMenuChevron = document.getElementById('user-menu-chevron');

        if (userMenuBtn && userDropdownMenu) {
            userMenuBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                const isHidden = userDropdownMenu.classList.contains('hidden');
                if (isHidden) {
                    userDropdownMenu.classList.remove('hidden');
                    if (userMenuChevron) userMenuChevron.classList.add('rotate-180');
                } else {
                    userDropdownMenu.classList.add('hidden');
                    if (userMenuChevron) userMenuChevron.classList.remove('rotate-180');
                }
            });

            document.addEventListener('click', function(e) {
                if (!userDropdownMenu.contains(e.target) && !userMenuBtn.contains(e.target)) {
                    userDropdownMenu.classList.add('hidden');
                    if (userMenuChevron) userMenuChevron.classList.remove('rotate-180');
                }
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    userDropdownMenu.classList.add('hidden');
                    if (userMenuChevron) userMenuChevron.classList.remove('rotate-180');
                }
            });
        }
    });
</script>
