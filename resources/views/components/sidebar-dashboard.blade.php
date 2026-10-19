@php
    $url = explode('/', request()->url());
    $page_slug = $url[count($url) - 2] ?? '';
@endphp

<aside id="sidebar" class="fixed top-0 left-0 z-20 flex flex-col flex-shrink-0 hidden w-64 h-full pt-16 font-normal duration-75 lg:flex transition-width" aria-label="Sidebar">
  <div class="relative flex flex-col flex-1 min-h-0 pt-0 bg-[#FFFFFF] border-r border-[#E7E0DA]">
    <div class="flex flex-col flex-1 pt-5 pb-4 overflow-y-auto">
      <div class="flex-1 px-3 space-y-1 bg-[#FFFFFF]">
        <ul class="pb-2 space-y-1">
            {{ $slot }}
        </ul>
      </div>
    </div>
  </div>
</aside>

<div class="fixed inset-0 z-10 hidden bg-[#1C1917]/30 backdrop-blur-xs" id="sidebarBackdrop"></div>
