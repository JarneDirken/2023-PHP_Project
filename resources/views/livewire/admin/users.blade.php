<div x-data="">
    {{-- preloader section --}}
    <div class="fixed top-12 left-1/2 -translate-x-1/2 z-50 animate-pulse"
         wire:loading>
        <x-preloader class="bg-green-400/75 text-gray-800 border border-green-700 shadow-2xl">
            Laden...
        </x-preloader>
    </div>

    {{-- help icon section --}}
    <x-help-modal>
        <x-slot name="title">Info gebruikers beheren</x-slot>
        <x-slot name="content">
            <section class="border-b">
                <p>Op deze pagina kan je:</p>
                <ul class="ml-2 mt-2 list-disc">
                    <li class="mb-2">De gebruikers beheren</li>
                </ul>
            </section>
            <section class="border-b py-2">
                <p>Bovenaan de pagina kan je:</p>
                <ul class="ml-2 list-disc">
                    <li class="my-2">
                        Maak een nieuwe gebruiker door op
                        <x-button>
                            Nieuwe gebruiker
                        </x-button> te klikken
                    </li>
                    <li>Gebruikers filteren op naam, adminstatus, activiteitsstatus of verzekeringsstatus</li>
                </ul>
            </section>
            <section class="mt-2 py-2">
                <p class="mb-2">Legende symbolen</p>
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
                                Pas een gebruiker aan
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center">
                                <x-phosphor-trash-duotone class="hover:text-red-900 h-8 w-8 inline"></x-phosphor-trash-duotone>
                            </td>
                            <td>
                                Verwijder een gebruiker (of zet deze inactief)
                            </td>
                        </tr>
                        <tr class="bg-gray-200">
                            <td>Symbolen status</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="flex justify-center"><x-phosphor-lock-simple-open class="h-8 w-8 bg-green-200 p-0.5 rounded-full"></x-phosphor-lock-simple-open></td>
                            <td>Gebruiker is actief</td>
                        </tr>
                        <tr>
                            <td class="flex justify-center"><x-phosphor-lock-simple class="h-8 w-8 bg-red-200 p-0.5 rounded-full"></x-phosphor-lock-simple></td>
                            <td>Gebruiker is inactief</td>
                        </tr>
                        <tr>
                            <td class="flex justify-center"><x-phosphor-user-circle-gear class="h-8 w-8 bg-blue-200 p-0.5 rounded-full"></x-phosphor-user-circle-gear></td>
                            <td>Gebruiker is een administrator</td>
                        </tr>
                        <tr class="bg-gray-200">
                            <td>Symbolen lidmaatschap</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="text-center"><x-phosphor-check-circle class="h-8 w-8 inline bg-green-200 rounded-full"></x-phosphor-check-circle></td>
                            <td>Gebruiker heeft een geldig standaard lidmaatschap </td>
                        </tr>
                        <tr>
                            <td class="text-center"><x-phosphor-check-circle class="h-8 w-8 inline bg-blue-200 rounded-full"></x-phosphor-check-circle></td>
                            <td>Gebruiker heeft een ander geldig lidmaatschap (b.v. WBV)</td>
                        </tr>
                        <tr>
                            <td class="text-center"><x-phosphor-x-circle class="h-8 w-8 inline bg-red-200 rounded-full"></x-phosphor-x-circle></td>
                            <td>Gebruiker heeft geen geldig lidmaatschap </td>
                        </tr>
                        <tr class="bg-gray-200">
                            <td>Symbolen gezinshoofd</td>
                            <td class="text-sm">Soms heeft een gebruiker een gezinshoofd opgelijst, deze heeft een verbonden-status</td>
                        </tr>
                        <tr>
                            <td class="text-center"><x-phosphor-check-circle class="h-8 w-8 inline bg-green-200 rounded-full"></x-phosphor-check-circle></td>
                            <td>Gebruiker is verbonden met hun gezinshoofd</td>
                        </tr>
                        <tr>
                            <td class="text-center">
                                <x-phosphor-question class="h-8 w-8 inline align-middle bg-orange-200 rounded-full"></x-phosphor-question>
                            </td>
                            <td>Gebruiker is nog niet verbonden met hun gezinshoofd (dit moet manueel door een admin gebeuren)</td>
                        </tr>
                        <tr class="bg-gray-200">
                            <td>Symbolen verzekering</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="text-center"><x-phosphor-check-circle class="h-8 w-8 inline bg-green-200 rounded-full"></x-phosphor-check-circle></td>
                            <td>Gebruiker heeft een geldige verzekering</td>
                        </tr>
                        <tr>
                            <td class="text-center"><x-phosphor-x-circle class="h-8 w-8 inline bg-red-200 rounded-full"></x-phosphor-x-circle></td>
                            <td>Gebruiker heeft geen (geldige) verzekering</td>
                        </tr>
                    </tbody>
                </table>
            </section>
            <section class="border-t py-2">
                <p class="mt-2 text-blue-900">Een gebruiker kan verbonden worden met hun gezinshoofd in het bewerkmenu</p>
                <p class="mt-2 text-blue-900">Je kan jouw account niet aanpassen of inactief zetten</p>
                <p class="mt-2 text-blue-900">Je kan een gebruiker niet verwijderen als ze verbonden zijn in de database</p>
            </section>
        </x-slot>
    </x-help-modal>

    {{-- filter section --}}
    <x-section class="flex gap-3 flex-wrap">
        <div class="flex flex-wrap gap-3 flex-grow">
            <x-button
                wire:click="setNewUser()">
                Nieuwe gebruiker
            </x-button>
            <x-input id="search" type="text" placeholder="Filter op naam / naam gezinshoofd"
                     wire:model.debounce.500ms="search"
                     class="shadow-md placeholder-gray-300 px-2 border border-black basis-1/2 flex-grow lg:flex-grow-0"/>
            <x-switch id="admin"
                      wire:model="admin"
                      text-off="Admin"
                      color-off="bg-gray-100 before:line-through"
                      text-on="Admin"
                      color-on="bg-blue-300"
                      class="mt-1 col-span-2 w-32"
                      data-tippy-content="Filter op adminstatus"/>
            <div class="relative">
                <x-label for="status" class="absolute -top-3">Status</x-label>
                <x-select id="status" wire:model="status" class="border border-black mt-2 rounded-md p-1 w-32">
                    <option value="All" selected>Alle</option>
                    <option value="Active">Actief</option>
                    <option value="Inactive">Inactief</option>
                </x-select>
            </div>
            <div class="relative">
                <x-label for="insurance" class="absolute -top-3">Verzekering</x-label>
                <x-select id="insurance" wire:model="insurance" class="border border-black mt-2 rounded-md p-1 w-32">
                    <option value="All" selected>Alle</option>
                    <option value="Yes">Ja</option>
                    <option value="No">Nee</option>
                </x-select>
            </div>
        </div>
        @if($search || $insurance != "All" || $status != "All" || $admin)
            <div class="w-10 h-10 border-gray-300 border p-2 rounded-full flex items-center justify-center cursor-pointer hover:bg-gray-100"
                 wire:click="resetFilter()"
                 data-tippy-content="Filters verwijderen">
                <x-phosphor-x class="w-5 h-5"></x-phosphor-x>
            </div>
        @endif
    </x-section>

    {{-- main section --}}
    <div class="my-4">{{ $users->links() }}</div>
    @if($users->isEmpty())
        <x-alert dismissable="false" type="warning">
            @if($search)
                Geen resultaten voor: <b>'{{ $search }}'</b>
            @else
                Geen gebruikers gevonden
            @endif
        </x-alert>
    @else
    <x-section>
        <div class="grid grid-cols-12 gap-5">
        @foreach($users as $user)

            <div class="bg-gray-100 border border-black rounded shadow p-5 pb-3 lg:col-span-6 xl:col-span-4 col-span-12"
                 wire:key="user_{{ $user->id }}">
                <h1 class="flex justify-between font-bold text-2xl mb-4 py-1 border-b border-black">
                    <div class="flex gap-2 items-center">
                        {{ $user->first_name }} {{ $user->last_name }}
                        @if($user->gender_id == 1)
                            <x-phosphor-gender-male class="h-6 w-6"></x-phosphor-gender-male>
                        @elseif($user->gender_id == 2)
                            <x-phosphor-gender-female class="h-6 w-6"></x-phosphor-gender-female>
                        @else
                            <x-phosphor-gender-nonbinary class="h-6 w-6"></x-phosphor-gender-nonbinary>
                        @endif
                    </div>
                    <div>
                        @if( Auth::user()->id != $user->id )
                            <button wire:click="setNewUser({{ $user->id }})">
                                <x-phosphor-pencil-duotone class="hover:text-blue-900 h-8 w-8"></x-phosphor-pencil-duotone>
                            </button>
                            <button>
                                <x-phosphor-trash-duotone class="hover:text-red-900 h-8 w-8"
                                                          @click="$dispatch('swal:confirm', {
                                                        title: 'Verwijder {{ $user->first_name }} {{ $user->last_name }}?',
                                                        icon: '{{ $user->amount_connection > 0 ? 'warning' : '' }}',
                                                        background: '{{ $user->amount_connection > 0 ? 'error' : '' }}',
                                                        cancelButtonText: 'Nee',
                                                        confirmButtonText: '{{ $user->amount_connection > 0 ? 'Zet gebruiker inactief' : 'Verwijder gebruiker' }}',
                                                        html: '{{ $user->amount_connection > 0 ? '<b>OPGELET</b>: ' . $user->first_name . ' heeft <b>' . $user->amount_connection . '</b> verbindingen in het systeem!' :'' }}',
                                                        color: '{{ $user->amount_connection > 0 ? 'red' : '' }}',
                                                        next: {
                                                            event: '{{ $user->amount_connection > 0 ? 'inactive-user' : 'delete-user' }}',
                                                            params: {
                                                                id: {{ $user->id }}
                                                            }
                                                        }
                                                    });"
                                ></x-phosphor-trash-duotone>
                            </button>
                        @endif
                    </div>
                </h1>
                <div class="grid grid-rows-1 gap-3">
                    <div class="row-span-2 flex justify-between">
                        <div class="flex-grow">
                            <h2 class="font-bold text-lg">Contact</h2>
                            <p>{{ $user->first_name }} {{ $user->last_name }}</p>
                            <p>{{ $user->email }}</p>
                            <p>{{ $user->phone }}</p>
                        </div>
                        <div>
                            <h2 class="font-bold mb-1 text-lg text-center">Status</h2>
                            <div class="flex justify-end gap-0.5">
                                @if($user->active)
                                    {{--<p class="bg-green-400 w-32 text-center py-1 px-4 rounded-md mb-1.5">Actief</p>--}}
                                    <x-phosphor-lock-simple-open class="h-8 w-8 bg-green-200 p-0.5 rounded-full"
                                                                 data-tippy-content="Actief"></x-phosphor-lock-simple-open>
                                @else
                                    {{--<p class="bg-red-400 w-32 text-center py-1 px-4 rounded-md mb-1.5">Inactief</p>--}}
                                    <x-phosphor-lock-simple class="h-8 w-8 bg-red-200 p-0.5 rounded-full"
                                                            data-tippy-content="Inactief"></x-phosphor-lock-simple>
                                @endif
                                @if($user->management)
                                    {{--<p class="bg-blue-300 w-32 text-center py-1 px-4 rounded-md">Admin</p>--}}
                                    <x-phosphor-user-circle-gear class="h-8 w-8 bg-blue-200 p-0.5 rounded-full"
                                                                 data-tippy-content="Administrator"></x-phosphor-user-circle-gear>
                                @endif
                            </div>

                        </div>
                    </div>
                    <div class="row-span-2 flex justify-between">
                        <div class="flex-grow">
                            <h2 class="font-bold text-lg">Adres</h2>
                            <p>{{ $user->city }} {{ $user->postal_code }}</p>
                            <p>{{ $user->street }} {{ $user->house_number }}</p>
                        </div>
                        <div>
                            <h2 class="font-bold text-lg mb-1">Lidmaatschap</h2>
                            <div class="flex justify-end">
                                @if( !$user->current_membership )
                                    {{--<p class="bg-red-400 w-32 text-center py-1 px-4 rounded-md">Geen</p>--}}
                                    <x-phosphor-x-circle class="h-8 w-8 inline bg-red-200 rounded-full"
                                                         data-tippy-content="Geen lidmaatschap"></x-phosphor-x-circle>
                                @elseif( $user->current_membership->id == 1 )
                                    {{--<p class="bg-green-400 w-32 text-center py-1 px-4 rounded-md">{{ $user->current_membership->name }}</p>--}}
                                    <x-phosphor-check-circle class="h-8 w-8 inline bg-green-200 rounded-full"
                                                             data-tippy-content="{{ $user->current_membership->name }}"></x-phosphor-check-circle>
                                @else
                                    {{--<p class="bg-blue-300 w-32 text-center py-1 px-4 rounded-md">{{ $user->current_membership->name }}</p>--}}
                                    <x-phosphor-check-circle class="h-8 w-8 inline bg-blue-200 rounded-full"
                                                             data-tippy-content="{{ $user->current_membership->name }}"></x-phosphor-check-circle>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row-span-1 flex justify-between">
                        <div class="flex-grow">
                            @if( !is_null($user->householder_name) )
                                <h2 class="font-bold text-lg">Gezinshoofd</h2>
                                <p>
                                    @isset( $user->user_id )
                                        <x-phosphor-check-circle class="h-8 w-8 inline align-middle bg-green-200 rounded-full"
                                                                 data-tippy-content="Het gezinshoofd is verbonden"></x-phosphor-check-circle>
                                    @else
                                        <x-phosphor-question class="h-8 w-8 inline align-middle bg-orange-200 rounded-full"
                                                             data-tippy-content="Het gezinshoofd is aangevraagd maar nog niet verbonden"></x-phosphor-question>
                                    @endisset
                                    <span class="align-middle">{{ $user->householder_name }}</span>
                                </p>
                            @endif
                        </div>
                        <div>
                            <h2 class="font-bold mb-1 text-lg">Verzekering</h2>
                            <div class="flex justify-end">
                                {{-- heeft gebruiker verzekering? of heeft gebruiker een (actieve) ouder met verzekering --}}
                                @if( $user->actual_wbv_insurance )
                                    {{--<p class="bg-green-400 w-32 text-center py-1 px-4 rounded-md">Ja</p>--}}
                                    <x-phosphor-check-circle class="h-8 w-8 inline bg-green-200 rounded-full"
                                                             data-tippy-content="Wel WBV verzekering"></x-phosphor-check-circle>
                                @else
                                    {{--<p class="bg-red-400 w-32 text-center py-1 px-4 rounded-md">Nee</p>--}}
                                    <x-phosphor-x-circle class="h-8 w-8 inline bg-red-200 rounded-full"
                                                         data-tippy-content="Geen WBV verzekering"></x-phosphor-x-circle>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row-span-1 flex justify-between border-t border-black">
                        <div>
                            <h2 class="font-bold my-1 text-lg">Punten</h2>
                            <div class="flex gap-3">
                                <p>
                                    {{-- hier moeten nog de minpunten afgetrokken worden --}}
                                    Totaal: <span class="font-medium">{{ $user->pointusers->sum('points') }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        </div>
    </x-section>
    @endif
    <div class="my-4">{{ $users->links() }}</div>

    {{--Model voor het aanmaken van een user --}}
    <x-dialog-modal id="userModal"
                        wire:model="showModal">
        <x-slot name="title">
            @if(is_null($newUser['id']))
                <h2>Nieuwe gebruiker</h2>
            @else
                <h2>Bewerk gebruiker</h2>
            @endif

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

            <div class="flex gap-x-3">
                <div class="flex-auto">
                    <x-label for="first_name" value="{{ __('Voornaam') }}" />
                    <x-input id="first_name" class="block mt-1 w-full p-2 border border-black" type="text" name="first_name" required
                             wire:model.defer="newUser.first_name"/>
                </div>

                <div class="flex-auto">
                    <x-label for="last_name" value="{{ __('Achternaam') }}" />
                    <x-input id="last_name" class="block mt-1 w-full p-2 border border-black" type="text" name="last_name" :value="old('last_name')" required
                             wire:model.defer="newUser.last_name"/>
                </div>
            </div>

            <div class="flex gap-x-3 mt-4">
                <div class="flex-auto w-4/12">
                    <x-label for="city" value="{{ __('Gemeente') }}" />
                    <x-input id="city" class="block mt-1 w-full p-2 border border-black" type="text" name="city" :value="old('city')" required
                             wire:model.defer="newUser.city"/>
                </div>

                <div class="flex-auto w-2/12">
                    <x-label for="postal_code" value="{{ __('Postcode') }}" />
                    <x-input id="postal_code" class="block mt-1 w-full p-2 border border-black" type="text" name="postal_code" :value="old('postal_code')" required
                             wire:model.defer="newUser.postal_code"/>
                </div>

                <div class="flex-auto w-4/12">
                    <x-label for="street" value="{{ __('Straat') }}" />
                    <x-input id="street" class="block mt-1 w-full p-2 border border-black" type="text" name="street" :value="old('street')" required
                             wire:model.defer="newUser.street"/>
                </div>

                <div class="flex-auto w-2/12">
                    <x-label for="house_number" value="{{ __('Huisnummer') }}" />
                    <x-input id="house_number" class="block mt-1 w-full p-2 border border-black" type="text" name="house_number" :value="old('house_number')" required
                             wire:model.defer="newUser.house_number"/>
                </div>
            </div>

            <div class="flex gap-3 mt-4">
                <div class="flex-auto w-8/12">
                    <x-label for="email" value="{{ __('Email') }}" />
                    <x-input id="email" class="block mt-1 w-full p-2 border border-black" type="email" name="email" :value="old('email')" required
                             wire:model.defer="newUser.email"/>
                </div>

                <div class="flex-auto w-4/12">
                    <x-label for="phone" value="{{ __('Telefoonnummer') }}" />
                    <x-input id="phone" class="block mt-1 w-full p-2 border border-black" type="text" name="phone" :value="old('phone')" required
                             wire:model.defer="newUser.phone"/>
                </div>
            </div>
            <div class="flex gap-3 mt-4">
                <div class="flex-auto w-6/12 flex flex-col">
                    <x-label for="gender" value="{{ __('Geslacht') }}" />
                    <x-select id="gender" wire:model.defer="newUser.gender_id" class="border border-black mt-1 rounded-md p-2">
                        @foreach($genders as $gender)
                            <option value="{{ $gender->id }}">{{ $gender->name }}</option>
                        @endforeach
                    </x-select>
                </div>
                <div class="flex-auto w-6/12 mt-8 flex justify-around">
                    <label class="font-medium text-sm text-gray-700">
                        <x-input type="checkbox" name="admin" value="1" wire:model.defer="newUser.management" />
                        Admin
                    </label>
                    <label class="font-medium text-sm text-gray-700">
                        <x-input type="checkbox" name="active" value="1" wire:model.defer="newUser.active" />
                        Actief
                    </label>
                </div>
            </div>
                <div class="flex gap-3 mt-4">
                    <div class="flex-auto w-6/12">
                        <x-label for="householder_name" value="{{ __('Naam gezinshoofd') }}" />
                        <x-input id="householder_name" class="block mt-1 w-full p-2 border border-black" type="text" name="householder_name"
                                 wire:model.defer="newUser.householder_name"/>
                    </div>
                    <div class="flex-auto w-6/12 flex flex-col">
                        <x-label for="gender" value="{{ __('Gezinshoofd') }}" />
                        <x-select id="gender" wire:model.defer="newUser.user_id" class="border border-black mt-1 rounded-md p-2">
                            <option value="0" selected>Geen gezinshoofd</option>
                            @foreach($allUsers as $user)
                                <option value="{{ $user->id }}" selected>{{ $user->first_name }} {{ $user->last_name }}</option>
                            @endforeach
                        </x-select>
                    </div>
                </div>
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button @click="show = false">Annuleer</x-secondary-button>
            @if(is_null($newUser['id']))
                <x-button
                    color="success"
                    wire:click="createUser()"
                    wire:loading.attr="disabled"
                    class="ml-2">Maak gebruiker
                </x-button>
            @else
                <x-button
                    color="success"
                    wire:click="updateUser({{ $newUser['id'] }})"
                    wire:loading.attr="disabled"
                    class="ml-2">Update gebruiker
                </x-button>
            @endif
        </x-slot>
    </x-dialog-modal>

</div>
