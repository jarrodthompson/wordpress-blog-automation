<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') · FGOS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full text-slate-800 antialiased">
<div class="flex h-full min-h-screen">
    @include('partials.sidebar')
    <div class="flex-1 flex flex-col min-w-0">
        @include('partials.topbar')
        <main class="flex-1 overflow-y-auto">
            <div class="px-6 py-5 max-w-[1600px] mx-auto w-full">
                @yield('content')
            </div>
        </main>
    </div>
</div>
@stack('scripts')
</body>
</html>
