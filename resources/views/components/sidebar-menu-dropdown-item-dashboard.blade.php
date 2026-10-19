@props(['icon' => null, 'routeName' => null, 'title' => null])
@php
    $isActive = request()->routeIs($routeName);
@endphp
<li>
    <a href="{{ route($routeName) }}"
        class="text-xs rounded-lg flex items-center px-2.5 py-1.5 transition-all {{ $isActive ? 'bg-[#F5E6DE] text-[#BD6745] font-semibold' : 'text-[#78716C] hover:text-[#292524] hover:bg-[#FAF7F4]' }}">
        <span class="w-1.5 h-1.5 rounded-full mr-2 {{ $isActive ? 'bg-[#BD6745]' : 'bg-[#E7E0DA]' }}"></span>
        <span class="truncate">{{ $title }}</span>
    </a>
</li>
