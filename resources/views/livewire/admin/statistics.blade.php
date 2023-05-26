<div x-data="">
    <x-help-modal>
        <x-slot name="title">Info statistieken pagina</x-slot>
        <x-slot name="content">
            <section class="border-b">
                <p class="font-medium">Op deze pagina kan je volgende statistieken bekijken:</p>
                <ul class="ml-2 mt-2 list-disc">
                    <li class="mb-2">Aantal aanwezigen per rit</li>
                </ul>
            </section>
            <section class="mt-2 border-b">
                <p class="font-medium">Op de grafiek zie je de hoeveelheid aanwezigheden per rit</p>
                <p class="mt-2">Hierop kan je:</p>
                <ul class="mt-2 ml-2 list-disc [&>li]:mb-2">
                    <li>Filters toepassen zoals de volgorde, een seizoen kiezen, een ploeg kiezen en zoeken op naam</li>
                    <li>Over een bar hoveren om de naam van de rit te zien</li>
                    <li>Op een bar klikken om een gedetailleerd overzicht van de rit te zien</li>
                </ul>
            </section>
            <section class="mt-2">
                <p class="font-medium">Als je rit gekozen hebt krijg je een gedetailleerd overzicht</p>
                <p class="mt-2">Hierop zie je:</p>
                <ul class="mt-2 ml-2 list-disc [&>li]:mb-2">
                    <li>De naam, datum en ploeg van de rit</li>
                    <li>De lijst van deelnemers</li>
                    <li>Het percentage van het aantal deelnemers in vergelijking met het totaal aantal gebruikers</li>
                </ul>
            </section>
        </x-slot>
    </x-help-modal>
    <x-section class="flex gap-3 justify-between flex-wrap">
        <div class="flex gap-3 flex-wrap">
            {{-- switch voor asc of desc --}}
            <x-switch id="order"
                      wire:model="asc"
                      text-off="Aflopend"
                      color-off="bg-slate-200"
                      text-on="Oplopend"
                      color-on="bg-slate-200"
                      class="mt-1 col-span-2 w-max"
                      data-tippy-content="Verander volgorde"/>
            {{-- filter op seizoen --}}
            <x-select id="seasonSearch" wire:model="selectedSeasonId" class="border border-black rounded-md w-60 px-2">
                <option value="0">Alle seizoenen</option>
                @foreach($seasons as $season)
                    <option value="{{ $season->id }}">
                        {{ $season->start_date }} - {{ $season->end_date }}
                    </option>
                @endforeach
            </x-select>
            {{-- filter op team --}}
            <x-select id="teamSearch" wire:model="selectedTeamId" class="border border-black rounded-md w-60 px-2">
                <option value="0">Alle ploegen</option>
                @foreach($teams as $team)
                    <option value="{{ $team->id }}">
                        {{ $team->name }}
                    </option>
                @endforeach
            </x-select>
            {{-- filter op naam --}}
            <x-input id="search" type="text" placeholder="Zoek op naam"
                     wire:model.debounce.500ms="search"
                     class="shadow-md placeholder-gray-400 px-2 border border-black"/>
        </div>
        @if($search || $selectedSeasonId || $selectedTeamId || $asc)
            <div class="w-10 border-gray-300 border p-2 rounded-full flex items-center justify-center cursor-pointer hover:bg-gray-100"
                 wire:click="resetFilter()"
                 data-tippy-content="Filters verwijderen">
                <x-phosphor-x class="w-5 h-5"></x-phosphor-x>
            </div>
        @endif
    </x-section>
    @if(sizeof($tours) > 0)
    <section class="flex flex-wrap gap-8 mt-8">
        <div class="bg-white p-8 w-max border border-gray-300 rounded shadow-md overflow-auto">
            <div class="flex justify-between gap-2 mb-4">
                <h2 class="text-lg font-bold h-10 overflow-hidden">Aantal aanwezigen per rit</h2>
                @if(isset($selectedTour['id']))
                    <div class="w-10 h-10 -translate-y-2 border-gray-300 border p-2 rounded-full flex items-center justify-center cursor-pointer hover:bg-gray-100"
                         wire:click="hideInfo()"
                         data-tippy-content="Selectie verwijderen">
                        <x-phosphor-x class="w-5 h-5"></x-phosphor-x>
                    </div>
                @endif
            </div>
            <div class="flex relative items-end border-b-2 border-l-2 border-gray-700"
                 style="height: calc({{(int) $maxGraph}} * {{$heightMultiplier}}px + 4px); width: calc({{sizeof($tours)}} * {{ $widthColumn + $spacingColumn }}px + {{$spacingColumn}}px); min-width: 15rem">
                {{-- de rijen in de achtergrond maken --}}
                @for($x = 1; $x <= $maxGraph / 5; $x++)
                    <div class="border w-full absolute" style="bottom: calc({{$x}} * {{ 5 * $heightMultiplier }}px)"></div>
                    <div class="absolute w-6 text-right" style="bottom: calc({{$x}} * {{ 5 * $heightMultiplier }}px - 9px); left: -30px">{{ $x * 5 }}</div>
                @endfor
                @foreach($tours as $tour)
                    <div class="relative" style="width: {{$widthColumn}}px; margin-left: {{ $spacingColumn }}px" wire:key="tour_{{$tour->id}}">
                        <div class="z-10 cursor-pointer hover:border-2 hover:border-black hover:border-b-0 @if($selectedTour['id'] == null || $selectedTour['id'] == $tour->id) bg-indigo-700 @else bg-indigo-300 @endif"
                             style="height: calc({{(int) $tour->present_amount}} * {{$heightMultiplier}}px);"
                             data-tippy-content="{{$tour->name}}" data-tippy-placement="bottom"
                             @if($selectedTour['id'] == $tour->id)
                                 wire:click="hideInfo()"
                             @else
                                 wire:click="showInfo({{$tour->id}})"
                             @endif
                             ></div>
                        <div class="absolute -top-6 left-1/2 -translate-x-1/2 font-medium">{{ $tour->present_amount }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        @if(!is_null($selectedTour['id']))
            <div class="flex-grow align-top basis-64 p-8 bg-white border border-gray-300 rounded shadow-md">
                <h2 class="text-lg"><span class="font-bold">{{ $selectedTour['name'] }}</span> {{ $selectedTour['date'] }}</h2>
                <p class="mb-4">{{ $selectedTour['team']['name'] }}</p>
                <div class="flex gap-4 flex-col">
                    <div>
                        <p class="text-md font-medium mb-2">Deelnemers ({{ $selectedTour['present_amount'] }}):</p>
                        <ul class="list list-disc ml-4">
                            @foreach($selectedTour['usertours'] as $usertour)
                                <li>{{ $usertour['user']['first_name'] }} {{ $usertour['user']['last_name'] }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <div>
                        <div class="border-2 border-black relative bg-gray-100" style="height: 40px; width: 260px">
                            <div class="absolute inset-0 bg-indigo-700" style="height: 37px; width: {{ $selectedTour['present_amount'] / $selectedTour['participating_users'] * 100}}%"
                            data-tippy-content="{{ $selectedTour['present_amount'] }}"></div>
                            <p class="absolute -right-6 top-1.5" data-tippy-content="Aantal gebruikers toen">{{ $selectedTour['participating_users'] }}</p>
                        </div>
                        <p class="text-sm">{{ round($selectedTour['present_amount'] / $selectedTour['participating_users'] * 100, 2) }}% van toen alle gebruikers was aanwezig</p>
                    </div>
                </div>
            </div>
        @endif
    </section>
    @else
        <x-alert dismissable="false" type="warning">Geen ritten gevonden</x-alert>
    @endif
</div>
