<div>
    <x-help-modal>
        <x-slot name="title">Info winkelmandje</x-slot>
        <x-slot name="content">
            <section class="border-b border-gray-300">
                <p>Op deze pagina kan je:</p>
                <ul class="ml-2 mt-2 list-disc">
                    <li class="mb-2">Je reeds aan het winkelmandje toegevoegde goederen weergeven en bestellen.</li>
                </ul>
            </section>
            <section class="border-b border-gray-300">
                    <p class="font-medium">Links in het winkelmandje vind je de door jouw geselecteerde goederen.</p>
                    <p class="font-medium">Rechts in het winkelmandje vind je de betaalsectie terug met:</p>
                <ul class="ml-2 mt-2 list-disc">
                    <li class="mb-2">De optie om een kortingsbon te selecteren door de knop <x-select><option value="0">Kies een kortingsbon</option></x-select>aan te klikken.</li>
                    <li class="mb-2">De optie om het gehele winkelmandje leeg te maken door de knop <x-button>Winkelmand leegmaken</x-button>aan te klikken.</li>
                    <li class="mb-2">De optie om terug naar het bestellen van kledij te gaan door de knop <x-secondary-button>Verder winkelen</x-secondary-button>aan te klikken.</li>
                    <li class="mb-2">De optie om verder door naar de betaalpagina te gaan door de knop <x-button>Afrekenen</x-button>aan te klikken.</li>
                </ul>

            </section>
        </x-slot>
    </x-help-modal>
    <div>
        <div class="flex bg-white min-h-fit items-center">
            <div class="w-4/6 mr-3">
                @if($carts->isEmpty())
                    <x-alert type="info" class="w-full text-center" >
                        Je winkelwagen is nog leeg, klik op 'verder winkelen' om terug naar de webshop te gaan.
                    </x-alert>
                @else
                    <table class="text-center w-5/6 bg-white">
                        <tbody>
                        @foreach($carts as $cart)

                         <tr class="border-b border-gray-300 [&>td]:p-2">
                             <td>
                                 <div>
                                         <img src="{{$cart->article->garment->url}}" alt="Product Image" class="w-3/4 h-48 object-cover">
                                     </div></td>
                             <td><div class="w-3/4">
                                     <p class="text-lg text-center font-semibold mb-2">{{$cart->article->garment->name}}</p>
                                     <p>{{$cart->article->size->name}}</p>
                                 </div></td>
                             <td>{{$cart->amount}} x</td>

                                     <td>€{{$cart->article->garment->price}}</td>


                             <td x-data=""><div>
                                     <x-phosphor-trash-duotone
                                         wire:click="deleteCartItem({{$cart->id}})"
                                         data-tippy-content="Verwijder artikel"
                                         class="w-5 text-gray-300 hover:text-red-600"/>
                                 </div></td>
                         </tr>

                        @endforeach
                        </tbody>
                    </table>
                @endif

            </div>
            <div class="w-1-3">

                <h1 class="mt-5 text-3xl font-semibold">Waardebon</h1>
                @if(session('totalp'))

                    <p class="mt-4">Totaal aantal punten: {{ session('totalp') }}</p>
                @else <p class="mt-4">Totaal aantal punten: {{$totalpoints}}</p>
                @endif
                <div class="flex justify-start">
                    <label class="mt-2">Kortingsbon:</label>
                    <form id="coupon-form" action="{{ route('apply') }}" method="POST">
                        @csrf
                        <x-select id="coupon"
                                  name="coupon"
                                  onchange="this.form.submit()"
                                  class="w-full ml-3 mb-2 -pt-2 shadow-md"
                                  required>
                            <option value="0">Kies een kortingsbon</option>
                            @foreach($coupons as $coupon)
                                {{-- De waarde van de optie is ingesteld op de ID van de reeds geselecteerde kortingsbon --}}
                                <option value="{{ $coupon->id }}" @if (session('selected_coupon_id') == $coupon->id) selected @endif>€{{$coupon->amount_euro}}-{{$coupon->amount_point}} punten</option>
                            @endforeach
                            <option value="-1">Verwijder kortingsbon</option>
                        </x-select>

                    </form>
                </div>
                @if(session('danger'))

                    <p class="text-red-600 font-bold">{{ session('danger') }}</p>
                @endif
                @if(session('fault'))

                    <p class="text-red-600 font-bold">{{ session('fault') }}</p>
                @endif
                <h1 class="mt-5 text-3xl font-semibold">Totaalprijs</h1>
                <p class="mt-4 mb-2">Subtotaal: €{{$sum}}</p>
                <div class="border-b border-gray-300 pb-3">
                    @if(session('amount'))

                        <p>Kortingsbon: €{{ session('amount') }}</p>
                    @else <p>Kortingsbon: €0</p>
                    @endif
                </div>
                @if(session()->has('total'))
                    {{-- Toont de totaalprijs na aftrek van de kortingsbon --}}
                    <p class="font-semibold">Totaalprijs: €{{ $sum-session('amount') }}</p>
                @else <p class="font-semibold">Totaalprijs: €{{$sum}}</p>
                @endif
                <div>
                    <div class="mt-5 flex float-right">
                        <x-button wire:click="clearcart" class="h-15 mt-3 mr-5">
                            Winkelmand leegmaken
                        </x-button>

                        <x-secondary-button class="h-15  justify-center mt-3 mr-5">
                            <a href="{{url('kledij-bestellen')}}">Verder winkelen</a>
                        </x-secondary-button>
                        <x-button class=" h-15 justify-center mt-3 mr-5">
                            <a href="{{route('payment')}}">Afrekenen</a>
                        </x-button>


                    </div>
                </div>

            </div>
        </div>


    </div>

</div>
