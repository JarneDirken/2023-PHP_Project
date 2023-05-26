<div class="grid place-content-center">
    <h1 class="text-center text-3xl mb-3">Betaal overzicht</h1>
    <div class="w-full bg-slate-200 p-2">
        @if($registration || $newMembership)
            <form>
                <h3 class="text-left">Inschrijving:</h3>
                <div class="flex justify-between">
                    <p class="text-left">{{ $membership->name }}</p>
                    <p class="text-right">&euro; {{ $membership->price }}</p>
                </div>

                <div class="border-b border-black mt-5 mb-1 w-full"></div>

                <h3 class="text-left">Extra:</h3>
                <div class="flex justify-between">
                    <p class="text-left">+ {{ $insurance->name }}</p>
                    <p class="text-right">&euro; {{ $differenceInPrice }}</p>
                    <input type="checkbox" name="insurance" wire:model="input.insurance" wire:click="updateSessionValue" wire:loading.class="opacity-50">
                </div>


                <div class="border-b border-black mt-5 mb-1 w-full"></div>

                <div class="flex justify-between">
                    <h3 class="font-bold text-left">Total</h3>
                    <h3 class="font-bold text-right">&euro; {{ $price }}</h3>
                </div>
            </form>

        @else
            <h3 class="text-left">Artikelen:</h3>


            @foreach($carts as $cart)

                <div class="flex justify-between">
                    <p class="text-right">
                        {{ $cart->amount }} x {{ $cart->article->garment->name }} ({{$cart->article->size->name}})
                    </p>
                    <p class="text-right">
                        &euro;{{ $cart->amount*$cart->article->garment->price }}
                    </p>




                </div>
            @endforeach

            <div class="border-b border-black mt-5 mb-1 w-full"></div>
            <div class="flex justify-between">
                <p>Subtotaal:</p>
                <p>€{{session('sum')}}</p>
            </div>
            @if(session('amount'))
                <div class="flex justify-between">
                    <p>Kortingsbon: </p>
                    <p>-€{{ session('amount') }}</p>
                </div>

            @endif

                @if(session('total'))
                <div class="flex justify-between">
                    <p class="font-semibold">Totaalprijs: </p>
                    <p class="font-semibold">€{{ session('total') }}</p>
                </div>
                @else
                <div class="flex justify-between">
                    <p class="font-semibold">Totaalprijs: </p>
                    <p class="font-semibold">€{{session('sum')}}</p>
                </div>
                @endif

        @endif

        <div class="flex justify-between mt-4">
            <button wire:click="cancelPayment()" class="btn bg-red-500 text-white rounded text-center py-1 px-3">Annuleer</button>
            <button wire:click="confirmPayment()" class="btn bg-blue-500 text-white rounded text-center py-1 px-3">Betalen</button>
        </div>
    </div>
</div>
