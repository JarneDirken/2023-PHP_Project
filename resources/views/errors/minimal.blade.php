<x-projectPHP-layout>
    <x-slot name="title"></x-slot>
    <div class="flex flex-col justify-center">
        <div class="grid grid-cols-2 gap-2">
            <p class="text-3xl text-right border-r border-black pr-4">
                @yield('code')
            </p>
            <div class="flex flex-col justify-center pl-3">
                <p class="text-2xl font-light text-gray-400">
                    @yield('message')
                </p>
                <div class="flex gap-2 mt-4">
                    <x-button class="bg-gray-400 hover:bg-gray-500">
                        <a href="{{ route('home') }}">Home</a>
                    </x-button>
                    <x-button class="bg-gray-400 hover:bg-gray-500">
                        <a href="#" onclick="window.history.back();">Back</a>
                    </x-button>
                </div>
            </div>
        </div>
    </div>
</x-projectPHP-layout>
