<?php

namespace App\Http\Livewire\Shared;


use App\Models\ArticleOrder;

use App\Models\Membership;
use App\Models\Order;

use App\Http\Livewire\Mailing;


use App\Models\MembershipUser;
use App\Models\PointUser;
use App\Models\Season;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Illuminate\Session\Store;


class Payment extends Component
{
    public $registration = false;
    public $order = false;
    public $actual_wbv_insurance;
    public $membership;
    public $insurance;
    public $input;
    public $paymentConfirmed;
    public $differenceInPrice;
    protected $session;
    public $newMembership = false;


    public function confirmPayment()
    {
        if($this->registration) {
            // Controleren of de verzekering is geselecteerd en opslaan in de sessie
            if(isset($this->input['insurance'])) {
                session(['insurance' => $this->input['insurance']]);
            } else {
                session(['insurance' => false]);
            }
            return redirect()->route('register');
        }
        elseif ($this->order) {
            session()->forget('selected_coupon_id');

            session()->forget('amount');




            $user = auth()->user();
            $order = Order::where('user_id', $user->id)
                ->where('hasPaid', false)->first();
            // De bestelling als betaald meegeven en initialiseren van de datum
            $order->hasPaid = true;
            $order->date = now();
            $order->save();
            $this->sendmail2(Auth::user()->email,Auth::user()->first_name);
            // Bijwerken van de voorraad van de artikelen in de bestelling
            foreach ($order->articleOrders as $articleOrder) {
                $article = $articleOrder->article;
                $article->stock -= $articleOrder->amount;
                $article->save();
            }
            $pointUsers = PointUser::where('user_id',$user->id)->get();
            $totalPoints = 0;
            foreach ($pointUsers as $pointUser) {
                $totalPoints += $pointUser->points;
            }
            $coupon = session()->get('coupon');


            if ($coupon) {
                $amountPoint = $coupon->amount_point;

                $totalPoints -= $amountPoint;



            }
            $updated = false;
            foreach ($pointUsers as $pointUser) {
                if ($pointUser->points > 0 && !$updated) {
                    $pointUser->points = $totalPoints;
                    $updated = true;
                } else {
                    $pointUser->points = 0;
                }
                $pointUser->save();
            }

            // Doorverwijzen naar de kledingbestelpagina met succesbericht
            return redirect()->route('kledij-bestellen')->with('message', 'Je bestelling is succesvol geplaatst!');

        }
        //gebruiker heeft nieuw lidmaatschap betaald
        else {
            //sendmail function
            $this->sendmail(Auth::user()->email,Auth::user()->first_name);
            // gewoon lidmaatschap of met verzekering?
            if(isset($this->input['insurance'])) {
                $membershipId = ($this->input['insurance']) ? 2 : 1;
            } else {
                $membershipId = 1;
            }
            // nieuwe verbinding lidmaatschap-gebruiker aanmaken
            MembershipUser::create(
                [
                    'membership_id' => $membershipId,
                    'season_id' => Season::where('active', true)->value('id'),
                    'user_id' => auth()->user()->id,
                ]
            );
            $goingToUrl = session()->pull('redirect.from', null);
            if ($goingToUrl !== null) {
                // verder navigeren naar de pagina waar de gebruiker origineel naartoe ging
                return redirect($goingToUrl);
            }
            // geen url => ga naar homepagina
            return redirect()->route('home');
        }

    }
//mails versturen via de class 'mailing.php'
    public function sendmail($mail,$name) {
        $inschrijvenEvenement = new Mailing();
        $inschrijvenEvenement->sendWelcome($mail,$name);
        Mailing::sendWelcome($mail,$name);
    }

    public function sendmail2($mail,$name) {
        $inschrijvenEvenement = new Mailing();
        $inschrijvenEvenement->sendShopMail($mail,$name);
        Mailing::sendShopMail($mail,$name);
    }

    public function cancelPayment(){
        if ($this->order) {
            return redirect()->route('showcart');
        }
        else {
            return redirect()->route('home');
        }
    }

    public function updateSessionValue()
    {
        session(['insurance' => $this->input['insurance']]);
    }


    public function mount(Request $request, Store $session)
    {
        $this->membership = Membership::where('name', 'Lidmaatschap')->first();
        $this->insurance = Membership::where('name', 'Verzekering')->first();
        $standardPrice = $this->membership->price;
        $insurancePrice = $this->insurance->price;
        $this->differenceInPrice = $insurancePrice - $standardPrice;
        $this->session = $session;

        // get the route
        $route = app('router')->getRoutes()->match(app('request')->create(url()->previous()))->getName();

        if ($route != '/' && !auth()->check())
        {
            $this->registration = true;
        }
        elseif ($route == 'showcart')
        {
            $this->order = true;
        }
        // als de user geredirect is naar deze pagina (door de Membership middleware)
        elseif ($this->session->has('redirected')) {
            $this->newMembership = true;
        }
        else
        {
//            return redirect('/');
            return abort(403, 'Geen toegang');
        }
        return '';
    }


    public function render()
    {


        if ($this->order){
            $user = auth()->user();
            $carts = ArticleOrder::with('order','article','article.garment','article.size')->whereHas('order',function ($query) use ($user){
                $query->where('user_id',$user->id)->where('hasPaid', false);})->get();
            $count = 0;
            $user = auth()->user();
            if ($user) {
                $activeOrder = Order::where('user_id', $user->id)->where('hasPaid', false)->first();
                if ($activeOrder) {
                    $count = $activeOrder->articleOrders()->sum('amount');
                }
            }
            $sum = $carts->sum('article.garment.price');

            return view('livewire.shared.payment', compact('carts', 'sum', 'count'))
                ->layout('layouts.projectPHP', [
                    'description' => 'Betalingspagina',
                    'title' => 'Betaal pagina'
                ]);
        } else {
            // Stel de prijs in op het standaard lidmaatschapsbedrag
            $price = $this->membership->price;
            // Controleren of er invoer is en of verzekering(wbv) is geselecteerd
            if (!is_null($this->input) && array_key_exists('insurance', $this->input) && $this->input['insurance']) {
                // Stel de prijs in op het verzekeringsbedrag
                $price = $this->insurance->price;
            }

            return view('livewire.shared.payment', [
                'membership' => $this->membership,
                'input' => $this->input,
                'price' => $price
            ])
                ->layout('layouts.projectPHP', [
                    'description' => 'Betalingspagina',
                    'title' => 'Betaal pagina'
                ]);
        }


    }
}
