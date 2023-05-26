<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $description ?? 'Welkom bij de homepagina' }}">
    <x-tmk.favicons></x-tmk.favicons>
    <title>PlatteBerg: {{ $title ?? '' }}</title>
{{--    <link rel="stylesheet" href="/build/assets/app-cea4fce0.css" />--}}
{{--    <link rel="stylesheet" href="/build/assets/app-660cf8f0.css" />--}}
{{--    <script type="module" src="/build/assets/app-5367c89a.js"></script>--}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased">
<div class="flex flex-col space-y-4 min-h-screen text-gray-800 bg-gray-100">
    <header class="w-full h-20 top-0 left-0" id="top">
        {{--  Navigation  --}}
        @livewire('layout.nav-bar')
    </header>
    <main class="container mx-auto p-4 flex-1 px-4">
        {{-- Title --}}
        <h1 class="text-3xl mb-4">
            {{ $title ?? 'Titel hier...' }}
        </h1>
        {{-- Main content --}}
        {{ $slot }}
    </main>
    <x-tmk.footer/>
    <!---#GO TO TOP-->
    <a href="#top" class="go-top active" aria-label="Go To Top" data-go-top>
        <x-heroicon-o-arrow-small-up />
    </a>
</div>
@stack('script')
@livewireScripts
</body>

</html>
