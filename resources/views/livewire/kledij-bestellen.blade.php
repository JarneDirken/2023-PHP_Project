@push('script')
    @if(Session::has('message'))
        <script>
            document.addEventListener('livewire:load', function() {
                // Toon een melding met behulp van SweetAlert als er een artikel aan het mandje toegevoegd is
                Swal.fire({
                    toast: true,
                    position: 'top',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,

                    title: '{{ Session::get('message') }}',


                });
            });
        </script>
    @endif
@endpush
<div>
    <x-help-modal>
        <x-slot name="title">Info kledij bestellen</x-slot>
        <x-slot name="content">
            <section class="border-b border-gray-300">
                <p class="font-medium">Op deze pagina kan je:</p>
                <ul class="ml-2 mt-2 list-disc">
                    <li class="mb-2">De verschillende kledij artikelen bekijken en toevoegen aan je winkelmandje.</li>
                </ul>
            </section>
            <section class="border-b border-gray-300">
                <ul class="ml-2 mt-2 list-disc">
                    <li class="mb-2">Bovenaan kan je de kledingstukken filteren door hier : <x-input type="text" readonly placeholder="Filter op naam van het product"/> de naam van het product in te geven.</li>

                </ul>

            </section>
            <section class="border-b border-gray-300">
                <p class="mt-2 font-medium">Het toevoegen van kledij aan het winkelmandje.</p>
                <ul class="ml-2 mt-2 list-disc">
                    <li class="mb-2">Om een maat te selecteren dien je hier : <x-select><option value="0">Kies een maat</option></x-select> de gewenste maat te selecteren.</li>
                    <li class="mb-2">Om het artikel (met een reeds geselecteerde maat) aan het winkelmandje toe te voegen klik je op <x-button>Aan winkelmandje toevoegen</x-button></li>
                    <li class="mb-2 flex items-center">Om naar het winkelmandje zelf te gaan kan je rechtsboven op  <x-fas-shopping-basket class="mx-1 w-4 h-4"/>  klikken.</li>
                </ul>
            </section>
        </x-slot>
    </x-help-modal>



    <div class="grid grid-cols-10 gap-4">
        <div class="col-span-10 md:col-span-5 lg:col-span-3">
            <x-label for="name" value="Filter"/>
            <div
                class="relative">
                <x-input id="name" type="text"
                         wire:model.debounce.500ms="name"

                         class="block mt-1 w-full"
                         placeholder="Filter op naam van het product"/>
                <div
                    class="w-5 absolute right-4 top-3 cursor-pointer">
                    <x-phosphor-x-duotone/>
                </div>
            </div>
        </div>


    </div>

    @if($garments->isEmpty())
        <x-alert dismissable="false" type="warning">Geen resultaten voor: <b>'{{ $name }}'</b></x-alert>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-2 2xl:grid-cols-3 gap-8 mt-8">
            @foreach($garments as $garment)
                <div wire:key="garment-{{ $garment->id }}" class="flex bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="flex-1 flex flex-col">
                        <img src="{{$garment->url}}" alt="Product Image" class="w-full h-48 object-cover">
                        <div class="flex-1 p-4">
                            <h3 class="text-lg font-semibold mb-2">{{$garment->name}}</h3>
                            <p class="text-gray-600">€{{$garment->price}}</p>

                        </div>
                        <div style="height: 100px" class="flex-1 bg-gray-100 p-4">
                            @if( $garment->articles->pluck('stock')->sum() > 0)
                                <!-- Controleren of de totale voorraad van alle artikelen van het kledingstuk groter is dan 0 -->
                                <form action="{{url('addcart',$garment->id)}}" method="POST">
                                    @csrf
                                    <x-select id="size"
                                              name="size"
                                              wire:model.defer="sizeId"
                                              class="w-1/2 mb-2 -pt-2 shadow-md"
                                              required>

                                        <option value="">Kies een maat</option>
                                        @foreach($sizes as $size)
                                            <option value="{{$size->id}}">{{$size->name}}</option>
                                        @endforeach
                                    </x-select>
                                    <input class="bg-black text-white py-2 px-4 rounded-md hover:bg-gray-800 transition-colors duration-300" type="submit" value="Aan winkelmandje toevoegen">
                                    @if(session('garmentId') == $garment->id && session('stockerr'))
                                        <!-- Controleren of er nog stock is voor het artikel(maat en kledingstuk samengenomen) -->
                                        <div class="text-red-600 font-bold">
                                            {{ session('stockerr') }}
                                        </div>
                                    @endif
                                </form>
                            @else
                                <p class="font-extrabold text-red-700 text-center mt-5">UITVERKOCHT!!!</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
