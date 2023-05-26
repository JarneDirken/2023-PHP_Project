<div>
    {{--preloader--}}
    <div class="fixed top-12 left-1/2 -translate-x-1/2 z-50 animate-pulse"
         wire:loading>
        <x-preloader class="bg-green-400/75 text-gray-800 border border-green-700 shadow-2xl">
            Laden...
        </x-preloader>
    </div>

    <x-help-modal>
        <x-slot name="title">Info evenementen plannen</x-slot>
        <x-slot name="content">
            <section class="border-b">
                <p>Op deze pagina kan je:</p>
                <ul class="ml-2 mt-2 list-disc">
                    <li class="mb-2">De evenementen beheren</li>
                </ul>
            </section>
            <section class="border-b py-2">
                <p class="font-medium">Bovenaan de pagina kan je:</p>
                <ul class="ml-2 list-disc">
                    <li>
                        Een nieuw evenement plannen door op
                        <x-button color="success" class="m-2">Plan evenement</x-button>
                         te klikken
                    </li>
                    <li>Evenementen filteren op naam, startdatum en einddatum</li>
                </ul>
            </section>
            <section class="mt-2">
                <p class="font-medium">Op de pagina zie je alle evenementen</p>
                <p class="mt-2">Per evenement wordt weergegeven:</p>
                <ul class="mt-2 ml-2 list-disc [&>li]:mb-1">
                    <li>Naam</li>
                    <li>Beschrijving</li>
                    <li>Ingeschreven vrijwilligers en maximum vrijwilligers</li>
                    <li>Namen van vrijwilligers</li>
                    <li>Start- en einddatum</li>
                </ul>
                <p class="mt-3 font-medium">Je kan een evenement:</p>
                <ul class="ml-2 list-disc [&>li]:mb-1">
                    <li>Aanpassen door op
                        <x-button class="text-center m-1">Aanpassen</x-button>
                        te klikken
                    </li>
                    <li>Verwijderen door op
                        <x-danger-button class="m-1">
                            Verwijderen
                        </x-danger-button>
                        te klikken
                    </li>
                </ul>
            </section>
            <section class="border-t mt-2">
                <p class="mt-2 text-blue-900">Een evenement kan niet verwijderd worden als er een gebruiker zich ingeschreven heeft voor dit evenement</p>
            </section>
        </x-slot>
    </x-help-modal>

    <x-section class="mb-4 flex gap-8">
        <x-button wire:click="resetNewEvent" color="success" class="m-4">Plan evenement</x-button>
        <x-section class="mb-4 flex gap-8 bg-gray-200">
        <x-input id="name" type="text"
                     class="block mt-1 w-full"
                     placeholder="Filter evenementen"
                     wire:model="name"/>
        <div class="flex justify-center items-center w-60">
        <x-label class="text-center">vanaf:</x-label>
        </div>
        <x-input id="start_date" type="date"
                 class="block mt-1 w-60"
                 placeholder="Filter evenementen"
                 wire:model="start_date"/>
            <div class="flex justify-center items-center w-60">
        <x-label class="text-center">tot en met:</x-label>
        </div>
        <x-input id="end_date" type="date"
                 class="block mt-1 w-60"
                 placeholder="Filter evenementen"
                 wire:model="end_date"/>
            <x-danger-button wire:click="resetFilters">Reset filters</x-danger-button>
        </x-section>
    </x-section>

    {{--main--}}
    {{--paginate--}}
    <div class="my-4 justify-center mx-auto">{{ $events->links() }}</div>
    @if($events->isEmpty())
        <x-alert dismissable="false" type="warning">Geen resultaten voor: <b>'{{ $name }}'</b></x-alert>
    @else
    <x-section>
        <div class="grid gap-4 grid-cols-2">
        @foreach($events as $event)
            <div wire:key="event_{{ $event->id }}" class="w-full p-6 bg-gray-100 shadow-md grid grid-cols-3 gap-4">
                <div class="col-span-2 bold text-2xl">
                    <b>{{ $event->name }}</b>
                </div>
                <div class="col-span-1 text-2xl bold text-right">Vrijwilligers: <b>{{ $event->users_count }}/{{ $event->max_volunteer }}</b></div>
                <div class="col-span-2 row-span-2"><b>Beschrijving:</b> <br>{{ $event->description }}</div>
                <div>vanaf: <b>{{ $event->start_date }}</b></div>

                <div>tot: <b>{{ $event->end_date }}</b></div>
                <div class="col-span-3 text-center text-lg flex justify-center">
                    <x-phosphor-map-pin-bold class="w-6"></x-phosphor-map-pin-bold> {{ $event->location }}
                </div>
                <div class="bg-gray-50 w-full p-5 col-span-3 rounded-l shadow-md">
                    <b>Vrijwilligers:</b><br>
                    @foreach ($event->users as $user)
                        {{ $user->first_name }} {{ $user->last_name }} |
                    @endforeach
                </div>
                <div x-data="" class="flex justify-between w-full col-span-3"><x-button class="text-center" wire:click="setNewEvent({{ $event->id }})">Aanpassen</x-button>
                    {{-- alleen als een event met nog geen users is verbonden mag je ze deleten (DTR) --}}
                    @if(($event->userevents->count() ?? 0) == 0)
                        <x-danger-button @click="$dispatch('swal:confirm', {
                                    title: 'Verwijder evenement?',
                                    cancelButtonText: 'Annuleer',
                                    confirmButtonText: 'Verwijder het evenement',
                                    next: {
                                        event: 'delete-event',
                                        params: {
                                            id: {{ $event->id }}
                                        }
                                    }
                                });">
                            Verwijderen
                        </x-danger-button>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </x-section>
    @endif
    {{--paginate--}}
    <div class="my-4 justify-center mx-auto">{{ $events->links() }}</div>


    {{--popup for creating / editing--}}
    <x-dialog-modal id="eventModal" wire:model="showModal">
        <x-slot name="title">
            <h2>Evenement</h2>
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
            <div class="grid grid-cols-12 gap-2">
                <div class="col-span-6">
                    <x-label>Evenement naam:</x-label>
                    <x-input id="newEventName" type="text" placeholder="Naam"
                             wire:model.defer="newEvent.name"
                             class="p-2 w-full shadow-md placeholder-gray-300" required/>
                </div>
                <div class="col-span-6">
                    <x-label>Evenement plaats:</x-label>
                    <x-input id="newEventLocation" type="text" placeholder="Plaats"
                             wire:model.defer="newEvent.location"
                             class="p-2 w-full shadow-md placeholder-gray-300" required/>
                </div>
                <div class="col-span-12" wire:key="event-description">
                    <x-label>Evenement beschrijving:</x-label>
                    <x-textarea id="newEventDescription" placeholder="Beschrijving"
                                wire:model.defer="newEvent.description"
                                class="rounded-md p-2 w-full resize-none shadow-md placeholder-gray-300" required/>
                </div>
                <div class="col-span-4">
                    <x-label>Evenement begin:</x-label>
                    <x-input id="newEventStartDate" type="date" placeholder="Naam"
                             wire:model.defer="newEvent.start_date"
                             class="p-2 w-full shadow-md placeholder-gray-300" required/>
                </div>
                <div class="col-span-4">
                    <x-label>Evenement einde:</x-label>
                    <x-input id="newEventEndDate" type="date" placeholder="Plaats"
                             wire:model.defer="newEvent.end_date"
                             class="p-2 w-full shadow-md placeholder-gray-300" required/>
                </div>
                <div class="col-span-4">
                    <x-label>Max vrijwilligers:</x-label>
                    <x-input id="newEventMaxVolunteer" type="text" placeholder="Aantal"
                             wire:model.defer="newEvent.max_volunteer"
                             class="p-2 w-full shadow-md placeholder-gray-300" required/>
                </div>
            </div>
        </x-slot>
        <x-slot name="footer" class="flex gap-2">
            <x-secondary-button @click="show = false">Annuleer</x-secondary-button>

            @if ($newEvent && !is_null($newEvent['id']))
                <x-button
                    color="success"
                    wire:click="updateEvent('{{ $newEvent['id'] }}')"
                    wire:loading.attr="disabled"
                    class="ml-2">Aanpassen
                </x-button>
            @else
                    <x-button
                        color="success"
                        wire:click="createEvent()"
                        wire:loading.attr="disabled"
                        class="ml-2">Plan
                    </x-button>
        @endif

        </x-slot>
    </x-dialog-modal>
</div>
