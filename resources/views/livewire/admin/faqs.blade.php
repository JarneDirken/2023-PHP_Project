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
        <x-slot name="title">Info faq's beheren</x-slot>
        <x-slot name="content">
            <section class="border-b">
                <p>Op deze pagina kan je je:</p>
                <ul class="ml-2 mt-2 list-disc">
                    <li class="mb-2">Vragen en antwoorden van de FAQ's beheren</li>
                </ul>
            </section>
            <section class="mt-2 border-b">
                <p class="font-medium">Bovenaan kun je filteren en toevoegen</p>
                <p class="mt-2">Hierin kan je:</p>
                <ul class="mt-2 ml-2 list-disc [&>li]:mb-2">
                    <li>De tabel filteren op vraag door simpel weg de vraag te beginnen typen in:
                        <x-input readonly type="text" placeholder="Filter op vraag" class="shadow-md placeholder-gray-300 px-2 border border-black flex-grow basis-1/2"/></li>
                    <li>Rechts op de knop
                        <x-button class="mb-3">
                            Nieuwe FAQ aanmaken
                        </x-button>
                         te klikken, om een nieuwe FAQ aan te maken.
                    </li>
                </ul>
            </section>
            <section class="mt-2 border-b">
                <p class="font-medium">Als je de knop
                    <x-button class="mb-3">
                        Nieuwe FAQ aanmaken
                    </x-button> aanklikt.</p>
                <p class="mt-2">Hierin kan je:</p>
                <ul class="mt-2 ml-2 list-disc [&>li]:mb-2">
                    <li>
                        De vraag en het antwoord van de nieuwe vraag in te geven.
                    </li>
                    <li>Een nieuwe FAQ te maken door op
                        <x-button class="mb-3">
                            Maak nieuwe FAQ
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
                            Pas een vraag aan
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center">
                            <x-phosphor-trash-duotone class="hover:text-red-900 h-8 w-8 inline"></x-phosphor-trash-duotone>
                        </td>
                        <td>
                            Verwijder een vraag
                        </td>
                    </tr>
                    </tbody>
                </table>
            </section>
        </x-slot>
    </x-help-modal>

    {{-- filter section --}}
    <x-section class="flex gap-3 flex-wrap mb-3">
        <x-input id="search" type="text" placeholder="Filter op vraag"
                 wire:model="question"
                 class="shadow-md placeholder-gray-300 px-2 border border-black flex-grow basis-1/2"/>
            <x-button
                      wire:click="showFaq()"
                      data-tippy-content="Nieuwe FAQ">
                Nieuwe FAQ aanmaken
            </x-button>
    </x-section>
    {{--paginate--}}
    <div class="my-4 justify-center mx-auto">{{ $faqs->links() }}</div>

    {{-- main section --}}
    @if($faqs->isEmpty())
        <x-alert dismissable="false" type="warning">
            Geen resultaten voor: <b>'{{ $question }}'</b>
        </x-alert>
    @else
    <x-section>
            <div class="overflow-x-auto">
                <table class="text-center w-full border border-gray-300">
                    <colgroup>
                        <col class="w-15">
                        <col class="w-30">
                        <col class="w-100">
                        <col class="w-max">
                    </colgroup>
                    <thead>
                    <tr class="bg-gray-100 text-gray-700 [&>th]:p-2 cursor-pointer">
                        <th>
                            <span>#</span>
                        </th>
                        <th>
                            <span>Vraag</span>
                        </th>
                        <th>
                            <span>Antwoord</span>
                        </th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($faqs as $faq)
                        <tr class="border-t border-gray-300 [&>td]:p-2">
                            <td>
                                {{$faq->id}}
                            </td>
                            <td>
                                {{$faq->question}}
                            </td>
                            <td>
                                {{$faq->answer}}
                            </td>
                            <td
                                x-data="">
                                <div class="flex gap-1 justify-end [&>*]:cursor-pointer [&>*]:outline-0 [&>*]:transition">
                                    <x-phosphor-pencil-line-duotone
                                        wire:click="setNewFaq({{ $faq->id }})"
                                        class="w-5 text-gray-300 hover:text-green-600"/>
                                    <x-phosphor-trash-duotone
                                        @click="$dispatch('swal:confirm', {
                                    title: 'Verwijder {{$faq->question}}?',
                                    cancelButtonText: 'Annuleer',
                                    confirmButtonText: 'Verwijder de vraag',
                                    next: {
                                        event: 'delete-faq',
                                        params: {
                                            id: {{ $faq->id }}
                                        }
                                    }
                                });"
                                        class="w-5 text-gray-300 hover:text-red-600"/>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
    </x-section>
        <div class="my-4 justify-center mx-auto">{{ $faqs->links() }}</div>
    @endif

    {{--Model voor faq aan te maken--}}
    <x-dialog-modal  id="recordModal"
                     wire:model="showModal">
        <x-slot name="title" class="text-center">
            <h2>{{ is_null($newFaq['id']) ? 'Nieuwe FAQ' : 'FAQ bijwerken' }}</h2>
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
            <x-label for="question" value="Vraag" class="mt-4"></x-label>
            <x-input id="question" type="text" placeholder="Vraag" wire:model="newFaq.question" class="w-full"></x-input>
            <x-label for="answer" value="Antwoord" class="mt-4"></x-label>
            <x-textarea id="answer" placeholder="Antwoord" wire:model="newFaq.answer" class="w-full max-h-96"></x-textarea>
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button @click="show = false">Annuleer</x-secondary-button>
            @if(is_null($newFaq['id']))
                <x-button
                    wire:click="createFaq()"
                    wire:loading.attr="disabled"
                    class="ml-2">Maak nieuwe FAQ
                </x-button>
            @else
                <x-button
                    color="success"
                    wire:click="updateFaq({{ $newFaq['id'] }})"
                    wire:loading.attr="disabled"
                    class="ml-2">Werk FAQ bij
                </x-button>
            @endif
        </x-slot>
    </x-dialog-modal>
</div>
