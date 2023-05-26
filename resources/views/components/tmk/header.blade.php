<header class="bg-green-300 w-full h-20 top-0 left-0">
<nav class="flex justify-between items-center">
    <div class="flex gap-5">
        <a href="{{ url('/') }}" class="mr-4">
            <img class="h-20 w-20" src="/icon.png" alt="home">
        </a>
        @auth()
            <x-nav-link href="{{ route('home') }}" class="text-gray-800 hover:text-black">Kalender</x-nav-link>
            <x-nav-link href="{{ route('home') }}" class="text-gray-800 hover:text-black">Kledij</x-nav-link>
            <x-nav-link href="{{ route('inschrijven-ritverkenner') }}" class="text-gray-800 hover:text-black">Ritverkenner</x-nav-link>
            <x-nav-link href="{{ route('inschrijven-evenement') }}" class="text-gray-800 hover:text-black">Evenementen</x-nav-link>
            @if(auth()->user()->is_tour_guide)
                <x-nav-link href="{{ route('opnemen-aanwezigheden') }}" class="text-gray-800 hover:text-black text-md">Aanwezigheid opnemen</x-nav-link>
            @endif
        @endauth
    </div>
    <div class="flex gap-5 mr-5">
        @auth()
            @if(auth()->user()->management)
                <x-dropdown class="cursor-pointer mt-0.5">
                    <x-slot name="trigger">
                        <p class="text-sm text-gray-800 hover:text-black inline-block font-medium">Admin</p>
                        <x-phosphor-caret-down class="h-3 w-3 inline-block"></x-phosphor-caret-down>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link href="{{ route('admin.tours') }}">Ritten plannen</x-dropdown-link>
                        <x-dropdown-link href="{{ route('home') }}">Evenementen plannen</x-dropdown-link>
                        <x-dropdown-link href="{{ route('opnemen-aanwezigheden') }}">Aanwezigheid opnemen</x-dropdown-link>
                        <div class="block px-4 pt-2 pb-1 text-xs text-gray-500 border-t border-gray-300">Beheren</div>
                        <x-dropdown-link href="{{ route('admin.memberships') }}">Lidmaatschappen</x-dropdown-link>
                        <x-dropdown-link href="{{ route('home') }}">Seizoenen</x-dropdown-link>
                        <x-dropdown-link href="{{ route('admin.teams') }}">Ploegen</x-dropdown-link>
                        <x-dropdown-link href="{{ route('admin.users') }}">Gebruikers</x-dropdown-link>
                        <x-dropdown-link href="{{ route('admin.points') }}">Punten</x-dropdown-link>
                        <x-dropdown-link href="{{ route('admin.coupons') }}">Kortingsbonnen</x-dropdown-link>
                        <x-dropdown-link href="{{ route('home') }}">Kledij</x-dropdown-link>
                        <x-dropdown-link href="{{ route('home') }}">Maten</x-dropdown-link>
                        <x-dropdown-link href="{{ route('admin.mail-templates') }}">Mail templates</x-dropdown-link>
                        <x-dropdown-link href="{{ route('home') }}">Geslachten</x-dropdown-link>
                        <x-dropdown-link href="{{ route('admin.articles') }}">Artikels</x-dropdown-link>
                    </x-slot>
                </x-dropdown>
            @endif
        <x-dropdown>
            {{-- avatar --}}
            <x-slot name="trigger">
                <img class="rounded-full h-8 w-8 cursor-pointer"
                     src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->first_name) }}+{{ urlencode(auth()->user()->last_name) }}"
                     alt="Platte Berg">
            </x-slot>
            <x-slot name="content">
                <div class="block px-4 py-2 text-xs text-gray-400">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</div>
                {{--<x-dropdown-link href="{{ route('dashboard') }}">Dashboard</x-dropdown-link>--}}
                <x-dropdown-link href="{{ route('profile.show') }}">Profiel bewerken</x-dropdown-link>
                <div class="border-t border-gray-100"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition">Afmelden</button>
                </form>
            </x-slot>
        </x-dropdown>
        @else
            <a href="{{ route('login') }}" class="text-gray-800 hover:text-black text-sm font-medium hover:underline">Log in</a>
            <a href="{{ route('register') }}" class="text-gray-800 hover:text-black text-sm font-medium hover:underline">Schrijf in</a>
        @endauth
    </div>
    </nav>
</header>
