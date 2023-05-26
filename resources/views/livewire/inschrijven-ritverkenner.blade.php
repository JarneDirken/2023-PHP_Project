<div>
    <div class="fixed top-12 left-1/2 -translate-x-1/2 z-50 animate-pulse"
         wire:loading>
        <x-preloader class="bg-green-400/75 text-gray-800 border border-green-700 shadow-2xl">
            Laden...
        </x-preloader>
    </div>

    <x-help-modal>
        <x-slot name="title">Info ritverkenner worden</x-slot>
        <x-slot name="content">
            <section class="border-b">
                <p>Op deze pagina kan je je:</p>
                <ul class="ml-2 mt-2 list-disc">
                    <li class="mb-2">Inschrijven als ritverkenner voor een rit</li>
                    <li class="mb-2">Uitschrijven als ritverkenner van een rit</li>
                </ul>
            </section>
            <section class="mt-2 border-b">
                <p class="font-medium">Bovenaan krijg je een overzicht van alle ritten zonder ritverkenner</p>
                <p class="mt-2">Hierin kan je:</p>
                <ul class="mt-2 ml-2 list-disc [&>li]:mb-2">
                    <li>Ritten filteren op naam, locatie, ploegen en ritten tonen tussen een start- en einddatum</li>
                    <li>Ritten sorteren op naam, locatie, ploegen, datum en locatie</li>
                    <li>Inschrijven als ritverkenner voor een rit door op
                        <button
                            class="hover:bg-gray-300 transition border border-gray-300 py-2 px-4 rounded">
                            Ritverkenner worden
                        </button>
                        te klikken
                    </li>
                </ul>
            </section>
            <section class="mt-2">
                <p class="font-medium">Als je een ritverkenner bent voor minstens 1 rit krijg je een onderaan een overzicht van al jouw ritten</p>
                <p class="mt-2">Hierin kan je:</p>
                <ul class="mt-2 ml-2 list-disc [&>li]:mb-2">
                    <li>Je uitschrijven als ritverkenner voor een rit door op
                        <button
                            class="hover:bg-gray-300 transition border border-gray-300 py-2 px-4 rounded">
                            Uitschrijven
                        </button>
                        te klikken
                    </li>
                </ul>
            </section>
        </x-slot>
    </x-help-modal>

    <x-section>
        <h2 class="text-xl font-medium mb-4">Ritten zonder ritverkenner</h2>

        <div class="flex flex-wrap items-center gap-3 bg-gray-100 px-2 py-4 border border-gray-300 rounded">
            <div class="flex flex-wrap gap-4 flex-grow">
                {{-- filter op naam --}}
                <x-input id="search" type="text" placeholder="Filter op naam"
                         wire:model.debounce.500ms="search"
                         class="shadow-md placeholder-gray-400 px-2 border border-black basis-60"/>
                {{-- filter op locatie --}}
                <x-input id="location" type="text" placeholder="Filter op locatie"
                         wire:model.debounce.500ms="location"
                         class="shadow-md placeholder-gray-400 px-2 border border-black basis-60"/>
                {{-- filter op team --}}
                <x-select id="teamSearch" wire:model="teamSearch" class="border border-black rounded-md p-1 w-32 px-2 basis-40">
                    <option value="0">Alle ploegen</option>
                    @foreach($teams as $team)
                        <option value="{{ $team->id }}">{{ $team->name }}</option>
                    @endforeach
                </x-select>
                <div class="relative">
                    <x-label for="start_date" class="text-gray-300 absolute -top-4">Vanaf</x-label>
                    <x-input id="start_date" type="date"
                             class="block mt-1 w-40"
                             wire:model="begin_date"/>
                </div>
                <div class="relative">
                    <x-label for="end_date" class="absolute -top-4">Tot en met</x-label>
                    <x-input id="end_date" type="date"
                             class="block mt-1 w-40"
                             wire:model="end_date"/>
                </div>
            </div>
            @if($search || $location || $teamSearch != 0 || $begin_date || $end_date)
                <div class="bg-white w-10 h-10 border-gray-300 border p-2 rounded-full flex items-center justify-center cursor-pointer hover:bg-gray-100"
                     wire:click="resetFilter()"
                     data-tippy-content="Filters verwijderen">
                    <x-phosphor-x class="w-5 h-5"></x-phosphor-x>
                </div>
            @endif
        </div>
        {{--paginate--}}
        <div class="my-4">{{ $tours->links() }}</div>
        @if(sizeof($tours) == 0)
            <x-alert dismissable="false" type="warning">
                @if($search || $location)
                    Geen resultaten voor
                    @if($search)naam: <b>'{{ $search }}'</b>@endif
                    @if($search && $location) en @endif
                    @if($location)locatie: <b>'{{ $location }}'</b>@endif
                @else
                    Geen ritten gevonden
                @endif
            </x-alert>
        @else
        <div class="overflow-x-auto">
            <table class="w-full border border-gray-300">
                <colgroup>
                    <col class="w-3/12">
                    <col class="w-1/12">
                    <col class="w-1/12">
                    <col class="w-1/12">
                    <col class="w-1/12">
                    <col class="w-3/12">
                    <col class="w-2/12">
                </colgroup>
                <thead>
                <tr class="bg-gray-100 text-gray-700 [&>th]:p-2 [&>th]:text-start">
                    <th class="cursor-pointer" wire:click="resort('name')">
                        <span data-tippy-content="Sorteer op naam" data-tippy-placement="bottom">Naam</span>
                        <x-phosphor-caret-down class="h-3 w-3 inline-block
                        {{$orderAsc ?: 'rotate-180'}}
                        {{$orderBy === 'name' ? 'inline-block' : 'hidden'}}
                    "/>
                    </th>
                    <th class="cursor-pointer" wire:click="resort('team_id')">
                        <span data-tippy-content="Sorteer op ploeg" data-tippy-placement="bottom">Ploeg</span>
                        <x-phosphor-caret-down class="h-3 w-3 inline-block
                        {{$orderAsc ?: 'rotate-180'}}
                        {{$orderBy === 'team_id' ? 'inline-block' : 'hidden'}}
                    "/>
                    </th>
                    <th class="cursor-pointer" wire:click="resort('distance')">
                        <span data-tippy-content="Sorteer op afstand" data-tippy-placement="bottom">Afstand</span>
                        <x-phosphor-caret-down class="h-3 w-3 inline-block
                        {{$orderAsc ?: 'rotate-180'}}
                        {{$orderBy === 'distance' ? 'inline-block' : 'hidden'}}
                    "/>
                    </th>
                    <th class="cursor-pointer" wire:click="resort('date')">
                        <span data-tippy-content="Sorteer op datum" data-tippy-placement="bottom">Datum</span>
                        <x-phosphor-caret-down class="h-3 w-3 inline-block
                        {{$orderAsc ?: 'rotate-180'}}
                        {{$orderBy === 'date' ? 'inline-block' : 'hidden'}}
                    "/>
                    </th>
                    <th>
                        <span>Tijdstip</span>
                    </th>
                    <th class="cursor-pointer" wire:click="resort('date')">
                        <span data-tippy-content="Sorteer op locatie" data-tippy-placement="bottom">Locatie</span>
                        <x-phosphor-caret-down class="h-3 w-3 inline-block
                        {{$orderAsc ?: 'rotate-180'}}
                        {{$orderBy === 'location' ? 'inline-block' : 'hidden'}}
                    "/>
                    </th>
                    <th>
                        {{-- kolom voor inschrijf knop --}}
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach($tours as $tour)
                    <tr class="border-t border-gray-300 [&>td]:p-2" wire:key="tour_{{ $tour->id }}">
                        <td>{{ $tour->name }}</td>
                        <td>{{ $tour->team->name }}</td>
                        <td>{{ $tour->distance }} km</td>
                        <td>{{ $tour->date }}</td>
                        <td>{{ \Carbon\Carbon::createFromTimeString($tour->departure_time)->format('h:m') }}</td>
                        <td>{{ $tour->location }}</td>
                        <td>
                            <button
                                wire:click="showInfo({{ $tour->id }})"
                                class="hover:bg-gray-300 transition border border-gray-300 py-2 px-4 rounded">
                                Ritverkenner worden
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </x-section>


    @if(sizeof($toursTourGuide) > 0)
        <x-section class="mt-8">
            <h2 class="text-xl font-medium mb-4">Jouw ritten</h2>
            <div class="overflow-x-auto">
                <table class="w-full border border-gray-300">
                    <colgroup>
                        <col class="w-3/12">
                        <col class="w-1/12">
                        <col class="w-1/12">
                        <col class="w-1/12">
                        <col class="w-1/12">
                        <col class="w-3/12">
                        <col class="w-2/12">
                    </colgroup>
                    <thead>
                    <tr class="bg-gray-100 text-gray-700 [&>th]:p-2 [&>th]:text-start">
                        <th>
                            <span>Naam</span>
                        </th>
                        <th>
                            <span>Ploeg</span>
                        </th>
                        <th>
                            <span>Afstand</span>
                        </th>
                        <th>
                            <span>Datum</span>
                        </th>
                        <th>
                            <span>Tijdstip</span>
                        </th>
                        <th>
                            <span>Locatie</span>
                        </th>
                        <th>
                            {{-- kolom voor uitschrijf knop --}}
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($toursTourGuide as $tour)
                        <tr class="border-t border-gray-300 [&>td]:p-2" wire:key="tour_{{ $tour->id }}">
                            <td>{{ $tour->name }}</td>
                            <td>{{ $tour->team->name }}</td>
                            <td>{{ $tour->distance }} km</td>
                            <td>{{ $tour->date }}</td>
                            <td>{{ \Carbon\Carbon::createFromTimeString($tour->departure_time)->format('h:m') }}</td>
                            <td>{{ $tour->location }}</td>
                            <td>
                                <button
                                    wire:click="removeTourGuide({{ $tour->id }})"
                                    class="hover:bg-gray-300 transition border border-gray-300 py-2 px-4 rounded">
                                    Uitschrijven
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </x-section>
    @endif

    <x-dialog-modal id="tourModal"
                    wire:model="showModal">
        <x-slot name="title" class="text-lg">
            Ritverkenner worden
        </x-slot>
        <x-slot name="content">
            @if($selectedTour)
                <div class="grid grid-cols-2 gap-4">
                    <div class="inline-block">
                        <p class="text-md font-bold">Naam</p>
                        <p class="text-md">{{ $selectedTour->name }}</p>
                    </div>
                    <div class="inline-block">
                        <p class="text-md font-bold">Beschrijving</p>
                        <p class="text-md">{{ $selectedTour->description }}</p>
                    </div>
                    <div class="inline-block">
                        <p class="text-md font-bold">Locatie</p>
                        <p class="text-md">{{ $selectedTour->location }}</p>
                    </div>
                    <div class="inline-block">
                        <p class="text-md font-bold">Afstand</p>
                        <p class="text-md">{{ $selectedTour->distance }} km</p>
                    </div>
                    <div class="inline-block">
                        <p class="text-md font-bold">Datum</p>
                        <p class="text-md">{{ $selectedTour->date }}</p>
                    </div>
                    <div class="inline-block">
                        <p class="text-md font-bold">Tijdstip vertrek</p>
                        <p class="text-md">{{ \Carbon\Carbon::createFromTimeString($selectedTour->departure_time)->format('h:m') }}</p>
                    </div>

                </div>
            @else
                <p>Er is iets misgelopen</p>
            @endif
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button @click="show = false">Annuleer</x-secondary-button>
            <x-button
                color="success"
                wire:click="becomeTourGuide()"
                wire:loading.attr="disabled"
                class="ml-2">Ritverkenner worden
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
