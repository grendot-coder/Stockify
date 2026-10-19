@props(['icon' => null, 'routeName' => null, 'title' => null])
@php
    $isActive = request()->routeIs($routeName);
@endphp
<li>
    <a href="{{ route($routeName) }}"
        class="flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all group {{ $isActive ? 'bg-[#F5E6DE] text-[#BD6745] font-semibold shadow-xs' : 'text-[#78716C] hover:text-[#292524] hover:bg-[#FAF7F4]' }}">
        <span class="{{ $isActive ? 'text-[#BD6745]' : 'text-[#78716C] group-hover:text-[#292524]' }}">
            {{ $icon }}
        </span>
        <span class="ml-2.5 truncate" sidebar-toggle-item> {{ $title }} </span>
    </a>
</li>
