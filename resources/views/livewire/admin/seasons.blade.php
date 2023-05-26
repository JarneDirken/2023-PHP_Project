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
        <x-slot name="title">Info seizoenen beheren</x-slot>
        <x-slot name="content">
            <section class="border-b">
                <p>Op deze pagina kan je je:</p>
                <ul class="ml-2 mt-2 list-disc">
                    <li class="mb-2">De seizoenen beheren</li>
                </ul>
            </section>
            <section class="mt-2 border-b">
                <p class="font-medium">Bovenaan kun je seizoenen toevoegen</p>
                <ul class="mt-2 ml-2 list-disc [&>li]:mb-2">
                    <li>Links op de knop
                        <x-button class="mt-3">
                            nieuw seizoen aanmaken
                        </x-button>
                        te klikken
                    </li>
                </ul>
            </section>
            <section class="mt-2 border-b">
                <p class="font-medium">Als je de knop <x-button class="mt-3">
                         nieuw seizoen aanmaken
                    </x-button> aanklikt.</p>
                <p class="mt-2">Hierin kan je:</p>
                <ul class="mt-2 ml-2 list-disc [&>li]:mb-2">
                    <li>
                        De start- en eind datum in te geven.
                    </li>
                    <li>
                        Kiezen of het seizoen actief is of niet.
                    </li>
                    <li>Een nieuw seizoen te maken door op
                        <x-button class="mt-3">
                            Maak nieuw seizoen
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
                            Pas een seizoen aan
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center">
                            <x-phosphor-trash-duotone class="hover:text-red-900 h-8 w-8 inline"></x-phosphor-trash-duotone>
                        </td>
                        <td>
                            Verwijder een seizoen
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center">
                            <x-heroicon-s-chevron-up class="w-5 text-slate-400 inline h-8 w-8 hover:rotate-180 transition-all"/>
                        </td>
                        <td>
                            Sorteer op actief seizoen
                        </td>
                    </tr>
                    </tbody>
                </table>
            </section>
            <section class="border-t mt-2">
                <p class="mt-5 text-blue-900">Een seizoen mag niet verwijderd worden als er een gebruiker verbonden is aan dit seizoen</p>
            </section>
        </x-slot>
    </x-help-modal>

    {{-- filter section --}}
    <x-section class="flex gap-3 flex-wrap mb-3 max-w-4xl mx-auto flex justify-center">
        <div class="flex justify-center items-center">
            <x-button class="mt-3" wire:click="showSeason()">
                Nieuw seizoen aanmaken
            </x-button>
        </div>
    </x-section>

    {{--paginate--}}
    <div class="my-4 justify-center mx-auto max-w-4xl">{{ $seasons->links() }}</div>
    {{-- main section --}}
    <x-section class="max-w-4xl mx-auto flex justify-center overflow-x-auto">
        <table class="text-center w-full border border-gray-300">
            <colgroup>
                <col class="w-50">
                <col class="w-50">
                <col class="w-30">
                <col class="w-max">
            </colgroup>
            <thead>
            <tr class="bg-gray-100 text-gray-700 [&>th]:p-2 cursor-pointer">
                <th>
                    <span>Start datum</span>
                </th>
                <th>
                    <span>Eind datum</span>
                </th>
                <th wire:click="resort('active')">
                    <span data-tippy-content="Sorteer op Actief">Actief</span>
                    <x-heroicon-s-chevron-up
                        class="w-5 text-slate-400
                        {{$orderAsc ?: 'rotate-180'}}
                        {{$orderBy == 'active' ? 'inline-block' : 'hidden'}}
                            "/>
                </th>
                <th></th>
            </tr>
            </thead>
            @php
                $maandNamen = [
                    1 => 'Januari',
                    12 => 'December',
                ];
            @endphp
            <tbody>
            @foreach($seasons as $season)
                <tr class="border-t border-gray-300 [&>td]:p-2">
                    <td>
                        {{ \Carbon\Carbon::parse($season->start_date)->format('j ') . $maandNamen[\Carbon\Carbon::parse($season->start_date)->format('n')] . \Carbon\Carbon::parse($season->start_date)->format(' Y') }}
                    </td>
                    <td>
                        {{ \Carbon\Carbon::parse($season->end_date)->format('j ') . $maandNamen[\Carbon\Carbon::parse($season->end_date)->format('n')] . \Carbon\Carbon::parse($season->end_date)->format(' Y') }}
                    </td>
                    <td>
                        @if($season->active)
                            ja
                        @else
                            nee
                        @endif
                    </td>
                    <td
                        x-data="">
                        <div class="flex gap-1 justify-end [&>*]:cursor-pointer [&>*]:outline-0 [&>*]:transition">
                            <x-phosphor-pencil-line-duotone
                                wire:click="setNewSeason({{ $season->id }})"
                                class="w-5 text-gray-300 hover:text-green-600"/>
                            {{-- alleen als een seizoen met nog geen users is verbonden mag je ze deleten (DTR) --}}
                            @if(($season->membershipusers->count() ?? 0) == 0)
                            <x-phosphor-trash-duotone
                                @click="$dispatch('swal:confirm', {
                                title: 'Verwijder seizoen?',
                                cancelButtonText: 'Annuleer',
                                confirmButtonText: 'Verwijder het seizoen',
                                next: {
                                    event: 'delete-season',
                                    params: {
                                        id: {{ $season->id }}
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
    {{--paginate--}}
    <div class="my-4 justify-center mx-auto max-w-4xl">{{ $seasons->links() }}</div>

    {{--model voor het aanmaken van een seizoen--}}
    <x-dialog-modal  id="recordModal"
                     wire:model="showModal">
        <x-slot name="title" class="text-center">
            <h2>{{ is_null($newSeason['id']) ? 'Nieuwe seizoen' : 'Seizoen bijwerken' }}</h2>
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
            <div class="float-left w-1/2">
                <x-label for="date" value="Start Datum" class="mt-4"></x-label>
                <x-input type="date" id="date" name="StartDatum" lang="" wire:model.defer="newSeason.start_date"
                         min="2023-01-01" max="2040-12-31" ></x-input>
            </div>
            <div class="float-right w-1/2 text-right">
                <x-label for="date" value="Eind Datum" class="mt-4"></x-label>
                <x-input type="date" id="date" name="EndDatum" lang="" wire:model.defer="newSeason.end_date"
                         min="2023-01-01" max="2040-12-31" ></x-input>
            </div>
            <div class="justify-center mt-4">
                <x-label for="actief" value="Actief" class="mt-4 text-center"></x-label>
                <div class="flex justify-center">
                    <x-checkbox id="actief" wire:model="newSeason.active" class="justify-center text-center mt-1"></x-checkbox>
                </div>
            </div>
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button @click="show = false">Annuleer</x-secondary-button>
            @if(is_null($newSeason['id']))
                <x-button
                    wire:click="createSeason()"
                    wire:loading.attr="disabled"
                    class="ml-2">Maak nieuw seizoen
                </x-button>
            @else
                <x-button
                    color="success"
                    wire:click="updateSeason({{ $newSeason['id'] }})"
                    wire:loading.attr="disabled"
                    class="ml-2">Werk seizoen bij
                </x-button>
            @endif
        </x-slot>
    </x-dialog-modal>
</div>
