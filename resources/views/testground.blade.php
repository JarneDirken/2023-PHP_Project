<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs" defer></script>
    <meta name="description" content="Welcome to the Vinyl Shop">
    <title>The vinyl Shop</title>
</head>
<body class="font-sans antialiased">
<div class="flex flex-col space-y-4 min-h-screen text-gray-800 bg-gray-100">
    <x-tmk.header>
    </x-tmk.header>
    <main class="container mx-auto p-4 flex-1 px-4">
        {{-- Title --}}
        <h1 class="text-3xl mb-4">

        </h1>
        {{-- Main content --}}

    </main>
    <footer class="container mx-auto p-4 text-sm border-t flex justify-between items-center">
        <div>The Vinyl Shop - © {{ date('Y') }}</div>
        <div>Build with Laravel {{ app()->version() }}</div>
    </footer>
</div>
</body>
</html>
