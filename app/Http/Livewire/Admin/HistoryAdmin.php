<?php

namespace App\Http\Livewire\Admin;

use App\Models\ArticleOrder;
use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class HistoryAdmin extends Component
{
    use WithPagination;
    public $perPage = 3;
    public function render()
    {

        $orders = Order::where('hasPaid', true)
            ->with('user')
            ->paginate($this->perPage);
        $orderIds = $orders->pluck('id');
        $carts = ArticleOrder::with('order', 'article', 'article.garment', 'article.size')
            ->whereIn('order_id', $orderIds)
            ->get();



        return view('livewire.admin.history-admin',compact('orders','carts'))
            ->layout('layouts.projectPHP', [
                'description' => 'Bestellingen geschiedenis',
                'title' => 'Overzicht bestellingen'
            ]);
    }
}
