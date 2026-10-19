<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Stockify — Warehouse Management System</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600&family=IBM+Plex+Sans:wght@400;500;600&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">

    <style>
        body { 
            font-family: 'IBM Plex Sans', sans-serif; 
            background-color: #101214;
            color: #F5F5F5;
        }
        h1, h2, h3, h4, h5, h6, .font-heading, .font-brand, .font-editorial {
            font-family: 'Space Grotesk', sans-serif;
        }
        .tabular-nums, table, [data-tabular], .font-mono {
            font-variant-numeric: tabular-nums;
            font-feature-settings: "tnum" 1;
        }
    </style>
</head>
<body class="bg-[#101214] text-[#F5F5F5] antialiased selection:bg-[#F5C842] selection:text-[#101214]">
    <x-navbar-dashboard/>
    
    <div class="flex pt-16 overflow-hidden bg-[#101214] min-h-screen">
        <x-sidebar.admin-sidebar/>
        
        <div id="main-content" class="relative w-full h-full overflow-y-auto bg-[#101214] lg:ml-64 flex flex-col justify-between min-h-[calc(100vh-4rem)]">
            <main class="flex-grow p-4 lg:p-7 max-w-7xl mx-auto w-full">
                @yield('content')
            </main>
            <x-footer-dashboard/>
        </div>
    </div>

    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.2/datepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    @stack('scripts')
</body>
</html>
