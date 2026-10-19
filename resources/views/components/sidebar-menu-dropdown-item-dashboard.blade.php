@props(['icon' => null, 'routeName' => null, 'title' => null])
@php
    $isActive = request()->routeIs($routeName);
@endphp
<li>
    <a href="{{ route($routeName) }}"
        class="text-xs rounded-lg flex items-center px-2.5 py-1.5 transition-all {{ $isActive ? 'bg-[#F5C842] text-[#101214] font-semibold' : 'text-[#A1A1AA] hover:text-[#F5F5F5] hover:bg-[#2A3036]' }}">
        <span class="w-1.5 h-1.5 rounded-full mr-2 {{ $isActive ? 'bg-[#101214]' : 'bg-[#343A40]' }}"></span>
        <span class="truncate">{{ $title }}</span>
    </a>
</li>
