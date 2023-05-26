<nav class="flex justify-between items-center">
    <div class="flex gap-5">
        <a href="{{ url('/') }}" class="mr-4">
            <img class="h-20 w-20" src="/icon.png" alt="home">
        </a>
        <!-- Hamburger icon for smaller screens (below 992px) -->
        <div class="flex items-center">
            <x-dropdown class="cursor-pointer mt-0.5" align="left">
                <!-- Hamburger button -->
                <x-slot name="trigger">
                    <button id="hamburgerBtn" type="button" class="text-gray-800 hover:text-black focus:outline-none lg:hidden">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 12h18M3 6h18M3 18h18"></path>
                        </svg>
                    </button>
                </x-slot>
                <!-- Dropdown content (links) for screens below 992px -->
                <x-slot name="content">
                    <div class="flex flex-col justify-between items-center gap-5">
                        <!-- Links for screens below 992px -->
                        @auth()
                            <!-- Authenticated user links -->
                            <x-nav-link href="{{ url('/') }}" class="text-gray-800 hover:text-black">Home</x-nav-link>
                            <x-nav-link href="{{ route('calendar') }}" class="text-gray-800 hover:text-black">Kalender bekijken</x-nav-link>
                            <x-nav-link href="{{ route('inschrijven-ritverkenner') }}" class="text-gray-800 hover:text-black">Inschrijven ritverkenner</x-nav-link>
                            <x-nav-link href="{{ route('inschrijven-evenement') }}" class="text-gray-800 hover:text-black">Inschrijven evenementen</x-nav-link>
                            <x-nav-link href="{{ route('kledij-bestellen') }}" class="text-gray-800 hover:text-black">Webshop</x-nav-link>
                            <x-nav-link href="{{ route('bestellingen') }}" class="text-gray-800 hover:text-black">Bestellingen</x-nav-link>
                            <x-nav-link href="{{ route('faq-page') }}" class="text-gray-800 hover:text-black">FAQ</x-nav-link>
                            @if(auth()->user()->is_tour_guide)
                                <x-nav-link href="{{ route('opnemen-aanwezigheden') }}" class="text-gray-800 hover:text-black text-md">Aanwezigheid opnemen</x-nav-link>
                            @endif
                        @else
                            <!-- Guest user links -->
                            <x-nav-link href="{{ url('/') }}" class="text-gray-800 hover:text-black">Home</x-nav-link>
                            <x-nav-link href="{{ route('home') }}#ritten" class="text-gray-800 hover:text-black">Ritten</x-nav-link>
                            <x-nav-link href="{{ route('home') }}#over-ons" class="text-gray-800 hover:text-black">Over ons</x-nav-link>
                            <x-nav-link href="{{ route('faq-page') }}" class="text-gray-800 hover:text-black">FAQ</x-nav-link>
                        @endauth
                    </div>
                </x-slot>
            </x-dropdown>
        </div>
        <div class="hidden lg:flex md:gap-5">
            @auth()
                <x-nav-link href="{{ url('/') }}" class="text-gray-800 hover:text-black">Home</x-nav-link>
                <x-nav-link href="{{ route('calendar') }}" class="text-gray-800 hover:text-black">Kalender bekijken</x-nav-link>
                <x-nav-link href="{{ route('inschrijven-ritverkenner') }}" class="text-gray-800 hover:text-black">Inschrijven ritverkenner</x-nav-link>
                <x-nav-link href="{{ route('inschrijven-evenement') }}" class="text-gray-800 hover:text-black">Inschrijven evenementen</x-nav-link>
                <x-nav-link href="{{ route('kledij-bestellen') }}" class="text-gray-800 hover:text-black">Webshop</x-nav-link>
                <x-nav-link href="{{ route('bestellingen') }}" class="text-gray-800 hover:text-black">Bestellingen</x-nav-link>
                <x-nav-link href="{{ route('faq-page') }}" class="text-gray-800 hover:text-black">FAQ</x-nav-link>
                @if(auth()->user()->is_tour_guide)
                    <x-nav-link href="{{ route('opnemen-aanwezigheden') }}" class="text-gray-800 hover:text-black text-md">Aanwezigheid opnemen</x-nav-link>
                @endif
            @else
                <x-nav-link href="{{ url('/') }}" class="text-gray-800 hover:text-black">Home</x-nav-link>
                <x-nav-link href="{{ route('home') }}#ritten" class="text-gray-800 hover:text-black">Ritten</x-nav-link>
                <x-nav-link href="{{ route('home') }}#over-ons" class="text-gray-800 hover:text-black">Over ons</x-nav-link>
                <x-nav-link href="{{ route('faq-page') }}" class="text-gray-800 hover:text-black">FAQ</x-nav-link>
            @endauth
        </div>
    </div>

    <div class="flex gap-5 mr-5">
        @auth()
            <div class="-mt-2 -mr-2">
          <span  class="ml-2 -mb-0.5  text-xs text-white bg-rose-500 text-rose-100 rounded-full w-4 h-4 flex items-center justify-center">
                    {{session('count')}}
                </span>
                <a href="{{route('showcart')}}"><x-fas-shopping-basket class="w-4 h-4"/></a>

            </div>
            @if(auth()->user()->management)
                <x-dropdown class="cursor-pointer mt-0.5">
                    <x-slot name="trigger">
                        <p class="text-sm text-gray-800 hover:text-black inline-block font-medium">Admin</p>
                        <x-phosphor-caret-down class="h-3 w-3 inline-block"></x-phosphor-caret-down>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link href="{{ route('admin.tours') }}">Ritten plannen</x-dropdown-link>
                        <x-dropdown-link href="{{ route('admin.events') }}">Evenementen plannen</x-dropdown-link>
                        <x-dropdown-link href="{{ route('opnemen-aanwezigheden') }}">Aanwezigheid opnemen</x-dropdown-link>
                        <x-dropdown-link href="{{ route('admin.statistics') }}">Statistieken</x-dropdown-link>
                        <x-dropdown-link href="{{ route('admin.overzicht-bestellingen') }}">Overzicht bestellingen</x-dropdown-link>
                        <div class="block px-4 pt-2 pb-1 text-xs text-gray-500 border-t border-gray-300">Beheren</div>
                        <x-dropdown-link href="{{ route('admin.memberships') }}">Lidmaatschappen</x-dropdown-link>
                        <x-dropdown-link href="{{ route('admin.seasons') }}">Seizoenen</x-dropdown-link>
                        <x-dropdown-link href="{{ route('admin.teams') }}">Ploegen</x-dropdown-link>
                        <x-dropdown-link href="{{ route('admin.users') }}">Gebruikers</x-dropdown-link>
                        <x-dropdown-link href="{{ route('admin.points') }}">Punten</x-dropdown-link>
                        <x-dropdown-link href="{{ route('admin.coupons') }}">Kortingsbonnen</x-dropdown-link>
                        <x-dropdown-link href="{{ route('admin.garments') }}">Kledij</x-dropdown-link>
                        <x-dropdown-link href="{{ route('admin.articles') }}">Artikels</x-dropdown-link>
                        <x-dropdown-link href="{{ route('admin.sizes') }}">Maten</x-dropdown-link>
                        <x-dropdown-link href="{{ route('admin.mail-templates') }}">Mail templates</x-dropdown-link>
                        <x-dropdown-link href="{{ route('admin.genders') }}">Geslachten</x-dropdown-link>
                        <x-dropdown-link href="{{ route('admin.faqs') }}">Faq</x-dropdown-link>

                    </x-slot>
                </x-dropdown>
            @endif
            <x-dropdown>
                {{-- avatar --}}
                <x-slot name="trigger">
                    <img class="rounded-full h-8 w-8 cursor-pointer"
                         src="{{ $avatar }}"
                         alt="{{ auth()->user()->first_name }}">
                </x-slot>
                <x-slot name="content">
                    <div class="block px-4 py-2 text-xs text-gray-400">{{ (auth()->user()->first_name . " " . auth()->user()->last_name) }}</div>
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
            <a href="{{ route('payment') }}" class="text-gray-800 hover:text-black text-sm font-medium hover:underline">Schrijf in</a>
        @endauth
    </div>
</nav>
