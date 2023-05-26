<div>
    <x-slot name="title"></x-slot>
    <style>
        body{
            overflow-x: hidden;
        }
        main {
            padding: 0!important;
            margin: 0!important;

        }
        h1{
            display:none!important;
        }
        main > div {
            width: 100vw;
        }

    </style>

        <div class="relative h-full md:flex md:items-center md:justify-center">
            <img class="object-cover w-full h-full brightness-50" src="/assets/Bike.jpg" alt="Fiets foto">
            <div class="absolute inset-0 flex items-center justify-center">
                <div>
                    <h2 class="text-center text-gray-50 font-bold text-1xl sm:text-3xl md:text-5xl lg:text-7xl">Welkom bij de platte berg!</h2><br>
                    <p class="text-center text-gray-50 font-medium text-base md:text-2xl">U kan hier inloggen of kijken wanneer onze volgende ritten zijn!</p>
                    @auth()
                        @if(auth()->user())
                        @endif
                    @else
                        <div class="flex flex-col md:flex-row justify-center items-center gap-10 mt-8">
                            <a href="{{ route('login') }}"><x-secondary-button class="text-gray-50 border-2 border-gray-50 hover:bg-gray-50 hover:text-slate-900 bg-transparent">Log in</x-secondary-button></a>
                            <a href="{{ route('payment') }}"><x-secondary-button class="text-gray-50 border-2 border-gray-50 hover:bg-gray-50 hover:text-slate-900 bg-transparent">Schrijf in</x-secondary-button></a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>


    <x-section class="my-20 py-10 md:mx-40 lg:mx-60 place-self-center" id="ritten">
        <div class="flex justify-center items-center flex-col">
            <x-authentication-card-logo></x-authentication-card-logo>
            <h1 class="text-4xl">Opkomende ritten</h1>
            <br>
            @auth()
                @if(auth()->user())
                    <div class="flex gap-6 m-6">
                        <a href="{{ route('inschrijven-ritverkenner') }}"><x-button color="success">Ritverkenner worden</x-button></a>
                    </div>
                @endif
            @else
            @endauth
            <div class="flex justify-center items-center text-2xl font-bold">Opkomende ritten: (binnen de komende 30 dagen)</div>
            <br>
            @if($tours->isEmpty())
                <div class="flex justify-center items-center text-2xl bold">Er zijn geen opkomende ritten.</div>
            @else
            @foreach($tours as $tour)



                    <div class="grid grid-cols-2 bg-gray-200 rounded-md shadow-lg w-2/3 p-6 gap-4 text-center">
                        <div class="col-span-2">
                            <h3 class="font-bold text-2xl text-center">{{ $tour->name }}</h3>
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <h4 class="font-bold text-xl text-center">Start tijd:</h4>
                            {{ date('H:i', strtotime($tour->departure_time)) }}

                            {{ $tour->date }}
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <h4 class="font-bold text-xl text-center">Plaats & ritlengte</h4>
                            <div class="flex items-center justify-center">
                                <x-phosphor-map-pin-duotone class="w-5"></x-phosphor-map-pin-duotone>{{ $tour->location }}
                            </div>
                            <div class="flex items-center justify-center">
                                <x-phosphor-arrow-bend-down-right-duotone class="w-5"></x-phosphor-arrow-bend-down-right-duotone>{{ $tour->distance }} km
                            </div>

                        </div>

                        <div class="col-span-2 bg-gray-50 rounded-lg shadow-md p-6">
                            <h4 class="font-bold text-xl text-center">Beschrijving: </h4>
                            {{ $tour->description }}</div>
                    </div>


            @endforeach
            @endif


        </div>
    </x-section>
    <x-section class="mt-6" id="over-ons">
        <h1 class="text-4xl text-center">Over ons</h1><br>
        <div class="flex justify-center items-center flex-col md:flex-row">
            <img class="w-full md:w-1/2 object-cover rounded-t md:rounded-l" src="/assets/HP_image.jpg" alt="foto van fietsen in de bergen">
            <div class="md:w-1/2 p-8">
                <h4 class="font-bold text-2xl">Over onze groep!</h4>
                <p class="text-center md:text-left">De Platte Berg is een groep voor mensen die graag fietsen. Of je nu jong of oud bent, beginner of gevorderde, iedereen is welkom bij deze groep. De groep bestaat uit fietsers van alle niveaus, van recreatieve fietsers tot fanatieke wielrenners.</p>
                <p class="text-center md:text-left mt-4">De naam van de groep is afgeleid van het landschap waarin ze graag fietsen: de Vlaamse Ardennen. Het gebied staat bekend om zijn heuvelachtige terrein en uitdagende beklimmingen, maar ook om zijn prachtige landschap en rustige wegen. De groep heeft als doel om samen te genieten van het fietsen en het ontdekken van de mooie omgeving.</p>
                <p class="text-center md:text-left mt-4">Het maakt niet uit of je een racefiets, mountainbike of gewone fiets hebt. De groep organiseert regelmatig ritten van verschillende lengtes en moeilijkheidsgraden, zodat iedereen kan deelnemen aan een rit die bij hem of haar past. De ritten vinden plaats op verschillende dagen en tijdstippen, zodat er altijd wel een geschikt moment is om mee te fietsen.</p>
            </div>
        </div>
    </x-section>
</div>
