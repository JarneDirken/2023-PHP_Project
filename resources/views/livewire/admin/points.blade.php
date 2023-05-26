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
        <x-slot name="title">Info punten beheren</x-slot>
        <x-slot name="content">
            <section class="border-b">
                <p>Op deze pagina kan je je:</p>
                <ul class="ml-2 mt-2 list-disc">
                    <li class="mb-2">Alle punten beheren</li>
                </ul>
            </section>
            <section class="mt-2 border-b">
                <p class="font-medium">Bovenaan kun je filteren en toevoegen</p>
                <p class="mt-2">Hierin kan je:</p>
                <ul class="mt-2 ml-2 list-disc [&>li]:mb-2">
                    <li>De tabel filteren op naam door simpel weg de vraag te beginnen typen in:
                        <x-input type="text" readonly placeholder="Filter op punten naam" class="shadow-md placeholder-gray-300 px-2 border border-black flex-grow basis-1/2"/></li>
                    <li>Rechts op de knop
                        <x-button class="mb-3">
                            Nieuwe puntensoort
                        </x-button>
                        te klikken.
                    </li>
                </ul>
            </section>
            <section class="mt-2 border-b">
                <p class="font-medium">Als je de knop <x-button class="mb-3">
                        Nieuwe puntensoort
                    </x-button> aanklikt.</p>
                <p class="mt-2">Hierin kan je:</p>
                <ul class="mt-2 ml-2 list-disc [&>li]:mb-2">
                    <li>
                        De naam, de hoeveelheid punten en de maximumhoeveelheid punten ingeven.
                    </li>
                    <li>Een nieuwe puntensoort te maken door op
                        <x-button class="mb-3">
                            Maak nieuwe puntensoort
                        </x-button>
                        te klikken.
                    </li>
                    <li>Annuleren door op
                        <x-secondary-button>Annuleer</x-secondary-button>
                        te klikken.
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
                            Pas een puntensoort aan
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center">
                            <x-phosphor-trash-duotone class="hover:text-red-900 h-8 w-8 inline"></x-phosphor-trash-duotone>
                        </td>
                        <td>
                            Verwijder een puntensoort
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center">
                            <x-heroicon-s-chevron-up class="w-5 text-slate-400 inline h-8 w-8 hover:rotate-180 transition-all"/>
                        </td>
                        <td>
                            Sorteer op naam van de puntensoort
                        </td>
                    </tr>
                    </tbody>
                </table>
            </section>
            <section class="border-t mt-2">
                <p class="mt-5 text-blue-900">Een puntensoort mag niet verwijderd worden als een gebruiker al punten voor deze soort heeft</p>
            </section>
        </x-slot>
    </x-help-modal>

    {{-- filter section --}}
    <x-section class="flex gap-3 flex-wrap max-w-4xl mx-auto items-center justify-center mb-3">
        <x-input id="search" type="text" placeholder="Filter op punten naam"
                 wire:model="name"
                 class="shadow-md placeholder-gray-300 px-2 border border-black flex-grow basis-1/2"/>
            <x-button wire:click="setNewPoint()">
                Nieuwe puntensoort
            </x-button>
    </x-section>

    {{-- Main section --}}
    @if($points->isEmpty())
        <x-alert dismissable="false" type="warning" class="max-w-4xl mx-auto justify-center flex">
            Geen resultaten voor: <b>'{{ $name }}'</b>
        </x-alert>
    @else
    <x-section class="max-w-4xl mx-auto flex justify-center overflow-x-auto">
    <table class="text-center w-full border border-gray-300">
        <thead>
        <tr class="bg-gray-100 text-gray-700 [&>th]:p-2">
            <th wire:click="resort('name')" class="cursor-pointer">
                <span data-tippy-content="Sorteer op naam">Naam</span>
                <x-heroicon-s-chevron-up
                    class="w-5 text-slate-400
                             {{$orderAsc ?: 'rotate-180'}}
                             {{$orderBy === 'name' ? 'inline-block' : 'hidden'}}
                         "/>
            </th>
            <th>
                <span>
                    Aantal
                </span>
            </th>
            <th>
                <span>Maximum</span>

            </th>
           <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($points as $point)
            <tr class="border-t border-gray-300 [&>td]:p-2">
                <td>
                    {{$point->name}}
                </td>
                <td>
                    {{$point->amount}}
                </td>
                <td>
                    {{$point->maximum}}
                </td>
                <td
                    x-data="">
                    <div class="flex gap-1 justify-end [&>*]:cursor-pointer [&>*]:outline-0 [&>*]:transition">
                            <x-phosphor-pencil-line-duotone
                                wire:click="setNewPoint({{ $point->id }})"
                                class="w-5 text-gray-300 hover:text-green-600"/>
                            {{-- alleen als een puntensoort met nog geen gebruikers is verbonden mag je ze deleten (DTR) --}}
                            @if(($point->pointusers->count() ?? 0) == 0)
                        <x-phosphor-trash-duotone
                            @click="$dispatch('swal:confirm', {
                                title: 'Verwijder puntensoort?',
                                cancelButtonText: 'Annuleer',
                                confirmButtonText: 'Verwijder de puntensoort',
                                next: {
                                    event: 'delete-point',
                                    params: {
                                        id: {{ $point->id }}
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

    {{--Model voor het maken van punten--}}
    <x-dialog-modal  id="pointModal"
                     wire:model="showModal">
        <x-slot name="title" class="text-center">
            <h2>{{ is_null($newPoint['id']) ? 'Nieuwe puntensoort' : 'Puntensoort bijwerken' }}</h2>
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
            <table>
                <tr>
                    <td>
                        <x-label for="name" value="Naam" ></x-label>
                        <x-input id="name" class="w-3/4" type="text" placeholder="Naam" wire:model="newPoint.name" ></x-input>
                    </td>
                    <td>
                        <x-label for="amount" value="Aantal"></x-label>
                        <x-input id="amount" class="w-3/4" type="number" min="1" placeholder="Aantal" wire:model="newPoint.amount"></x-input>
                    </td>
                    <td>
                        <x-label for="maximum"  value="Maximum"></x-label>
                        <x-input id="maximum" class="w-3/4" type="number" min="1" placeholder="Maximum" wire:model="newPoint.maximum"></x-input>
                    </td>
                </tr>
            </table>
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button @click="show = false">Annuleer</x-secondary-button>
                @if(is_null($newPoint['id']))
                    <x-button
                        wire:click="createPoint()"
                        wire:loading.attr="disabled"
                        class="ml-2">Maak nieuwe puntensoort
                    </x-button>
                @else
                    <x-button
                        color="success"
                        wire:click="updatePoint({{ $newPoint['id'] }})"
                        wire:loading.attr="disabled"
                        class="ml-2">Werk puntensoort bij
                    </x-button>
                @endif
        </x-slot>
    </x-dialog-modal>

</div>
