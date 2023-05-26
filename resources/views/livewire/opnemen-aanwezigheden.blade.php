<div>
    {{--preloader--}}
    <div class="fixed top-12 left-1/2 -translate-x-1/2 z-50 animate-pulse"
         wire:loading>
        <x-preloader class="bg-green-400/75 text-gray-800 border border-green-700 shadow-2xl">
            Laden...
        </x-preloader>
    </div>

    {{--help model--}}
    <x-help-modal>
        <x-slot name="title">Info aanwezigheden opnemen</x-slot>
        <x-slot name="content">
            <p>Op deze pagina kan je aanwezigheden opnemen</p>
            <ul class="mt-5 ml-2">
                <li>1. Selecteer een rit in de dropdown</li>
                <li>2. Geef voor elke gebruiker aan of ze aanwezig waren op de rit</li>
                <li class="my-1.5">3. Druk op <x-button class="mx-1.5 cursor-default">Bevestig</x-button> om je keuze te bevestigen</li>
            </ul>
            <p class="mt-5">
                Bij een bevestiging krijgen alle leden (inclusief de ritverkenner) punten:
                <br>
                <span class="ml-2">Is dit hun eerste rit van we week?</span>
                <br>
                <span class="ml-4">Ja: +2 punten</span>
                <br>
                <span class="ml-4">Nee: +1 punt</span>
            </p>
            <p class="mt-5 text-blue-900">De aanwezigheid van de ritverkenner moet niet opgenomen worden</p>
        </x-slot>
    </x-help-modal>

    @if(sizeof($tours) > 0)
    {{--filter section--}}
    <x-section class="flex flex-wrap gap-3 mb-3 max-w-2xl mx-auto justify-center">
            <div class="flex gap-3 flex-wrap w-full justify-center">
                {{-- Dropdown tours --}}
                <x-select id="tourSelect" wire:model="selectedTour" class="border border-black rounded-md p-1 px-2 basis-80">
                    <option value="{{ $tours[0]->id }}">{{ $tours[0]->name }} - {{ $tours[0]->date }}</option>
                    @foreach($tours->slice(1) as $tour)
                        <option value="{{ $tour->id }}">{{ $tour->name }} - {{ $tour->date }}</option>
                    @endforeach
                </x-select>
                {{-- bevestig --}}
                <x-button
                    wire:click="confirmPresences()"
                    wire:loading.attr="disabled" data-tippy-content="Bevestig de aanwezigheden">
                    Bevestig</x-button>
            </div>
            {{-- filter naam --}}
            <x-input wire:model.debounce.500ms="name" placeholder="Filter op naam"
                     class="w-96 shadow-md placeholder-gray-300 p-2 border border-black"></x-input>
    </x-section>


    {{--paginate--}}
    <div class="my-4 max-w-2xl justify-center mx-auto">{{ $users->links() }}</div>

    {{--main--}}
    <x-section class="max-w-2xl mx-auto justify-center flex">
        @if(sizeof($users) > 0)
            {{-- Tabel --}}
            <table class="border border-gray-300 bg-white w-full">
                <colgroup>
                    <col class="w-full">
                    <col class="w-20">
                </colgroup>
                <thead>
                <tr class="bg-gray-200 text-gray-700 [&>th]:p-2">
                    <th class="text-start">Naam</th>
                    <th class="text-center">Aanwezig</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr class="border-t border-gray-300 [&>td]:p-2" wire:key="user_{{ $user->id }}">
                        <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                        <td class="flex justify-center">
                            @foreach($user->usertours as $usertour)
                                @if($usertour->tour_id == $selectedTour)
                                    @if($usertour->present)
                                        <x-phosphor-check class="h-8 w-8 cursor-pointer border rounded-md border-gray-400 bg-green-100" wire:click="togglePresent({{ $user->id }})"></x-phosphor-check>
                                    @else
                                        <x-phosphor-x class="h-8 w-8 cursor-pointer border rounded-md border-gray-400" wire:click="togglePresent({{ $user->id }})"></x-phosphor-x>
                                    @endif
                                @endif
                            @endforeach
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <x-alert dismissable="false" type="warning">Geen resultaten voor: <b>'{{ $name }}'</b></x-alert>
        @endif
    </x-section>
        {{--paginate--}}
        <div class="my-4 max-w-2xl justify-center mx-auto">{{ $users->links() }}</div>
    @else
        <x-alert dismissable="false" type="info">Geen ritten om aanwezigheden op te nemen</x-alert>
    @endif

</div>
