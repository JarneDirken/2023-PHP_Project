@php use App\Models\Coupon; @endphp
<div>

    <x-help-modal>
        <x-slot name="title">Info bestellingen weergeven</x-slot>
        <x-slot name="content">
            <section class="border-b border-gray-300">
                <p class="font-medium">Op de pagina kan je:</p>
                <ul class="ml-2 mt-2 list-disc">
                    <li class="mb-2">Een overzicht bekijken van alle reeds door jou gemaakte orders, met bijhorende artikelen onder elkaar opgesomd.</li>
                    <li class="mb-2">Moest je een kortingsbon ingegeven hebben deze ook op deze pagina waarnemen.</li>
                </ul>
            </section>
        </x-slot>
    </x-help-modal>

    <div class="my-4">{{ $orders->links() }}</div>
    <div class="grid grid-cols-1 lg:grid-cols-2 2xl:grid-cols-3 gap-8">
        @forelse($orders as $index=> $order)
            <div class="flex flex-col bg-white border border-gray-300 shadow-md rounded-lg custom-div overflow-hidden">
                <div class="p-2 bg-gray-100 border-b border-b-gray-300">
                    @php
                        $date = date('d/m/Y', strtotime('2023-05-18'));
                    @endphp
                    <p class="font-bold">Bestelling van: {{ $date }}</p>
                </div>
                @php
                    $filteredCarts = $carts->where('order_id', $order->id);
                @endphp
                @foreach ($filteredCarts as $cart)
                    <div class="p-2 flex items-start">
                        <div>
                            <img src="{{$cart->article->garment->url}}" alt="" style="width: auto; height: 8rem;">
                        </div>
                        <div class="flex flex-col ml-4">
                            <p class="font-medium">{{$cart->amount}} x {{$cart->article->garment->name}}({{$cart->article->size->name}})</p>
                            <p class="font-sm text-gray-400">Prijs: €{{$cart->article->garment->price}}</p>
                        </div>
                    </div>
                @endforeach

                <div class="p-2 bg-gray-100 border-t border-t-gray-300">
                    @php
                        $price = $filteredCarts->map(function ($item) {
                             return $item['article']['garment']['price'] * $item['amount'];
                         })->sum();
                    @endphp

                    @if ($order->coupon_id && $order->coupon_id !== 0)
                        {{-- Controleren of er een geldige kortingsbon is gekoppeld aan de bestelling als dit het geval is zal de totaalprijs aangepast worden--}}

                        @php
                            $coupon = Coupon::where('id',$order->coupon_id)->first();
                            $amount = $coupon->amount_euro;
                            $total = $price-$amount;
                        @endphp
                        <p class="font-semibold">Subtotaal: €{{$price}}</p>

                        <p class="font-semibold">Kortingsbon: -€{{$amount}}</p>
                        <hr>
                        <p class="font-semibold">Totaalprijs: €{{$total}}</p>
                    @else
                        <p class="font-semibold">Totaalprijs: €{{$price}}</p>
                    @endif

                </div>
            </div>
        @empty
            <x-alert type="info" dismissible="false" class="lg:col-span-2 2xl:col-span-3">

                <h3 class="font-semibold font-medium">Geen bestellingen gevonden</h3>
                <p>Er zijn nog geen bestellingen geplaatst.</p>
            </x-alert>
        @endforelse
    </div>
    <style>
        .custom-div {
            height: fit-content;
        }
    </style>
</div>
