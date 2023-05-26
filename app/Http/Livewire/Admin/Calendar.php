<?php

namespace App\Http\Livewire\Admin;

use App\Models\Event;
use App\Models\Tour;
use App\Models\UserEvent;
use Livewire\Component;

class Calendar extends Component
{


    public function render()
    {

        $events = [];

        $appointments = Event::all();
        foreach($appointments as $appointment){
            $start = $appointment->start_date.'T'.$appointment->departure_time;
            $events[]=[
                'id' => $appointment->id,
                'title' => $appointment->name,
                'start'=>$appointment->start_date,
                'end'=>$appointment->end_date,
                'location'=>$appointment->location,
                'description'=>$appointment->description,
                'color'=>'lightgreen',
            ];
        }
        $tours = Tour::all();
        foreach($tours as $tour){
            $events[]=[
                'id'=>$tour->id,
                'title' => $tour->name,
                'start'=>$tour->date.'T'.$tour->departure_time.'Z',
                'location'=>$tour->location,
                'description'=>$tour->description,
                'distance'=>$tour->distance,
                'ritverkenner_first_name'=>$tour->user->first_name,
                'ritverkenner_last_name'=>$tour->user->last_name,
                'color'=>'red'
            ];
        }
        return view('livewire.admin.calendar',compact('events','appointments','tours'))
            ->layout('layouts.projectPHP', [
                'description' => 'Bekijk de kalender hier',
                'title' => 'Kalender',
            ]);
    }
}
