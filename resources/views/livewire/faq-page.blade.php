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
        <x-slot name="title">Info FAQ pagina</x-slot>
        <x-slot name="content">
            <section class="border-b">
                <p>Op deze pagina kan je je:</p>
                <ul class="ml-2 mt-2 list-disc">
                    <li class="mb-2">Veelgestelde vragen bekijken</li>
                    <li class="mb-2">Een vraag opsturen</li>
                </ul>
            </section>
            <section class="mt-2 border-b">
                <p class="font-medium">Veelgestelde vragen bekijken.</p>
                <p class="mt-2">Hierin kan je:</p>
                <ul class="mt-2 ml-2 list-disc [&>li]:mb-2">
                    <li>
                        Als je jou vraag gevonden hebt, druk simpelweg op de vraag om het antwoord tevoorschijn te laten komen.
                    </li>
                </ul>
            </section>
            <section class="mt-2 border-b">
                <p class="font-medium">Een vraag opsturen.</p>
                <p class="mt-2">Hierin kan je:</p>
                <ul class="mt-2 ml-2 list-disc [&>li]:mb-2">
                    <li>
                        Het formulier openen door op de knop:
                        <x-button>
                            Stel vraag
                        </x-button>
                        te klikken
                    </li>
                    <li>
                        Jou vraag ingeven in het veld:
                        <x-input type="text" readonly placeholder="Vraag" class="shadow-md placeholder-gray-300 px-2 border border-black flex-grow basis-1/2"/>
                    </li>
                    <li>
                        Jou vraag opsturen door op de knop:
                        <x-button class="mt-3">
                            Versturen
                        </x-button>
                        te klikken
                    </li>
                </ul>
            </section>
        </x-slot>
    </x-help-modal>

    <div class="max-w-3xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-xl mx-auto">
            <!--FAQ's-->
            <div class="flex justify-center mx-auto items-center fex mb-3">
                <h1 class="font-bold text-4xl">Staat je vraag er niet tussen?</h1>
            </div>
            <div class="flex justify-center mx-auto items-center fex mb-4">
                <h2 class="text-2xl">Stel deze dan hier: &nbsp;
                </h2>
                <x-button
                    wire:click="showModal()"
                    class="mb-3">Stel vraag
                </x-button>
            </div>
            @if($faqs->isNotEmpty())
                @foreach($faqs as $faq)
                    <div class="relative shadow-lg rounded-xl m-1 p-3">
                        <!--Tickbox-->
                        <input type="checkbox" id="input{{ $faq->id }}" class="absolute peer opacity-0">
                        <!--Heading-->
                        <label for="input{{ $faq->id }}" class="font-bold tracking-normal mx-4 flex h-8 items-center cursor-pointer">
                            {{ $faq->question }}
                        </label>
                        <!--Arrow-->
                        <div class="absolute top-5 right-4 rotate-0 peer-checked:rotate-180 duration-150">
                            <x-heroicon-o-arrow-small-down class="w-5 h-5" />
                        </div>
                        <!--Content-->
                        <div class="max-h-0 overflow-hidden peer-checked:max-h-full">
                            <p class="p-3">
                                {!! nl2br(e($faq->answer)) !!}
                            </p>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-center py-8">
                    <h1 class="text-3xl">Geen FAQ's om te laten zien.</h1>
                </div>
            @endif
        </div>
    </div>

{{--Model voor het verstren van een vraag--}}
    <x-dialog-modal  id="recordModal"
                     wire:model="showModal">
        <x-slot name="title" class="text-center">
            <h2>Vraag opsturen</h2>
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
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2 md:col-span-1">
                    <x-label for="name" value="Name"/>
                    <x-input type="text" id="name" name="name" class="block mt-1 w-full"
                                 placeholder="Your name"
                             wire:model.lazy="name"/>
                </div>
                <div class="col-span-2 md:col-span-1">
                    <x-label for="email" value="Email"/>
                    <x-input type="text" id="name" name="name" placeholder="Your name"
                                 class="block mt-1 w-full"
                             wire:model.lazy="email"/>
                </div>
                <div class="col-span-2">
                    <x-label for="message" value="Message"/>
                    <x-textarea id="message" name="message" rows="5" placeholder="Your message"
                                         class="block mt-1 w-full max-h-96"
                                wire:model.lazy="message">
                    </x-textarea>
                </div>
            </div>
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button @click="show = false">Annuleer</x-secondary-button>
            <x-button
                wire:click="sendEmail()"
                wire:loading.attr="disabled"
                class="ml-2">Versturen
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
