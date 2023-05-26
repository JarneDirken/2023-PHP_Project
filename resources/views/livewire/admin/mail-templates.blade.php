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
        <x-slot name="title">Info mailing templates beheren</x-slot>
        <x-slot name="content">
            <section class="border-b">
                <p>Op deze pagina kan je je:</p>
                <ul class="ml-2 mt-2 list-disc">
                    <li class="mb-2">Vragen en antwoorden van de mailing templates pagina beheren</li>
                </ul>
            </section>
            <section class="mt-2 border-b">
                <p class="font-medium">Bovenaan kun je filteren en toevoegen</p>
                <p class="mt-2">Hierin kan je:</p>
                <ul class="mt-2 ml-2 list-disc [&>li]:mb-2">
                    <li>De tabel filteren op naam door simpel weg de naam te beginnen typen.
                        <x-input type="text" readonly placeholder="Filter op naam" class="shadow-md placeholder-gray-300 px-2 border border-black flex-grow basis-1/2"/>
                    <li>Rechts op de knop
                        <x-button class="mb-3">
                            Nieuwe template
                        </x-button>
                        te klikken
                    </li>
                </ul>
            </section>
            <section class="mt-2 border-b">
                <p class="font-medium">Als je de knop
                    <x-button class="mb-3">
                        Nieuwe template
                    </x-button> aanklikt.</p>
                <p class="mt-2">Hierin kan je:</p>
                <ul class="mt-2 ml-2 list-disc [&>li]:mb-2">
                    <li>
                        De naam, onderwerp en inhoud van de nieuwe template in te geven.
                    </li>
                    <li>Een nieuwe template te maken door op
                        <x-button class="mb-3">
                            Voeg toe
                        </x-button>
                        te klikken
                    </li>
                    <li>Annuleren door op
                        <x-secondary-button>Annuleer</x-secondary-button>
                        te klikken
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
                            Pas een mail template aan
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center">
                            <x-phosphor-trash-duotone class="hover:text-red-900 h-8 w-8 inline"></x-phosphor-trash-duotone>
                        </td>
                        <td>
                            Verwijder een mail template
                        </td>
                    </tr>
                    </tbody>
                </table>
            </section>
        </x-slot>
    </x-help-modal>

    {{-- filter section --}}
    <x-section class="flex gap-3 flex-wrap mb-3">
        <x-input id="search" type="text" placeholder="Filter op naam"
                 wire:model="name"
                 class="shadow-md placeholder-gray-300 px-2 border border-black flex-grow basis-1/2"/>
        <div class="flex justify-center items-center">
            <x-button wire:click="setNewMailTemplate()" class="mb-3">
                Nieuwe template
            </x-button>
        </div>
    </x-section>

    {{-- main section --}}
    {{--paginate--}}
    <div class="my-4 justify-center mx-auto">{{ $mailTemplates->links() }}</div>
    {{-- tabel with all data--}}
    @if($mailTemplates->isEmpty())
        <x-alert dismissable="false" type="warning">
            Geen resultaten voor: <b>'{{ $name }}'</b>
        </x-alert>
    @else
        <x-section>
            <div class="overflow-x-auto">
                <table class="text-center w-full border border-gray-300">
                    <colgroup>
                        <col class="w-60">
                        <col class="w-60">
                        <col class="w-max">
                        <col class="w-16">
                    </colgroup>
                    <thead>
                    <tr class="bg-gray-100 text-gray-700 [&>th]:p-2">
                        <th>Naam</th>
                        <th>Onderwerp</th>
                        <th>Inhoud</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($mailTemplates as $mailTemplate)
                        <tr wire:key="mailTemplate_{{ $mailTemplate->id }}" class="border-t border-gray-300 [&>td]:p-2">
                            <td>{{ $mailTemplate->name }}</td>
                            <td>{{ $mailTemplate->subject }}</td>
                            <td>{{ $mailTemplate->body }}</td>
                            <td>
                                <div class="flex gap-1 justify-center [&>*]:cursor-pointer [&>*]:outline-0 [&>*]:transition">
                                    <button
                                        wire:click="setNewMailTemplate({{ $mailTemplate->id }})"
                                        class="w-5 text-gray-300 hover:text-green-600">
                                        <x-phosphor-pencil-line-duotone class="inline-block w-5 h-5"/>
                                    </button>
                                    <button
                                        x-data=""
                                        @click="$dispatch('swal:confirm', {
                                        title: 'Ben je zeker dat je deze template wilt verwijderen?',
                                        cancelButtonText: 'Nee',
                                        confirmButtonText: 'Ja, verwijder deze template',
                                        next: {
                                            event: 'delete-mailTemplate',
                                            params: {
                                                id: {{ $mailTemplate->id }}
                                            }
                                        }
                                    });"
                                        class="w-5 text-gray-300 hover:text-red-600">
                                        <x-phosphor-trash-duotone class="inline-block w-5 h-5"/>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="border-t border-gray-300 p-4 text-center text-gray-500">
                                <div class="font-bold italic text-sky-800">Geen templates gevonden</div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </x-section>
    @endif
    {{--paginate--}}
    <div class="my-4 justify-center mx-auto">{{ $mailTemplates->links() }}</div>

    {{-- popup voor het creeeren van mail templates --}}
    <x-dialog-modal  id="mailTemplateModal"
                     wire:model="showModal">
        <x-slot name="title">
            <h2>{{ is_null($newMailTemplate['id']) ? 'Nieuwe template' : 'Template aanpassen' }}</h2>
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
                             wire:model.defer="newMailTemplate.name"
                             class="mt-1 block w-full"/>
                    <x-label for="subject" value="Onderwerp" class="mt-4"/>
                    <x-input id="subject" type="text"
                             wire:model.defer="newMailTemplate.subject"
                             class="mt-1 block w-full"/>
                    <x-label for="body" value="Inhoud" class="mt-4"/>
                    <x-textarea id="body"
                             wire:model.defer="newMailTemplate.body"
                             class="mt-1 block w-full max-h-96">
                    </x-textarea>
                </div>
            </div>
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button @click="show = false">Annuleer</x-secondary-button>
            @if(is_null($newMailTemplate['id']))
                <x-button
                    wire:click="createMailTemplate()"
                    wire:loading.attr="disabled"
                    class="ml-2">Voeg toe
                </x-button>
            @else
                <x-button
                    color="success"
                    wire:click="updateMailTemplate({{ $newMailTemplate['id'] }})"
                    wire:loading.attr="disabled"
                    class="ml-2">Template aanpassen
                </x-button>
            @endif
        </x-slot>
    </x-dialog-modal>
</div>
