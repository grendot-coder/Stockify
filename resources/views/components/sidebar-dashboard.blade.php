@php
    $url = explode('/', request()->url());
    $page_slug = $url[count($url) - 2] ?? '';
@endphp

<aside id="sidebar" 
    class="fixed top-0 bottom-0 left-0 z-40 flex flex-col flex-shrink-0 hidden w-64 h-screen pt-16 font-normal duration-75 lg:flex bg-[#181C20] border-r border-[#343A40] shadow-2xl lg:shadow-none" 
    aria-label="Sidebar">
  <div class="relative flex flex-col flex-1 min-h-0 pt-0 bg-[#181C20]">
    <div class="flex flex-col flex-1 pt-4 pb-4 overflow-y-auto">
      <div class="flex-1 px-3 space-y-1 bg-[#181C20]">
        <ul class="pb-2 space-y-1">
            {{ $slot }}
        </ul>
      </div>
    </div>
  </div>
</aside>

<div class="fixed inset-0 z-30 hidden bg-black/60 backdrop-blur-sm" id="sidebarBackdrop"></div>

