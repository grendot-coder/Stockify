<footer class="mt-12 py-6 px-6 border-t border-[#343A40] flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-[#A1A1AA] bg-transparent">
    <div class="flex items-center gap-2">
        <span class="font-editorial text-sm font-medium text-[#F5F5F5]">Stockify</span>
        <span>•</span>
        <span>Warehouse Management System</span>
    </div>
    <div class="flex items-center gap-4 text-[11px]">
        <a href="{{ route('landing') }}" class="hover:text-[#F5C842] transition">Halaman Depan</a>
        <span>•</span>
        <a href="{{ route('products.index') }}" class="hover:text-[#F5C842] transition">Katalog Produk</a>
        <span>•</span>
        <a href="{{ route('transactions.index') }}" class="hover:text-[#F5C842] transition">Mutasi Stok</a>
    </div>
    <div class="text-[11px] text-[#A1A1AA]">
        &copy; {{ date('Y') }} Stockify. All rights reserved.
    </div>
</footer>