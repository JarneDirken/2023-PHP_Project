<?php

namespace App\Http\Livewire;

use App\Models\ArticleOrder;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\PointUser;
use App\Models\UserTour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ShoppingCart extends Component
{
    public function apply(Request $request): \Illuminate\Http\RedirectResponse
    {
        $couponId = $request->input('coupon');
        if ($couponId == -1){
            // Geen geldige coupon geselecteerd, verwijder alle coupongerelateerde gegevens uit de sessie
            session()->forget('selected_coupon_id');
            session()->forget('coupon');
            session()->forget('amount');
            session()->forget('total');
            session()->forget('totalp');
            return redirect()->back();
        }else{
            $coupon = Coupon::find($couponId);
            $amountE = $coupon->amount_euro;
            $amountP = $coupon->amount_point;
            $totalpoints = PointUser::where('user_id', Auth::id())->sum('points');
            if ( $amountP < $totalpoints) {

                $user = auth()->user();
                $carts = ArticleOrder::with('order','article','article.garment','article.size')->whereHas('order',function ($query) use ($user){
                    $query->where('user_id',$user->id)->where('hasPaid', false);})->get();
                $sum  = 0;
                foreach ($carts as $cart) {
                    $sum += $cart->article->garment->price * $cart->amount;
                }

                $total = $sum-$coupon->amount_euro;

                $totalp = $totalpoints-$coupon->amount_point;
                $order = Order::where('user_id', $user->id)
                    ->where('hasPaid', false)->first();

                if ($total >= 0){
                    // Geldige coupon geselecteerd, sla de coupongegevens op in de sessie en koppel de coupon aan de bestelling
                    session()->put('selected_coupon_id', $couponId);
                    session()->put('coupon',$coupon);
                    session()->put('amount',$amountE);
                    session()->put('total',$total);
                    session()->put('totalp',$totalp);


                    $order->coupon_id = $couponId;
                    $order->save();
                    return redirect()->back();

                }else{
                    session()->forget('selected_coupon_id');
                    session()->forget('coupon');
                    session()->forget('amount');
                    session()->forget('total');
                    session()->forget('totalp');

                    return redirect()->back()->with('fault', 'De totaalprijs mag niet negatief zijn na het toepassen van de kortingsbon!');
                }

            }else{


                return redirect()->back()->with('danger', 'Je hebt te weinig punten om deze kortingsbon in te lassen!');
            }
        }


    }
    public function deleteCartItem(ArticleOrder $cart)
    {
        // Als de hoeveelheid groter is dan 1, verminder de hoeveelheid met 1 en sla op
        if($cart->amount >1){
            $cart->amount -= 1;
            $cart->save();
        } else {
            // Als de hoeveelheid gelijk is aan 1, verwijder het item
            $cart->delete();
        }
        return redirect()->route('showcart');
    }
    public function clearcart()
    {
        $user = auth()->user();
        ArticleOrder::with('order','article','article.garment','article.size')->whereHas('order',function ($query) use ($user){
            $query->where('user_id',$user->id)->where('hasPaid', false);})->delete();
        return redirect()->route('showcart');
    }
    public function render()
    {

        $user = auth()->user();
        $carts = ArticleOrder::with('order','article','article.garment','article.size')->whereHas('order',function ($query) use ($user){
            $query->where('user_id',$user->id)->where('hasPaid', false);})->get();

        $totalpoints = PointUser::where('user_id', Auth::id())->sum('points');
        $sum = 0;
        foreach ($carts as $cart) {
            $sum += $cart->article->garment->price * $cart->amount;
        }

        $count = 0;
        $user = auth()->user();

        session()->put('sum',$sum);

        if ($user) {
            $activeOrder = Order::where('user_id', $user->id)->where('hasPaid', false)->first();
            if ($activeOrder) {
                $count = $activeOrder->articleOrders()->sum('amount');
            }
        }
        session(['count'=>$count]);

        $coupons = Coupon::where('active',true)->get();
        return view('livewire.shopping-cart',compact('carts','totalpoints','coupons','count','sum'))
            ->layout('layouts.projectPHP', [
                'description' => 'Winkelmandje tonen',
                'title' => 'Winkelmandje'
            ]);
    }
}
