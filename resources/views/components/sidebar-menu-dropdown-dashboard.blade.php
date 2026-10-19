@props(['icon' => null, 'routeName' => null, 'title' => null])
@php
    $id = 'dropdown-' . \Illuminate\Support\Str::slug($routeName);
    $isActive = request()->routeIs(explode(',', $routeName));
@endphp
<li>
    <button type="button"
        class="flex items-center w-full px-3 py-2 text-xs font-medium rounded-lg transition-all group {{ $isActive ? 'text-[#BD6745] font-semibold bg-[#F5E6DE]' : 'text-[#78716C] hover:text-[#292524] hover:bg-[#FAF7F4]' }}"
        aria-controls="{{ $id }}" data-collapse-toggle="{{ $id }}">
        <span class="{{ $isActive ? 'text-[#BD6745]' : 'text-[#78716C] group-hover:text-[#292524]' }}">
            {{ $icon }}
        </span>
        <span class="flex-1 ml-2.5 text-left truncate" sidebar-toggle-item>{{ $title }}</span>
        <svg sidebar-toggle-item class="w-3.5 h-3.5 text-[#78716C] transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>
    <ul id="{{ $id }}" class="space-y-1 py-1 pl-6 border-l border-[#E7E0DA] ml-4 mt-1 {{ $isActive ? 'block' : 'hidden' }}">
        {{ $slot }}
    </ul>
</li>
