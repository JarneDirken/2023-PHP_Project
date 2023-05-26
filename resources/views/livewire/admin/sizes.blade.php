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
        <x-slot name="title">Info maten beheren</x-slot>
        <x-slot name="content">
            <section class="border-b">
                <p>Op deze pagina kan je je:</p>
                <ul class="ml-2 mt-2 list-disc">
                    <li class="mb-2">Maten bekijken en beheren</li>
                </ul>
            </section>
            <section class="mt-2 border-b">
                <p class="font-medium">Bovenaan kun je maten toevoegen.</p>
                <p class="mt-2">Hierin kan je:</p>
                <ul class="mt-2 ml-2 list-disc [&>li]:mb-2">
                    <li>
                    Rechts op de knop
                        <x-button class="mt-3">
                            Nieuw maat
                        </x-button>
                        te klikken, om een nieuwe maat aan te maken.
                    </li>
                </ul>
            </section>
            <section class="mt-2 border-b">
                <p class="font-medium">Als je de knop <x-button class="mt-3">
                        Nieuw maat
                    </x-button> aanklikt.</p>
                <p class="mt-2">Hierin kan je:</p>
                <ul class="mt-2 ml-2 list-disc [&>li]:mb-2">
                    <li>
                        De naam van de nieuwe maat ingeven.
                    </li>
                    <li>Een nieuwe maat te maken door op
                        <x-button class="mt-3">
                            voeg toe
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
                            Pas een maat aan
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center">
                            <x-phosphor-trash-duotone class="hover:text-red-900 h-8 w-8 inline"></x-phosphor-trash-duotone>
                        </td>
                        <td>
                            Verwijder een maat
                        </td>
                    </tr>
                    </tbody>
                </table>
            </section>
            <section class="border-t mt-2">
                <p class="mt-5 text-blue-900">Een maat mag niet verwijderd worden als een artikel deze maat gebruikt</p>
            </section>
        </x-slot>
    </x-help-modal>

    {{-- filter section --}}
    <x-section class="flex gap-3 flex-wrap mb-3 max-w-lg mx-auto justify-center">
        <div class="flex justify-center items-center">
            <x-button wire:click="setNewSize()" class="mt-3">
                Nieuwe maat
            </x-button>
        </div>
    </x-section>

    {{-- main section --}}
    <x-section class="max-w-lg mx-auto justify-center flex">
        <table class="text-center w-full border border-gray-300">
            <colgroup>
                <col class="w-1/2">
                <col class="w-1/2">
            </colgroup>
            <thead>
            <tr class="bg-gray-100 text-gray-700 [&>th]:p-2">
                <th>Naam</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @forelse($sizes as $size)
                <tr wire:key="size_{{ $size->id }}" class="border-t border-gray-300">
                    <td>{{ $size->name }}</td>
                    <td>
                        <div class="flex gap-1 justify-center [&>*]:cursor-pointer [&>*]:outline-0 [&>*]:transition">
                            <button
                                wire:click="setNewSize({{ $size->id }})"
                                class="w-5 text-gray-300 hover:text-green-600">
                                <x-phosphor-pencil-line-duotone class="inline-block w-5 h-5"/>
                            </button>
                            {{-- alleen als een maat met nog geen artikelis verbonden mag je ze deleten (DTR) --}}
                            @if(($size->articles->count() ?? 0) == 0)
                            <button
                                x-data=""
                                @click="$dispatch('swal:confirm', {
                                        title: 'Ben je zeker dat je deze maat wilt verwijderen?',
                                        cancelButtonText: 'Nee',
                                        confirmButtonText: 'Ja, verwijder deze maat',
                                        next: {
                                            event: 'delete-size',
                                            params: {
                                                id: {{ $size->id }}
                                            }
                                        }
                                    });"
                                class="w-5 text-gray-300 hover:text-red-600">
                                <x-phosphor-trash-duotone class="inline-block w-5 h-5"/>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="border-t border-gray-300 p-4 text-center text-gray-500">
                        <div class="font-bold italic text-sky-800">Geen maten gevonden</div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </x-section>

    {{-- Model voor maten te maken --}}
    <x-dialog-modal  id="sizeModal"
                         wire:model="showModal">
        <x-slot name="title">
            <h2>{{ is_null($newSize['id']) ? 'Nieuwe maat' : 'Maat aanpassen' }}</h2>
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
            <div class="flex flex-row gap-4 mt-4">
                <div class="flex-1 flex-col gap-2">
                    <x-label for="name" value="Naam" class="mt-4"/>
                    <x-input id="name" type="text"
                                 wire:model.defer="newSize.name"
                                 class="mt-1 block w-full"/>
                </div>
            </div>
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button @click="show = false">Annuleer</x-secondary-button>
            @if(is_null($newSize['id']))
            <x-button
                wire:click="createSize()"
                wire:loading.attr="disabled"
                class="ml-2">Voeg toe
            </x-button>
            @else
                <x-button
                    color="success"
                    wire:click="updateSize({{ $newSize['id'] }})"
                    wire:loading.attr="disabled"
                    class="ml-2">Maat aanpassen
                </x-button>
            @endif
        </x-slot>
    </x-dialog-modal>
</div>
