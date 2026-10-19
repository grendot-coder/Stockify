@props(['icon' => null, 'routeName' => null, 'title' => null])
@php
    $isActive = request()->routeIs($routeName);
@endphp
<li>
    <a href="{{ route($routeName) }}"
        class="flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all group {{ $isActive ? 'bg-[#F5C842] text-[#101214] font-semibold shadow-xs' : 'text-[#A1A1AA] hover:text-[#F5F5F5] hover:bg-[#2A3036]' }}">
        <span class="{{ $isActive ? 'text-[#101214]' : 'text-[#A1A1AA] group-hover:text-[#F5F5F5]' }}">
            {{ $icon }}
        </span>
        <span class="ml-2.5 truncate" sidebar-toggle-item> {{ $title }} </span>
    </a>
</li>
