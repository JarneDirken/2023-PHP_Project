<?php

namespace App\Http\Livewire;

use App\Models\Article;
use App\Models\ArticleOrder;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Size;
use App\Models\User;
use App\Models\Garment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class KledijBestellen extends Component
{

    public $name;
    public $sizeId;


    public function addcart(Request $request,$id){
        $product = Garment::find($id);
        $size = $request->size;
        $user = auth()->user();
        $activeOrder = Order::where('user_id',$user->id) ->where('hasPaid', false)->first();
        if (!$activeOrder) {
            // Maak een nieuwe order aan als er geen actieve order is
            $order = new Order;
            $order->user_id = $user->id;
            $order->order_number = rand(100000, 999999);;


            $order->save();
        } else {
            $order = $activeOrder;// Gebruik de actieve bestelling als die al bestaat
        }
        $article = Article::where('garment_id', $product->id)
            ->where('size_id', $size)
            ->first();
        $articleOrder = ArticleOrder::where('order_id', $order->id)
            ->where('article_id', $article->id)
            ->first(); // Het controleren of er al een artikelorder bestaat voor het artikel in de huidige bestelling
        if ($articleOrder) {
            // Als de artikelorder al bestaat, verhoog dan de hoeveelheid
            $articleOrder->amount += 1;
            if ($article->stock < $articleOrder->amount) {
                // Niet genoeg voorraad, toon een foutmelding
                return back()->with('stockerr', 'Er is niet genoeg voorraad meer voor dit artikel.')->with('garmentId',$article->garment_id);
            }

            $articleOrder->save();
        } else {
            // Maak een nieuwe artikelorder aan als die nog niet bestaat
            $articleOrder = new ArticleOrder;
            $articleOrder->order_id = $order->id;
            $articleOrder->article_id = $article->id;
            $articleOrder->amount = 1;
            if ($article->stock < $articleOrder->amount) {
                // Niet genoeg voorraad, toon een foutmelding
                return back()->with('stockerr', 'Er is niet genoeg voorraad voor dit artikel.')->with('garmentId',$article->garment_id);
            }
            $articleOrder->save();
        }
        return redirect()->back()->with('message', 'Het kledingstuk, '.$article->garment->name.' ('.$article->size->name.') is toegevoegd aan je winkelmandje');
    }


    public function render()
    {

        $garments = Garment::with('articles')
            ->where([
                ['name', 'like', "%{$this->name}%"],
                ['active', true],
            ])
            ->get();
        $articles = Article::all();

        $sizes = Size::with('articles')->get();
        $count = 0;
        $user = auth()->user();
        if ($user) {
            // Controleren of er een actieve bestelling is voor de gebruiker en het totale aantal artikelen in de bestelling berekenen
            $activeOrder = Order::where('user_id', $user->id)->where('hasPaid', false)->first();
            if ($activeOrder) {
                $count = $activeOrder->articleOrders()->sum('amount');
            }
        }
        session(['count'=>$count]);

        return view('livewire.kledij-bestellen',compact('garments','count','sizes','articles'))
            ->layout('layouts.projectPHP', [
                'description' => 'Op deze pagina kan je je kledij bestellen',
                'title' => 'Kledij bestellen'
            ]);
    }
}

