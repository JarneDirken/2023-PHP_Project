<div>
    <div class="fixed top-12 left-1/2 -translate-x-1/2 z-50 animate-pulse"
         wire:loading>
        <x-preloader class="bg-green-400/75 text-gray-800 border border-green-700 shadow-2xl">
            Laden...
        </x-preloader>
    </div>

    <x-help-modal>
        <x-slot name="title">Info ritten plannen</x-slot>
        <x-slot name="content">
            <section class="border-b">
                <p class="font-medium">Op deze pagina kan je:</p>
                <ul class="ml-2 mt-2 list-disc">
                    <li class="mb-2">Ritten aanmaken</li>
                    <li class="mb-2">Ritten bewerken</li>
                    <li class="mb-2">Ritten verwijderen</li>
                </ul>
            </section>
            <section class="mt-2 border-b">
                <p class="font-medium">Bovenaan kan je filteren of een nieuwe rit aanmaken</p>
                <p class="mt-2">Je kan filteren op status, naam, locatie, seizoen en ploeg</p>
                <p class="mt-2">Je kan ook een nieuwe rit aanmaken door op
                    <x-button class="m-2">
                        Nieuwe rit plannen
                    </x-button> te klikken
                </p>
            </section>
            <section class="mt-2">
                <p class="font-medium">In de tabel zie je alle ritten</p>
                <p class="mt-2">Per rit wordt weergegeven:</p>
                <ul class="mt-2 ml-2 list-disc [&>li]:mb-1">
                    <li>Naam</li>
                    <li>Ploeg</li>
                    <li>Eventuele ritverkenner</li>
                    <li>Afstand</li>
                    <li>Datum en vertrekuur</li>
                    <li>Locatie</li>
                </ul>
                <p class="mt-2">Je kan sorteren op:</p>
                <ul class="mt-2 ml-2 list-disc [&>li]:mb-1">
                    <li>Naam</li>
                    <li>Datum</li>
                    <li>Locatie</li>
                </ul>
            </section>
            <section class="mb-2 mt-2">
                <p class="font-medium mb-2">Legende symbolen tabel</p>
                <table class="border-2 border-black w-full">
                    <thead class="bg-gray-200">
                    <tr>
                        <td>Symbool</td>
                        <td>Uitleg</td>
                    </tr>
                    </thead>
                    <tbody class="[&>tr]:border-t [&>tr]:border-gray-500 [& td]:p-2 [& td]:ml-1">
                    <tr>
                        <td class="flex justify-center items-center">
                            <x-phosphor-lock-open class="w-5 h-5 bg-green-100 rounded-full p-0.5"
                                                  data-tippy-content="Open"></x-phosphor-lock-open>
                        </td>
                        <td>
                            De rit is open (de rit is nog niet gereden/de aanwezigheden zijn nog niet opgenomen)
                        </td>
                    </tr>
                    <tr>
                        <td class="flex justify-center items-center">
                            <x-phosphor-lock class="w-5 h-5 bg-red-100 rounded-full p-0.5"
                                             data-tippy-content="Gesloten"></x-phosphor-lock>
                        </td>
                        <td>
                            De rit is gesloten (de rit is gereden en aanwezigheden zijn opgenomen)
                        </td>
                    </tr>
                    <tr>
                        <td class="flex justify-center items-center">
                            <x-phosphor-pencil-line-duotone
                                class="w-5 text-gray-300 hover:text-green-600"
                                data-tippy-content="Bewerk rit"/>
                        </td>
                        <td>Bewerk een rit</td>
                    </tr>
                    <tr>
                        <td class="flex justify-center items-center">
                            <x-phosphor-trash-duotone
                                data-tippy-content="Verwijder rit"
                                class="w-5 text-gray-300 hover:text-red-600"/>
                        </td>
                        <td>Verwijder een rit</td>
                    </tr>
                    </tbody>
                </table>
            </section>
            <section class="border-t">
                <p class="mt-5 text-blue-900">Een ritverkenner kan aangewezen worden voor een rit in het bewerkmenu</p>
                <p class="mt-3 text-blue-900">Een gesloten rit draagt toe aan de statistieken pagina, dus het is best deze niet te verwijderen</p>
            </section>
        </x-slot>
    </x-help-modal>

    <x-section class="flex flex-wrap gap-3 mb-3 justify-between">
        <div class="flex gap-3 flex-wrap flex-grow">
            <x-button wire:click="setNewTour()">
                Nieuwe rit plannen
            </x-button>
            {{-- openfilter --}}
            <div class="relative col-span-2">
                <x-label for="open" class="absolute -top-2 text-xs">Status</x-label>
                <x-select id="open" wire:model="open" class="border border-black mt-2 rounded-md p-1 w-32">
                    <option value="All" selected>Alle</option>
                    <option value="Open">Open</option>
                    <option value="Closed">Gesloten</option>
                </x-select>
            </div>
            {{-- naamfilter --}}
            <x-input id="name" type="text" placeholder="Zoek op naam"
                     wire:model.debounce.500ms="name"
                     class="placeholder-gray-300 px-2 border border-black basis-1/5"/>
            {{-- locatiefilter --}}
            <x-input id="location" type="text" placeholder="Zoek op locatie"
                     wire:model.debounce.500ms="location"
                     class="placeholder-gray-300 px-2 border border-black basis-1/5"/>
            {{-- seizoenfilter --}}
            <x-select id="seasonSearch" wire:model="selectedSeasonId" class="border border-black rounded-md w-60 px-2">
                <option value="0">Alle seizoenen</option>
                @foreach($seasons as $season)
                    <option value="{{ $season->id }}">
                        {{ $season->start_date }} - {{ $season->end_date }}
                    </option>
                @endforeach
            </x-select>
            {{-- teamfilter --}}
            <x-select id="teamSearch" wire:model="selectedTeamId" class="border border-black rounded-md w-40 px-2">
                <option value="0">Alle ploegen</option>
                @foreach($teams as $team)
                    <option value="{{ $team->id }}">
                        {{ $team->name }}
                    </option>
                @endforeach
            </x-select>
        </div>
        @if($name || $selectedSeasonId || $selectedTeamId || $location || $open != "All")
            <div class="w-10 h-10 border-gray-300 border p-2 rounded-full flex items-center justify-center cursor-pointer hover:bg-gray-100"
                 wire:click="resetFilter()"
                 data-tippy-content="Filters verwijderen">
                <x-phosphor-x class="w-5 h-5"></x-phosphor-x>
            </div>
        @endif
    </x-section>
    @if(sizeof($tours) > 0)
        <x-section class="overflow-x-auto">
            <table class="w-full border border-gray-300 bg-white">
                <colgroup>
                    <col class="w-4">
                    <col class="w-3/12">
                    <col class="w-1/12">
                    <col class="w-3/12">
                    <col class="w-1/12">
                    <col class="w-1/12">
                    <col class="w-1/12">
                    <col class="w-2/12">
                </colgroup>
                <thead>
                <tr class="bg-gray-100 text-gray-700 [&>th]:p-2 [&>th]:text-start">
                    <th> {{-- icoontje status rit --}}</th>

                    <th wire:click="resort('name')" class="cursor-pointer">
                        <span data-tippy-content="Sorteer op naam">Naam</span>
                        <x-phosphor-caret-down class="h-3 w-3 inline-block
                            {{$orderAsc ?: 'rotate-180'}}
                            {{$orderBy === 'name' ? 'inline-block' : 'hidden'}}
                        "/>
                    </th>
                    <th wire:click="resort('team_id')" class="cursor-pointer">
                    <span data-tippy-content="Sorteer op ploeg">
                        Ploeg
                    </span>
                        <x-phosphor-caret-down class="h-3 w-3 inline-block
                            {{$orderAsc ?: 'rotate-180'}}
                            {{$orderBy === 'team_id' ? 'inline-block' : 'hidden'}}
                        "/>
                    </th>

                    <th>
                        <span>Ritverkenner</span>

                    </th>
                    <th>

                        <span>Afstand</span>

                    </th>
                    <th wire:click="resort('date')" class="cursor-pointer">

                        <span data-tippy-content="Sorteer op datum">Datum</span>
                        <x-phosphor-caret-down class="h-3 w-3 inline-block
                            {{$orderAsc ?: 'rotate-180'}}
                            {{$orderBy === 'date' ? 'inline-block' : 'hidden'}}
                        "/>
                    </th>

                    <th>
                        <span>Vertrekuur</span>

                    </th>

                    <th>
                        <span>Locatie</span>
                    </th>

                    <th>
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach($tours as $tour)
                    <tr class="border-t border-gray-300 [&>td]:p-2"
                        wire:key="tour_{{ $tour->id }}">
                        <td>
                            @if($tour->open)
                                <x-phosphor-lock-open class="w-5 h-5 bg-green-100 rounded-full p-0.5"
                                                      data-tippy-content="Open"></x-phosphor-lock-open>
                            @else
                                <x-phosphor-lock class="w-5 h-5 bg-red-100 rounded-full p-0.5"
                                                 data-tippy-content="Gesloten"></x-phosphor-lock>
                            @endif
                        </td>
                        <td>{{ $tour->name }}</td>
                        <td>{{ $tour->team->name }}</td>
                        @isset( $tour->user->first_name )
                            <td>{{ $tour->user->first_name }} {{ $tour->user->last_name }}</td>
                        @else
                            <td>-</td>
                        @endisset
                        <td>{{ $tour->distance }} km</td>
                        <td>{{ $tour->date }}</td>
                        <td>{{ \Carbon\Carbon::createFromTimeString($tour->departure_time)->format('h:m') }}</td>
                        <td>{{ $tour->location }}</td>
                        <td x-data=""><div class="flex gap-1 justify-center [&>*]:cursor-pointer [&>*]:outline-0 [&>*]:transition">
                                <x-phosphor-pencil-line-duotone
                                    wire:click="setNewTour({{ $tour->id }})"
                                    class="w-5 text-gray-300 hover:text-green-600"
                                    data-tippy-content="Bewerk rit"/>
                                <x-phosphor-trash-duotone
                                    data-tippy-content="Verwijder rit"
                                    @click="$dispatch('swal:confirm', {
                                    title: 'Verwijder rit?',
                                    icon: 'warning',
                                    background: 'warning',
                                    cancelButtonText: 'Nee',
                                    html: '{{ $tour->open ?
                                                '<b>OPGELET</b>: ' . $tour->name . ' staat open, gebruikers hebben deze rit misschien al ingepland'
                                                 : '<b>OPGELET</b>: ' . $tour->name . ' wordt gebruikt als data in de statistieken pagina'}}',
                                    color: 'orange',
                                    cancelButtonText: 'Annuleer',
                                    confirmButtonText: 'Verwijder de rit',
                                    next: {
                                        event: 'delete-tour',
                                        params: {
                                            id: {{ $tour->id }}
                                        }
                                    }
                                });"
                                    class="w-5 text-gray-300 hover:text-red-600"/>
                            </div></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="my-4">{{ $tours->links() }}</div>
        </x-section>
    @else
        <x-alert dismissable="false" type="warning">
            Geen ritten gevonden</b>
        </x-alert>
    @endif

    <x-dialog-modal  id="tourModal"
                     wire:model="showModal">
        <x-slot name="title" class="text-center">
            <h2>{{ is_null($newTour['id']) ? 'Nieuwe rit' : 'Rit bijwerken' }}</h2>
        </x-slot>
        <x-slot name="content">
            @if ($errors->any())
                <x-alert type="danger">
                    <x-list>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </x-list>
                </x-alert>
            @endif
            <div class="flex relative">
                <x-label for="name" value="Naam" class="mt-4"></x-label>
                <span class="text-red-600 absolute top-2">*&nbsp</span>
            </div>
            <x-input id="name" type="text" placeholder="Naam" wire:model.defer="newTour.name" class="w-full"></x-input>

            <div class="flex relative">
                <x-label for="location" value="Locatie" class="mt-4"></x-label>
                <span class="text-red-600 absolute top-2">*&nbsp</span>
            </div>
            <x-input id="location" type="text" placeholder="Locatie" wire:model.defer="newTour.location" class="w-full"></x-input>

            <div class="flex relative">
                <x-label for="team" value="Ploeg" class="mt-4"></x-label>
                <span class="text-red-600 absolute top-2">*&nbsp</span>
            </div>
            <x-select id="team"
                      wire:model.defer="newTour.team_id"
                      class="w-full shadow-md">
                <option value="">Kies een ploeg</option>
                @foreach($teams as $team)
                    <option value="{{$team->id}}">{{$team->name}}</option>
                @endforeach
            </x-select>


            <x-label for="user_id" value="Ritverkenner" class="mt-4"></x-label>
            <x-select id="user_id"
                      wire:model.defer="newTour.user_id"
                      class="w-full shadow-md">
                <option value="">Kies een ritverkenner</option>
                @foreach($users as $user)
                    <option value="{{$user->id}}">{{$user->first_name}} {{$user->last_name}}</option>
                @endforeach
            </x-select>

            <div class="flex gap-5">
                <div>
                    <div class="flex relative">
                        <x-label for="date" value="Datum" class="mt-4"></x-label>
                        <span class="text-red-600 absolute top-2">*&nbsp</span>
                    </div>
                    <x-input type="date" id="date" name="StartDatum" lang="" wire:model.defer="newTour.date"
                             min="2023-01-01" max="2026-12-31" ></x-input>
                </div>
                <div>
                    <div class="flex relative">
                        <x-label for="depart" value="Vertrekuur" class="mt-4"></x-label>
                        <span class="text-red-600 absolute top-2">*&nbsp</span>
                    </div>
                    <x-input id="depart" type="time" name="time" wire:model.defer="newTour.departure_time"></x-input>
                </div>
            </div>

            <div class="flex relative">
                <x-label for="distance" value="Afstand" class="mt-5 clear-both"></x-label>
                <span class="text-red-600 absolute top-2">*&nbsp</span>
            </div>
            <x-input id="distance" placeholder="Afstand" type="number" class="w-full" wire:model.defer="newTour.distance"></x-input>

            <x-label for="description" value="Beschrijving" class="mt-4"></x-label>
            <x-input id="description" placeholder="Beschrijving" type="text" class="w-full" wire:model.defer="newTour.description"></x-input>

        </x-slot>
        <x-slot name="footer">
            <x-secondary-button @click="show = false">Annuleer</x-secondary-button>
            @if(is_null($newTour['id']))
                <x-button
                    wire:click="createTour()"
                    wire:loading.attr="disabled"
                    class="ml-2">Maak nieuwe rit
                </x-button>
            @else
                <x-button
                    color="success"
                    wire:click="updateTour({{ $newTour['id'] }})"
                    wire:loading.attr="disabled"
                    class="ml-2">Werk tour bij
                </x-button>
            @endif
        </x-slot>
    </x-dialog-modal>
</div>
