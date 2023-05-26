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
        <x-slot name="title">Info kortingsbonnen beheren</x-slot>
        <x-slot name="content">
            <section class="border-b">
                <p>Op deze pagina kan je je:</p>
                <ul class="ml-2 mt-2 list-disc">
                    <li class="mb-2">De kortingsbonnen beheren</li>
                </ul>
            </section>
            <section class="mt-2 border-b">
                <p class="font-medium">Bovenaan kun je filteren en toevoegen</p>
                <p class="mt-2">Hierin kan je:</p>
                <ul class="mt-2 ml-2 list-disc [&>li]:mb-2">
                    <li>Een nieuwe kortingsbon toevoegen door op de knop
                        <x-button class="mb-3">
                            Nieuwe kortingsbon
                        </x-button>
                        te klikken.
                    </li>
                </ul>
            </section>
            <section class="mt-2 border-b">
                <p class="font-medium">Als je de knop
                    <x-button class="mb-3">
                        Nieuwe kortingsbon
                    </x-button> aanklikt.</p>
                <p class="mt-2">Hierin kan je:</p>
                <ul class="mt-2 ml-2 list-disc [&>li]:mb-2">
                    <li>
                        De prijs van de korting, aantal punten dat nodig is en of de kortingsbon actief is ja of nee.
                    </li>
                    <li>Een nieuwe kortingsbon te maken door op
                        <x-button class="mb-3">
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
                            Pas een kortingsbon aan
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center">
                            <x-phosphor-trash-duotone class="hover:text-red-900 h-8 w-8 inline"></x-phosphor-trash-duotone>
                        </td>
                        <td>
                            Verwijder een kortingsbon
                        </td>
                    </tr>
                    </tbody>
                </table>
            </section>
            <section class="border-t mt-2">
                <p class="mt-5 text-blue-900">Een kortingsbon mag niet verwijderd worden als een order deze gebruikt</p>
                <p class="mt-2 text-blue-900">Je kan wel een kortingsbon op inactief zetten om ze uit de lijst van bonnen te halen</p>
            </section>
        </x-slot>
    </x-help-modal>

    {{-- filter section --}}
    <x-section class="flex gap-3 flex-wrap mb-3 max-w-2xl mx-auto justify-center">
        <div class="flex justify-center items-center">
            <x-button wire:click="showCoupon()"
                      class="mb-1"
                      data-tippy-content="Nieuwe kortingsbon">
                Nieuwe kortingsbon
            </x-button>
        </div>
    </x-section>

    {{-- main section --}}
    <x-section class="max-w-2xl mx-auto flex justify-center">
        <table class="text-center w-full border border-gray-300">
            <colgroup>
                <col class="w-40">
                <col class="w-40">
                <col class="w-40">
                <col class="w-max">
            </colgroup>
            <thead>
            <tr class="bg-gray-100 text-gray-700 [&>th]:p-2">
                <th>
                    <span>Euro</span>
                </th>
                <th>
                    <span>Punten</span>
                </th>
                <th>
                    <span>Actief</span>
                </th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($coupons as $coupon)
                <tr wire:key="coupon_{{ $coupon->id }}"
                    class="border-t border-gray-300 [&>td]:p-2">
                    <td>&#8364;{{ $coupon->amount_euro }}</td>
                    <td>{{ $coupon->amount_point }}</td>
                    @if ($coupon->active)
                        <td>ja</td>
                    @else
                        <td>nee</td>
                    @endif
                    <td>
                        <div class="flex gap-1 justify-center [&>*]:cursor-pointer [&>*]:outline-0 [&>*]:transition">
                            <button
                                wire:click="setNewCoupon({{ $coupon->id }})"
                                class="w-5 text-gray-300 hover:text-green-600">
                                <x-phosphor-pencil-line-duotone class="inline-block w-5 h-5"/>
                            </button>
                            {{-- alleen als een coupon met nog geen orders is verbonden mag je ze deleten (DTR) --}}
                            @if(($coupon->orders->count() ?? 0) == 0)
                            <button
                                x-data=""
                                @click="$dispatch('swal:confirm', {
                                        title: 'Ben je zeker dat je deze kortingsbon wilt verwijderen?',
                                        cancelButtonText: 'Nee',
                                        confirmButtonText: 'Ja, verwijder deze kortingsbon',
                                        next: {
                                            event: 'delete-coupon',
                                            params: {
                                                id: {{ $coupon->id }}
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
            @endforeach
            </tbody>
        </table>
    </x-section>

    {{--Model voor artikel aan te maken--}}
    <x-dialog-modal  id="articleModal"
                     wire:model="showModal">
        <x-slot name="title">
            <h2>{{ is_null($newCoupon['id']) ? 'Nieuwe kortingsbon' : 'Kortingsbon aanpassen' }}</h2>
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
                    <x-label for="name" value="Euro" class="mt-4"></x-label>
                    <x-input id="name" type="number" placeholder="Euro" wire:model="newCoupon.amount_euro" class="w-full"></x-input>
                    <x-label for="stock" value="Punten" class="mt-4"></x-label>
                    <x-input id="stock" type="number" placeholder="Punten" wire:model="newCoupon.amount_point" class="mt-1 block w-full"></x-input>
                    <x-label for="checkbox" value="Actief" class="mt-4"></x-label>
                    <x-checkbox id="checkbox" type="checkbox" wire:model="newCoupon.active" class="w-4"></x-checkbox>
                </div>
            </div>
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button @click="show = false">Annuleer</x-secondary-button>
            @if(is_null($newCoupon['id']))
                <x-button
                    wire:click="createCoupon()"
                    wire:loading.attr="disabled"
                    class="ml-2">Voeg toe
                </x-button>
            @else
                <x-button
                    color="success"
                    wire:click="updateCoupon({{ $newCoupon['id'] }})"
                    wire:loading.attr="disabled"
                    class="ml-2">Artikel aanpassen
                </x-button>
            @endif
        </x-slot>
    </x-dialog-modal>
</div>
