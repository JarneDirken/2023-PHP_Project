<?php

namespace App\Http\Livewire;

use App\Models\ArticleOrder;
use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;
class History extends Component
{
    use WithPagination;
    public $perPage = 3;
    public function render()
    {
        $user = auth()->user();
        $orders = Order::where('user_id', $user->id)
            ->where('hasPaid', true)
            ->paginate($this->perPage);
        $orderIds = $orders->pluck('id');
        $carts = ArticleOrder::with('order', 'article', 'article.garment', 'article.size')
            ->whereIn('order_id', $orderIds)
            ->get();

        return view('livewire.history',compact('orders', 'carts'))
            ->layout('layouts.projectPHP', [
                'description' => 'Bestellingen geschiedenis',
                'title' => 'Bestellingen'
            ]);
    }
}
