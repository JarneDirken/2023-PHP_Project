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
        <x-slot name="title">Info artikelen beheren</x-slot>
        <x-slot name="content">
            <section class="border-b">
                <p>Op deze pagina kan je je:</p>
                <ul class="ml-2 mt-2 list-disc">
                    <li class="mb-2">Artikelen beheren</li>
                </ul>
            </section>
            <section class="mt-2 border-b">
                <p class="font-medium">Bovenaan kun je filteren en toevoegen</p>
                <p class="mt-2">Hierin kan je:</p>
                <ul class="mt-2 ml-2 list-disc [&>li]:mb-2">
                    <li>Sorteren door het gewenste kledingstuk te <select disabled><option>Selecteren</option></select>.</li>
                    <li>Een nieuw kledingstuk toevoegen door rechts op de knop
                        <x-button class="mb-3">
                            Nieuw artikel
                        </x-button>
                        te klikken.
                    </li>
                </ul>
            </section>
            <section class="mt-2 border-b">
                <p class="font-medium">Als je de knop
                    <x-button class="mb-3">
                        Nieuw artikel
                    </x-button> aanklikt.</p>
                <p class="mt-2">Hierin kan je:</p>
                <ul class="mt-2 ml-2 list-disc [&>li]:mb-2">
                    <li>
                        De maat van het artikel, type kledingstuk en hoeveel er op voorraad zijn opgeven.
                    </li>
                    <li>Een nieuw artikel te maken door op
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
                            Pas een artikel aan
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center">
                            <x-phosphor-trash-duotone class="hover:text-red-900 h-8 w-8 inline"></x-phosphor-trash-duotone>
                        </td>
                        <td>
                            Verwijder een artikel
                        </td>
                    </tr>
                    </tbody>
                </table>
            </section>
            <section class="border-t mt-2">
                <p class="mt-5 text-blue-900">Een artikel mag niet verwijderd worden als er een bestelling met dit artikel is</p>
            </section>
        </x-slot>
    </x-help-modal>

    {{-- filter section --}}
    <x-section class="flex flex-wrap gap-3 mb-3 max-w-2xl mx-auto justify-center">
        <label for="size" class="hidden"></label>
        <select id="size" name="size" class="mt-1 block w-52" wire:model="selectedSize">
            <option value="%">Alle maten</option>
            @foreach($sizes as $s)
                <option value="{{ $s->id }}">
                    {{ $s->name }}
                </option>
            @endforeach
        </select>
        <label for="article" class="hidden"></label>
        <select id="article" name="article" class="mt-1 block w-52" wire:model="selectedArticle">
            <option value="%">Alle kledingstukken</option>
            @foreach($garments as $g)
                <option value="{{ $g->id }}">
                    {{ $g->name }}
                </option>
            @endforeach
        </select>
        <x-button wire:click="setNewArticle()"
                  data-tippy-content="Nieuw artikel">
            Nieuw artikel
        </x-button>
    </x-section>

    {{--paginate--}}
    <div class="my-4 justify-center mx-auto max-w-2xl">{{ $articles->links() }}</div>
    {{-- main section --}}
    <x-section class="max-w-2xl mx-auto justify-center flex">
        <table class="text-center w-full border border-gray-300">
            <colgroup>
                <col class="w-40">
                <col class="w-40">
                <col class="w-40">
                <col class="w-max">
            </colgroup>
            <thead>
            <tr class="bg-gray-100 text-gray-700 [&>th]:p-2">
                <th>Maat</th>
                <th>Kleding</th>
                <th>Voorraad</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @forelse($articles as $article)
                <tr wire:key="article_{{ $article->id }}" class="border-t border-gray-300">
                    <td>{{ $article->size_name }}</td>
                    <td>{{ $article->garment_name }}</td>
                    <td>{{ $article->stock }}</td>
                    <td>
                        <div class="flex gap-1 justify-center [&>*]:cursor-pointer [&>*]:outline-0 [&>*]:transition">
                            <button
                                wire:click="setNewArticle({{ $article->id }})"
                                class="w-5 text-gray-300 hover:text-green-600">
                                <x-phosphor-pencil-line-duotone class="inline-block w-5 h-5"/>
                            </button>
                            {{-- alleen als een artikel met nog geen artikel-bestellingen is verbonden mag je ze deleten (DTR) --}}
                            @if(($article->articleorders->count() ?? 0) == 0)
                            <button
                                x-data=""
                                @click="$dispatch('swal:confirm', {
                                        title: 'Ben je zeker dat je dit artikel wilt verwijderen?',
                                        cancelButtonText: 'Nee',
                                        confirmButtonText: 'Ja, verwijder dit artikel',
                                        next: {
                                            event: 'delete-article',
                                            params: {
                                                id: {{ $article->id }}
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
                        <div class="font-bold italic text-sky-800">Geen artikels gevonden</div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </x-section>
    {{--paginate--}}
    <div class="my-4 justify-center mx-auto max-w-2xl">{{ $articles->links() }}</div>

    {{--Model voor artikel aan te maken--}}
    <x-dialog-modal  id="articleModal"
                     wire:model="showModal">
        <x-slot name="title">
            <h2>{{ is_null($newArticle['id']) ? 'Nieuw artikel' : 'Artikel aanpassen' }}</h2>
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
                    <x-label for="size_id" value="Maat" class="mt-4"/>
                    <x-select wire:model.defer="newArticle.size_id" id="size_id" class="block mt-1 w-full">
                        <option value="">Selecteer een maat</option>
                        @foreach($sizes as $size)
                            <option value="{{ $size->id }}">{{ $size->name }}</option>
                        @endforeach
                    </x-select>
                    <x-label for="garment_id" value="Kleding" class="mt-4"/>
                    <x-select wire:model.defer="newArticle.garment_id" id="garment_id" class="block mt-1 w-full">
                        <option value="">Selecteer een kledingstuk</option>
                        @foreach($garments as $garment)
                            <option value="{{ $garment->id }}">{{ $garment->name }}</option>
                        @endforeach
                    </x-select>
                    <x-label for="stock" value="Voorraad" class="mt-4"/>
                    <x-input id="stock" type="number"
                                 wire:model.defer="newArticle.stock"
                                 class="mt-1 block w-full"/>
                </div>
            </div>
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button @click="show = false">Annuleer</x-secondary-button>
            @if(is_null($newArticle['id']))
                <x-button
                    wire:click="createArticle()"
                    wire:loading.attr="disabled"
                    class="ml-2">Voeg toe
                </x-button>
            @else
                <x-button
                    color="success"
                    wire:click="updateArticle({{ $newArticle['id'] }})"
                    wire:loading.attr="disabled"
                    class="ml-2">Artikel aanpassen
                </x-button>
            @endif
        </x-slot>
    </x-dialog-modal>
</div>
