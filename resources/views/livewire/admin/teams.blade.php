<div>
    {{-- preloader section --}}
    <div class="fixed top-12 left-1/2 -translate-x-1/2 z-50 animate-pulse"
         wire:loading>
        <x-preloader class="bg-green-400/75 text-gray-800 border border-green-700 shadow-2xl">
            Laden...
        </x-preloader>
    </div>

    {{-- help icon section --}}
    <x-help-modal>
        <x-slot name="title">Info ploegen beheren</x-slot>
        <x-slot name="content">
            <section class="border-b">
                <p>Op deze pagina kan je je:</p>
                <ul class="ml-2 mt-2 list-disc">
                    <li class="mb-2">Alle ploegen beheren</li>
                </ul>
            </section>
            <section class="mt-2 border-b">
                <p class="font-medium">Bovenaan kun je filteren en toevoegen</p>
                <p class="mt-2">Hierin kan je:</p>
                <ul class="mt-2 ml-2 list-disc [&>li]:mb-2">
                    <li>De tabel filteren op naam door simpel weg de vraag te beginnen typen in:
                        <x-input type="text" readonly placeholder="Filter op team naam" class="shadow-md placeholder-gray-300 px-2 border border-black flex-grow basis-1/2"/></li>
                    <li>Rechts op de knop
                        <x-button class="mt-3">
                            nieuwe ploeg aanmaken
                        </x-button>
                        te klikken.
                    </li>
                </ul>
            </section>
            <section class="mt-2 border-b">
                <p class="font-medium">Als je de knop <x-button class="mt-3">
                        nieuwe ploeg aanmaken
                    </x-button> aanklikt.</p>
                <p class="mt-2">Hierin kan je:</p>
                <ul class="mt-2 ml-2 list-disc [&>li]:mb-2">
                    <li>
                        De naam en de snelheid van de nieuwe ploeg ingeven.
                    </li>
                    <li>Een nieuwe ploeg te maken door op
                        <x-button class="mt-3">
                            maak nieuwe ploeg
                        </x-button>
                        te klikken
                    </li>
                    <li>Annuleren door op
                        <x-secondary-button>Annuleer</x-secondary-button>
                        te klikken
                    </li>
                </ul>
            </section>
            <section class="mb-2 mt-2">
                <p class="font-medium mb-2">Legende symbolen</p>
                <table class="border-2 border-black w-full">
                    <thead class="bg-gray-200">
                    <tr>
                        <td>Symbool</td>
                        <td>Uitleg</td>
                    </tr>
                    </thead>
                    <tbody class="[&>tr]:border-t [&>tr]:border-gray-500 [& td]:p-2 [& td]:ml-1">
                    <tr>
                        <td class="text-center">
                            <x-phosphor-pencil-duotone class="hover:text-blue-900 h-8 w-8 inline"></x-phosphor-pencil-duotone>
                        </td>
                        <td>
                            Pas een ploeg aan
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center">
                            <x-phosphor-trash-duotone class="hover:text-red-900 h-8 w-8 inline"></x-phosphor-trash-duotone>
                        </td>
                        <td>
                            Verwijder een ploeg
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center">
                            <x-heroicon-s-chevron-up class="w-5 text-slate-400 inline h-8 w-8 hover:rotate-180 transition-all"/>
                        </td>
                        <td>
                            Sorteer op naam van de ploeg
                        </td>
                    </tr>
                    </tbody>
                </table>
            </section>
            <section class="border-t mt-2">
                <p class="mt-5 text-blue-900">Een ploeg mag niet verwijderd worden als deze met minstens 1 rit is verbonden</p>
            </section>
        </x-slot>
    </x-help-modal>

    {{-- filter section --}}
    <x-section class="flex gap-3 flex-wrap max-w-4xl mx-auto items-center justify-center mb-3">
        <x-input id="search" type="text" placeholder="Filter op team naam"
                 wire:model="name"
                 class="shadow-md placeholder-gray-300 px-2 border border-black flex-grow basis-1/2"/>
            <x-button
                      wire:click="showTeam()"
                      data-tippy-content="Nieuwe ploeg">
                Nieuwe ploeg aanmaken
            </x-button>
    </x-section>

    {{--paginate--}}
    <div class="my-4 justify-center mx-auto max-w-4xl">{{ $teams->links() }}</div>
    {{-- main section --}}
    @if($teams->isEmpty())
        <x-alert dismissable="false" type="warning" class="max-w-4xl mx-auto justify-center flex">
            Geen resultaten voor: <b>'{{ $name }}'</b>
        </x-alert>
    @else
    <x-section class="max-w-4xl mx-auto flex justify-center overflow-x-auto">
        <table class="text-center w-full border border-gray-300">
            <colgroup>
                <col class="w-60">
                <col class="w-48">
                <col class="w-max">
            </colgroup>
            <thead>
            <tr class="bg-gray-100 text-gray-700 [&>th]:p-2 cursor-pointer">
                <th wire:click="resort('name')">
                    <span data-tippy-content="Sorteer op ploeg">Ploeg naam</span>
                    <x-heroicon-s-chevron-up
                        class="w-5 text-slate-400
                        {{$orderAsc ?: 'rotate-180'}}
                        {{$orderBy === 'name' ? 'inline-block' : 'hidden'}}
                            "/>
                </th>
                <th wire:click="resort('speed_aim')">
                    <span>
                        Verwachte snelheid
                    </span>
                    <x-heroicon-s-chevron-up
                        class="w-5 text-slate-400
                        {{$orderAsc ?: 'rotate-180'}}
                        {{$orderBy === 'speed_aim' ? 'inline-block' : 'hidden'}}
                            "/>
                </th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($teams as $team)
                <tr class="border-t border-gray-300 [&>td]:p-2">
                    <td>
                        {{$team->name}}
                    </td>
                    <td>
                        {{$team->speed_aim}} km/u
                    </td>
                    <td
                        x-data="">
                        <div class="flex gap-1 justify-end [&>*]:cursor-pointer [&>*]:outline-0 [&>*]:transition">
                            <x-phosphor-pencil-line-duotone
                                wire:click="setNewTeam({{ $team->id }})"
                                class="w-5 text-gray-300 hover:text-green-600"/>
                            {{-- alleen als een ploeg met nog geen ritten is verbonden mag je ze deleten (DTR) --}}
                            @if(($team->tours->count() ?? 0) == 0)
                            <x-phosphor-trash-duotone
                                @click="$dispatch('swal:confirm', {
                                title: 'Verwijder ploeg?',
                                cancelButtonText: 'Annuleer',
                                confirmButtonText: 'Verwijder de ploeg',
                                next: {
                                    event: 'delete-team',
                                    params: {
                                        id: {{ $team->id }}
                                    }
                                }
                            });"
                                class="w-5 text-gray-300 hover:text-red-600"/>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
                </tbody>
        </table>
    </x-section>
    @endif
    {{--paginate--}}
    <div class="my-4 justify-center mx-auto max-w-4xl">{{ $teams->links() }}</div>

    {{-- Popup for creating new team--}}
    <x-dialog-modal  id="recordModal"
                     wire:model="showModal">
        <x-slot name="title" class="text-center">
            <h2>{{ is_null($newTeam['id']) ? 'Nieuw team' : 'Team bijwerken' }}</h2>
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
            <x-label for="name" value="Naam" class="mt-4"></x-label>
            <x-input id="name" type="text" placeholder="Naam" wire:model="newTeam.name" class="w-full"></x-input>
            <x-label for="snelheid" value="Snelheid" class="mt-4"></x-label>
            <x-input id="snelheid" type="number" placeholder="Snelheid" wire:model="newTeam.speed_aim" class="w-full"></x-input>
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button @click="show = false">Annuleer</x-secondary-button>
            @if(is_null($newTeam['id']))
                <x-button
                    wire:click="createTeam()"
                    wire:loading.attr="disabled"
                    class="ml-2">Maak nieuwe ploeg
                </x-button>
            @else
                <x-button
                    color="success"
                    wire:click="updateTeam({{ $newTeam['id'] }})"
                    wire:loading.attr="disabled"
                    class="ml-2">Werk ploeg bij
                </x-button>
            @endif
        </x-slot>
    </x-dialog-modal>
</div>
