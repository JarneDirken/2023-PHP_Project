<div>

    {{--filter--}}
    <x-section class="flex gap-5 my-4">
        {{-- filter op naam --}}
        <x-input id="search" type="text" placeholder="Filter op naam"
                 wire:model.debounce.500ms="search"
                 class="shadow-md placeholder-gray-300 px-2 border border-black"/>
        {{-- filter op locatie --}}
        <x-input id="location" type="text" placeholder="Filter op locatie"
                 wire:model.debounce.500ms="location"
                 class="shadow-md placeholder-gray-300 px-2 border border-black"/>
        {{-- filter op seizoen --}}
        <x-select id="seasonSearch" wire:model="selectedSeasonId" class="border border-black rounded-md p-1 w-60 px-2">
            <option value="0">Alle seizoenen</option>
            @foreach($seasons as $season)
                <option value="{{ $season->id }}">
                    {{ $season->start_date }} - {{ $season->end_date }}
                </option>
            @endforeach
        </x-select>
    </x-section>

    {{--paginate--}}
    <div class="my-4 justify-center mx-auto">{{ $events->links() }}</div>

    {{--main--}}
    @if($events->isEmpty())
        <x-alert dismissable="false" type="warning">
            @if($search || $location)
                Geen resultaten voor
                @if($search)naam: <b>'{{ $search }}'</b>@endif
                @if($search && $location) en @endif
                @if($location)locatie: <b>'{{ $location }}'</b>@endif
            @else
                Geen evenementen gevonden
            @endif
        </x-alert>
    @else
    <x-section>
        <table class="text-center w-full border border-gray-300">
            <thead>
            <tr class="bg-gray-100 text-gray-700 [&>th]:p-2">
                <th>Naam</th>
                <th>Start datum</th>
                <th>Eind datum</th>
                <th>Locatie</th>
                <th>Beschrijving</th>
                <th>Gewenste vrijwilligers</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach ($events as $event)
                <tr wire:key="event-{{ $event->id }}" class="border-t border-gray-300 [&>td]:p-2">
                    <td>{{ $event->name }}</td>
                    <td>{{ $event->start_date }}</td>
                    <td>{{ $event->end_date }}</td>
                    <td>{{ $event->location }}</td>
                    <td>{{ $event->description }}</td>
                    <td>{{ $event->user_events_count }}/{{ $event->max_volunteer }}</td>
                    <td>
                        <button
                            wire:click="showInfo({{ $event->id }})"
                            class="hover:bg-gray-300 transition border border-gray-300 py-2 px-4 rounded"
                            data-tippy-content="Info + inschrijven bij dit evenement">
                            Info
                        </button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </x-section>
    @endif
    {{--paginate--}}
    <div class="my-4 justify-center mx-auto">{{ $events->links() }}</div>

    <div
        x-data="{ open: @entangle('showModal') }"
        x-cloak
        x-show="open"
        x-transition.duration.500ms
        class="fixed z-40 inset-0 p-8 grid h-screen place-items-center backdrop-blur-sm backdrop-grayscale-[.7] bg-slate-100/70">
        <div
            @click.away="open = false;"
            @keyup.enter.window="open = false;"
            @keyup.esc.window="open = false;"
            class="bg-white p-4 border border-gray-300 max-w-2xl flex">
            <div class="mx-3">
                <p class="font-bold">{{ $selectedEvent->name ?? 'Naam' }}</p>
                <p>Start datum: {{ $selectedEvent->start_date ?? 'Start datum' }}</p>
                <p>Eind datum: {{ $selectedEvent->end_date ?? 'Eind datum' }}</p>
                <p>Locatie: {{ $selectedEvent->location ?? 'Locatie' }}</p>
            </div>
            <div class="mx-3">
                <p class="font-bold">Ingeschreven vrijwilligers:</p>
                @isset($selectedEvent)
                    @foreach($selectedEvent['userevents'] as $userevent)
                        <p>{{ $userevent['user']['first_name'] }} {{ $userevent['user']['last_name'] }}</p>
                    @endforeach
                @endisset
            </div>
            <div class="mx-3 flex flex-col justify-between">
                <button
                    @click="open = false;"
                    class="hover:bg-gray-300 transition border border-gray-300 py-2 px-4 rounded">
                    Sluiten
                </button>
                @if ($alreadyRegistered)
                    <span class="text-green-500">U bent al ingeschreven.</span>
                @else
                    <button
                        wire:click="createUserEvent({{ $selectedEvent->id ?? 'id' }})"
                        class="hover:bg-gray-300 transition border border-gray-300 py-2 px-4 rounded">
                        Inschrijven
                    </button>
                @endif
            </div>
        </div>
    </div>
    <x-help-modal>
        <x-slot name="title">Info inschrijven evenement</x-slot>
        <x-slot name="content">
            <section class="border-b">
                <p>Op deze pagina kan je je:</p>
                <ul class="ml-2 mt-2 list-disc">
                    <li class="mb-2">Inschrijven bij een evenement</li>
                </ul>
            </section>
            <section class="mt-2 border-b">
                <p class="font-medium">Bovenaan kan je evenementen filteren</p>
                <p class="mt-2">Hierin kan je volgende dingen doen:</p>
                <ul class="mt-2 ml-2 list-disc [&>li]:mb-2">
                    <li>Als u naar bepaalde evenementen wilt zoeken, kunt u bovenaan iets intypen in het veld <x-input id="search" type="text" placeholder="Filter op naam"
                                                                                                                       wire:model.debounce.500ms="search"
                                                                                                                       class="shadow-md placeholder-gray-300 px-2 border border-black" disabled/>.</li>
                    <li>Bij het veld <x-input id="location" type="text" placeholder="Filter op locatie"
                                              wire:model.debounce.500ms="location"
                                              class="shadow-md placeholder-gray-300 px-2 border border-black" disabled/> kunt u iets intypen om evenementen te zoeken met een bepaalde locatie.</li>
                    <li>Daarnaast ziet u een veld waar u een bepaald seizoen kunt selecteren om enkel de evenementen te zien van dat seizoen.</li>
                    <li>Bij elk evenement staat een
                        <button
                            class="hover:bg-gray-300 transition border border-gray-300 py-2 px-4 rounded">
                            Info
                        </button>-knop. Wanneer u daar op klikt zal er een pop-up verschijnen met info over dat evenement en wie er al als vrijwilliger is ingeschreven.</li>
                    <li>Daar gaat u ook een
                        <button
                            class="hover:bg-gray-300 transition border border-gray-300 py-2 px-4 rounded">
                            Inschrijven
                        </button>-knop terugvinden. Wanneer u daar op klikt zal u ingeschreven zijn bij dat evenement en zal u uw verdiende punten ontvangen.</li>
                </ul>
            </section>
        </x-slot>
    </x-help-modal>
</div>
