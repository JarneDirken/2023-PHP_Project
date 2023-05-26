<?php

namespace App\Http\Livewire;

use App\Models\Tour;
use Livewire\Component;
use Livewire\WithPagination;

class Home extends Component
{
    use WithPagination;
    public function render()
    {
        //variabelen om de opkomende ritten te tonen
        $currentDate = date('Y-m-d');
        $next30Days = date('Y-m-d', strtotime('+30 days'));

        $tours = Tour::where('date', '>', $currentDate)
            ->where('date', '<=', $next30Days)
            ->get();
        return view('livewire.home', compact('tours','currentDate'))

            ->layout('layouts.projectPHP', [
                'description' => 'Home pagina van de platte berg.',
                'title' => 'Home pagina'
            ]);
    }
}
