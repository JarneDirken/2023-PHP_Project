<x-project-p-h-p-layout>
    <x-slot name="title"></x-slot>
        <x-section>
    <div class="relative h-full">
        <img class="object-cover w-full h-full brightness-50" src="/assets/Bike.jpg" alt="Fiets foto">
        <div class="absolute inset-0 flex items-center justify-center">
            <div>
            <h1 class="text-center text-gray-50 bold text-5xl">Welkom bij de platte berg!</h1><br>
            <p class="text-center text-gray-50 bold text-2xl">U kan hier inloggen of kijken wanneer onze volgende ritten zijn!</p>
                @auth()
                    @if(auth()->user())
                    @endif
                    @else
                <div class="flex justify-center items-center gap-10 mt-8">
                    <a href="{{ route('login') }}"><x-secondary-button class="bg-transparent text-slate-900 border-2 border-gray-50 hover:bg-gray-50 hover:text-slate-900">Inloggen</x-secondary-button></a>
                    <a href="{{ route('register') }}"><x-secondary-button class="bg-transparent text-slate-900 border-2 border-gray-50 hover:bg-gray-50 hover:text-slate-900">Registreren</x-secondary-button></a>
                </div>
                    @endauth
            </div>
        </div>
    </div>
        </x-section>
        <x-section class="mt-8 py-10">
            <div class="flex justify-center items-center flex-col">
                <x-authentication-card-logo></x-authentication-card-logo>
                <h1 class="text-4xl">Opkomende ritten</h1>
                @auth()
                    @if(auth()->user())
                        <div class="flex gap-6 m-6">
                        <a href="{{ route('inschrijven-evenement') }}"><x-button>Inschrijven voor een rit</x-button></a>
                        <a href="{{ route('inschrijven-ritverkenner') }}"><x-button>Ritverkenner worden</x-button></a>
                        </div>
                    @endif
                    @else
                @endauth
                <table class="table-auto w-1/2 text-left p-6">
                    <tr>
                        <th>Naam</th>
                        <th>Datum</th>
                        <th>Plaats</th>
                    </tr>
                    <tr>
                        <td>Rit door de bossen</td>
                        <td>11/08/2023</td>
                        <td>Charleroi</td>
                    </tr>
                    <tr>
                        <td>Rit door de bergen</td>
                        <td>27/11/2023</td>
                        <td>Gent</td>
                    </tr>
                    <tr>
                        <td>Rit door de straten</td>
                        <td>01/12/2023</td>
                        <td>Antwerpen</td>
                    </tr>
                </table>
            </div>
        </x-section>
    <x-section class="mt-6">
        <h1 class="text-4xl text-center">Over ons</h1>
        <div class="flex justify-center items-center">
        <div class="grid grid-cols-2 w-2/3 p-8">

            <p class="col-span-1">De Platte Berg is een groep voor mensen die graag fietsen. Of je nu jong of oud bent, beginner of gevorderde, iedereen is welkom bij deze groep. De groep bestaat uit fietsers van alle niveaus, van recreatieve fietsers tot fanatieke wielrenners.<br><br>

                De naam van de groep is afgeleid van het landschap waarin ze graag fietsen: de Vlaamse Ardennen. Het gebied staat bekend om zijn heuvelachtige terrein en uitdagende beklimmingen, maar ook om zijn prachtige landschap en rustige wegen. De groep heeft als doel om samen te genieten van het fietsen en het ontdekken van de mooie omgeving.<br><br>

                Het maakt niet uit of je een racefiets, mountainbike of gewone fiets hebt. De groep organiseert regelmatig ritten van verschillende lengtes en moeilijkheidsgraden, zodat iedereen kan deelnemen aan een rit die bij hem of haar past. De ritten vinden plaats op verschillende dagen en tijdstippen, zodat er altijd wel een geschikt moment is om mee te fietsen.<br><br></p>
            <img class="col-span-1 object-cover w-full rounded-l" src="/assets/HP_image.jpg" alt="foto van fietsen in de bergen">
        </div>
        </div>
    </x-section>
</x-project-p-h-p-layout>




