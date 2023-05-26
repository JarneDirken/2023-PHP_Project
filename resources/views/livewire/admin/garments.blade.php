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
        <x-slot name="title">Info kledingstukken beheren</x-slot>
        <x-slot name="content">
            <section class="border-b">
                <p>Op deze pagina kan je je:</p>
                <ul class="ml-2 mt-2 list-disc">
                    <li class="mb-2">Kledingstukken bekijken en beheren</li>
                </ul>
            </section>
            <section class="mt-2 border-b">
                <p class="font-medium">Bovenaan kun je filteren en toevoegen.</p>
                <p class="mt-2">Hierin kan je:</p>
                <ul class="mt-2 ml-2 list-disc [&>li]:mb-2">
                    <li>De tabel filteren op naam door simpel weg de naam te beginnen typen.
                        <x-input type="text" readonly placeholder="Filter op naam" class="shadow-md placeholder-gray-300 px-2 border border-black flex-grow basis-1/2"/>
                    <li>Rechts op de knop
                        <x-button class="mt-3">
                            Nieuw kledingstuk aanmaken
                        </x-button>
                        te klikken, om een nieuw kledingstuk aan te maken.
                    </li>
                </ul>
            </section>
            <section class="mt-2 border-b">
                <p class="font-medium">Bovenaan kun je ook een foto uploaden.</p>
                <p class="mt-2">Hierin kan je:</p>
                <ul class="mt-2 ml-2 list-disc [&>li]:mb-2">
                    <li>
                        Een foto van je computer kiezen die je wil uploaden.
                    </li>
                    <li>Rechts op de knop
                        <x-button class="mt-3">
                            Upload foto
                        </x-button>
                        klikken, om de gekozen foto te uploaden.
                    </li>
                </ul>
            </section>
            <section class="mt-2 border-b">
                <p class="font-medium">Als je de knop <x-button class="mt-3">
                        Nieuw kledingstuk aanmaken
                    </x-button> aanklikt.</p>
                <p class="mt-2">Hierin kan je:</p>
                <ul class="mt-2 ml-2 list-disc [&>li]:mb-2">
                    <li>
                        De naam, prijs, beschrijving, actief en foto toe te voegen.
                    </li>
                    <li>Een nieuwe kledingstuk te maken door op
                        <x-button class="mt-3">
                            Maak nieuw kledingstuk
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
                            Pas een kledingstuk aan
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center">
                            <x-phosphor-trash-duotone class="hover:text-red-900 h-8 w-8 inline"></x-phosphor-trash-duotone>
                        </td>
                        <td>
                            Verwijder een kledingstuk
                        </td>
                    </tr>
                    </tbody>
                </table>
            </section>
            <section class="border-t mt-2">
                <p class="mt-5 text-blue-900">Een kledingstuk mag niet verwijderd worden als een artikel op basis van dit kledingstuk is gemaakt</p>
            </section>
        </x-slot>
    </x-help-modal>

    {{-- filter section --}}
    <x-section class="flex flex-wrap gap-3 mb-3">
        <x-input id="search" type="text" placeholder="Filter op naam"
                 wire:model="name"
                 class="shadow-md placeholder-gray-300 px-2 border border-black flex-grow basis-1/2"/>
            <x-button
                      wire:click="showGarment()">
                Nieuw kledingstuk aanmaken
            </x-button>
    </x-section>

    {{--paginate--}}
    <div class="my-4 justify-center mx-auto">{{ $garments->links() }}</div>
    {{-- main section --}}
    @if($garments->isEmpty())
        <x-alert dismissable="false" type="warning">
            Geen resultaten voor: <b>'{{ $name }}'</b>
        </x-alert>
    @else
    <x-section class="overflow-x-auto">
        <table class="text-center w-full border border-gray-300">
            <colgroup>
                <col class="w-50">
                <col class="w-20">
                <col class="w-70">
                <col class="w-20">
                <col class="w-50">
                <col class="w-max">
            </colgroup>
            <thead>
            <tr class="bg-gray-100 text-gray-700 [&>th]:p-2 cursor-pointer">
                <th>
                    <span>Naam</span>
                </th>
                <th>
                    <span>Prijs</span>
                </th>
                <th>
                    <span>Beschrijving</span>
                </th>
                <th>
                    <span>Actief</span>
                </th>
                <th></th>
            </tr>
            </thead>

            <tbody>
            @foreach($garments as $garment)
                <tr class="border-t border-gray-300 [&>td]:p-2">
                    <td>
                        {{$garment->name}}
                    </td>
                    <td>
                        €{{$garment->price}}
                    </td>
                    <td>
                        {{$garment->description}}
                    </td>
                    <td>
                        @if($garment->active)
                            ja
                        @else
                            nee
                        @endif
                    </td>
                    <td
                        x-data="">
                        <div class="flex gap-1 justify-end [&>*]:cursor-pointer [&>*]:outline-0 [&>*]:transition">
                            <x-phosphor-pencil-line-duotone
                                wire:click="setNewGarment({{ $garment->id }})"
                                class="w-5 text-gray-300 hover:text-green-600"/>
                            {{-- alleen als een kledingstuk met nog geen artikels (mix kledingstuk/maat) is verbonden mag je ze deleten (DTR) --}}
                            @if(($garment->articles->count() ?? 0) == 0)
                            <x-phosphor-trash-duotone
                                @click="$dispatch('swal:confirm', {
                                title: 'Verwijder {{$garment->name}}?',
                                cancelButtonText: 'Annuleer',
                                confirmButtonText: 'Verwijder het kledingstuk',
                                next: {
                                    event: 'delete-garment',
                                    params: {
                                        id: {{ $garment->id }}
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
        {{--model voor het aanmaken van een kledingstuk--}}
        <x-dialog-modal  id="recordModal"
                         wire:model="showModal">
            <x-slot name="title" class="text-center">
                <h2>{{ is_null($newGarment['id']) ? 'Nieuwe kledingstuk' : 'Kledingstuk bijwerken' }}</h2>
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
                <x-input id="name" type="text" placeholder="Naam" wire:model="newGarment.name" class="w-full"></x-input>
                <x-label for="price" value="Prijs" class="mt-4"></x-label>
                <x-input id="price" type="number" step="0.01" placeholder="Prijs" wire:model="newGarment.price" class="w-full"></x-input>
                <x-label for="name" value="Beschrijving" class="mt-4"></x-label>
                <x-input id="name" type="text" placeholder="Beschrijving" wire:model="newGarment.description" class="w-full"></x-input>
                <x-label for="name" value="Actief" class="mt-4"></x-label>
                <x-checkbox id="name" wire:model="newGarment.active"></x-checkbox>
                    <form wire:submit.prevent="save" class="md:flex justify-between grow m-1.5">
                        @if (!is_null($newGarment['id']))
                            <div>
                                <span class="text-gray-400">huidige foto:</span>
                                <img src="{{ $photoURL }}" alt="Current Image" class="w-32 h-32 mt-2">
                            </div>
                        @endif
                        <div>
                            <input type="file" wire:model="photo">
                            @error('photo') <span class="error">{{ $message }}</span> @enderror
                            <x-button type="submit" :disabled="$photoUploaded" class="flex justify-end">Upload photo</x-button>
                        </div>
                    </form>
            </x-slot>
            <x-slot name="footer">
                <x-secondary-button @click="show = false">Annuleer</x-secondary-button>
                @if(is_null($newGarment['id']))
                    <x-button
                        wire:click="createGarment()"
                        wire:loading.attr="disabled"
                        class="ml-2">Maak nieuw kledingstuk
                    </x-button>
                @else
                    <x-button
                        color="success"
                        wire:click="updateGarment({{ $newGarment['id'] }})"
                        wire:loading.attr="disabled"
                        class="ml-2">Werk kledingstuk bij
                    </x-button>
                @endif
            </x-slot>
        </x-dialog-modal>
</div>
