<footer class="mt-12 py-6 px-6 border-t border-[#E7E0DA] flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-[#78716C] bg-transparent">
    <div class="flex items-center gap-2">
        <span class="font-editorial text-sm font-medium text-[#292524]">Stockify</span>
        <span>•</span>
        <span>Warehouse Management System</span>
    </div>
    <div class="flex items-center gap-4 text-[11px]">
        <a href="{{ route('landing') }}" class="hover:text-[#BD6745] transition">Showroom Video</a>
        <span>•</span>
        <a href="{{ route('products.index') }}" class="hover:text-[#BD6745] transition">Katalog Produk</a>
        <span>•</span>
        <a href="{{ route('transactions.index') }}" class="hover:text-[#BD6745] transition">Mutasi Stok</a>
    </div>
    <div class="text-[11px] text-[#78716C]">
        &copy; {{ date('Y') }} Stockify. All rights reserved.
    </div>
</footer>