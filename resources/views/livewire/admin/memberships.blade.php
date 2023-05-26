@php use App\Models\MembershipUser; @endphp
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
        <x-slot name="title">Info lidmaatschappen beheren</x-slot>
        <x-slot name="content">
            <section class="border-b">
                <p>Op deze pagina kan je je:</p>
                <ul class="ml-2 mt-2 list-disc">
                    <li class="mb-2">Lidmaatschappen bekijken en beheren</li>
                </ul>
            </section>
            <section class="mt-2 border-b">
                <p class="font-medium">Bovenaan kun je filteren en toevoegen.</p>
                <p class="mt-2">Hierin kan je:</p>
                <ul class="mt-2 ml-2 list-disc [&>li]:mb-2">
                    <li>De tabel filteren op naam door simpel weg de naam te beginnen typen.
                        <x-input type="text" readonly placeholder="Filter op lidmaatschap" class="shadow-md placeholder-gray-300 px-2 border border-black flex-grow basis-1/2"/>
                    </li>
                    <li>Rechts op de knop
                        <x-button class="mb-3">
                            Nieuw lidmaatschap
                        </x-button>
                        te klikken, om een nieuw lidmaatschap aan te maken.
                    </li>
                </ul>
            </section>
            <section class="mt-2 border-b">
                <p class="font-medium">Als je de knop
                    <x-button class="mb-3">
                        Nieuw lidmaatschap
                    </x-button>aanklikt.</p>
                <p class="mt-2">Hierin kan je:</p>
                <ul class="mt-2 ml-2 list-disc [&>li]:mb-2">
                    <li>
                        De naam en de prijs van het nieuwe lidmaatschap ingeven.
                    </li>
                    <li>Een nieuwe lidmaatschap te maken door op
                        <x-button class="mb-3">
                            Maak nieuw lidmaatschap
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
                            Pas een lidmaatschap aan
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center">
                            <x-phosphor-trash-duotone class="hover:text-red-900 h-8 w-8 inline"></x-phosphor-trash-duotone>
                        </td>
                        <td>
                            Verwijder een lidmaatschap
                        </td>
                    </tr>
                    </tbody>
                </table>
            </section>
            <section class="border-t">
                <p class="mt-5 text-blue-900">Een lidmaatschap mag niet verwijderd worden als een gebruiker dit lidmaatschap heeft</p>
            </section>
        </x-slot>
    </x-help-modal>

    {{-- filter section --}}
    <x-section class="flex flex-wrap gap-3 mb-3 max-w-2xl mx-auto justify-center">
        <x-input id="search" type="text" placeholder="Filter op lidmaatschap"
                 wire:model="name"
                 class="shadow-md placeholder-gray-300 px-2 border border-black flex-grow basis-1/2"/>
        <x-button
            wire:click="showMembership()"
            data-tippy-content="Nieuw lidmaatschap">
            Nieuw lidmaatschap
        </x-button>
    </x-section>

    {{-- main section --}}
    @if($memberships->isEmpty())
        <x-alert dismissable="false" type="warning" class="max-w-2xl mx-auto justify-center flex">
            Geen resultaten voor: <b>'{{ $name }}'</b>
        </x-alert>
    @else
    <x-section class="max-w-2xl mx-auto justify-center flex overflow-x-auto">
        <table class="text-center w-full border border-gray-300">
            <colgroup>
                <col class="w-40">
                <col class="w-20">
                <col class="w-max">
            </colgroup>
            <thead>
            <tr class="bg-gray-100 text-gray-700 [&>th]:p-2">
                <th>
                    <span>Naam</span>
                </th>
                <th>
                    <span>Prijs</span>
                </th>
                <th></th>
            </tr>
            </thead>

            <tbody>
            @foreach($memberships as $membership)
            <tr class="border-t border-gray-300 [&>td]:p-2">
                <td>
                    {{$membership->name}}
                </td>
                <td>
                    €{{$membership->price}}
                </td>
                <td
                    x-data="">
                    <div class="flex gap-1 justify-end [&>*]:cursor-pointer [&>*]:outline-0 [&>*]:transition">
                        <x-phosphor-pencil-line-duotone
                            wire:click="setNewMembership({{ $membership->id }})"
                            class="w-5 text-gray-300 hover:text-green-600"/>
                        {{-- alleen als een membership met nog geen users is verbonden mag je ze deleten (DTR) --}}
                        @if(($membership->membershipusers->count() ?? 0) == 0)
                            <x-phosphor-trash-duotone
                                @click="$dispatch('swal:confirm', {
                                title: 'Verwijder {{$membership->name}}?',
                                cancelButtonText: 'Annuleer',
                                confirmButtonText: 'Verwijder de membership',
                                next: {
                                    event: 'delete-membership',
                                    params: {
                                        id: {{ $membership->id }}
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

    {{--Model voor het creeren van een lidmaatschap--}}
        <x-dialog-modal  id="recordModal"
                         wire:model="showModal">
            <x-slot name="title" class="text-center">
                <h2>{{ is_null($newMembership['id']) ? 'Nieuwe lidmaatschap' : 'lidmaatschap bijwerken' }}</h2>
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
                <x-input id="name" type="text" placeholder="Naam" wire:model="newMembership.name" class="w-full"></x-input>
                <x-label for="price" value="Prijs" class="mt-4"></x-label>
                <x-input id="price" type="number" step="0.01" placeholder="Prijs" wire:model="newMembership.price" class="w-full"></x-input>
            </x-slot>
            <x-slot name="footer">
                <x-secondary-button @click="show = false">Annuleer</x-secondary-button>
                @if(is_null($newMembership['id']))
                    <x-button
                        wire:click="createMembership()"
                        wire:loading.attr="disabled"
                        class="ml-2">Maak nieuw lidmaatschap
                    </x-button>
                @else
                    <x-button
                        color="success"
                        wire:click="updateMembership({{ $newMembership['id'] }})"
                        wire:loading.attr="disabled"
                        class="ml-2">Werk lidmaatschap bij
                    </x-button>
                @endif
            </x-slot>
        </x-dialog-modal>
</div>
