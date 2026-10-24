@php
$user = Auth::user();
$role = $user->role ?? 'staff';
@endphp

<x-sidebar-dashboard>
    <!-- Dashboard -->
    <x-sidebar-menu-dashboard routeName="dashboard" title="Dashboard">
        <x-slot name="icon">
            <svg class="w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 20 20">
                <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
            </svg>
        </x-slot>
    </x-sidebar-menu-dashboard>

    <!-- Master Data (Admin, Manager, Staff) -->
    <x-sidebar-menu-dropdown-dashboard routeName="products.*,categories.*,suppliers.*,locations.*" title="Master Data">
        <x-slot name="icon">
            <svg class="w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"></path>
            </svg>
        </x-slot>
        <x-sidebar-menu-dropdown-item-dashboard routeName="products.index" title="Data Produk" />
        @if(in_array($role, ['admin', 'manager']))
        <x-sidebar-menu-dropdown-item-dashboard routeName="categories.index" title="Kategori Produk" />
        <x-sidebar-menu-dropdown-item-dashboard routeName="suppliers.index" title="Data Supplier" />
        <x-sidebar-menu-dropdown-item-dashboard routeName="locations.index" title="Rak & Lokasi" />
        @endif
    </x-sidebar-menu-dropdown-dashboard>

    <!-- Manajemen Stok & Transaksi -->
    <x-sidebar-menu-dropdown-dashboard routeName="transactions.*,opnames.*" title="Manajemen Stok">
        <x-slot name="icon">
            <svg class="w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 20 20">
                <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"></path>
                <path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd"></path>
            </svg>
        </x-slot>
        <x-sidebar-menu-dropdown-item-dashboard routeName="transactions.index" title="Riwayat Transaksi" />
        <x-sidebar-menu-dropdown-item-dashboard routeName="transactions.inbound" title="Barang Masuk (Inbound)" />
        <x-sidebar-menu-dropdown-item-dashboard routeName="transactions.outbound" title="Barang Keluar (Outbound)" />
        <x-sidebar-menu-dropdown-item-dashboard routeName="opnames.index" title="Stock Opname" />
    </x-sidebar-menu-dropdown-dashboard>

    <!-- Laporan & Ekspor (Admin & Manager) -->
    @if(in_array($role, ['admin', 'manager']))
    <x-sidebar-menu-dropdown-dashboard routeName="reports.*" title="Laporan & Ekspor">
        <x-slot name="icon">
            <svg class="w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm2 10a1 1 0 10-2 0v3a1 1 0 102 0v-3zm2-3a1 1 0 011 1v5a1 1 0 11-2 0v-5a1 1 0 011-1zm4 4a1 1 0 10-2 0v2a1 1 0 102 0v-2z" clip-rule="evenodd"></path>
            </svg>
        </x-slot>
        <x-sidebar-menu-dropdown-item-dashboard routeName="reports.stock" title="Laporan Stok" />
        <x-sidebar-menu-dropdown-item-dashboard routeName="reports.transactions" title="Laporan Transaksi" />
    </x-sidebar-menu-dropdown-dashboard>
    @endif

    <!-- Menu Khusus Admin -->
    @if($role === 'admin')
    <x-sidebar-menu-dropdown-dashboard routeName="users.*,logs.*" title="Pengguna & Sistem">
        <x-slot name="icon">
            <svg class="w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
            </svg>
        </x-slot>
        <x-sidebar-menu-dropdown-item-dashboard routeName="users.index" title="Kelola Pengguna" />
        <x-sidebar-menu-dropdown-item-dashboard routeName="logs.index" title="Audit Log Aktivitas" />
    </x-sidebar-menu-dropdown-dashboard>

    <x-sidebar-menu-dashboard routeName="settings.index" title="Pengaturan">
        <x-slot name="icon">
            <svg class="w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path>
            </svg>
        </x-slot>
    </x-sidebar-menu-dashboard>
    @endif
</x-sidebar-dashboard>