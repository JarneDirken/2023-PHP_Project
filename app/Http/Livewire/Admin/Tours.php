<?php

namespace App\Http\Livewire\Admin;

use App\Models\Event;
use App\Models\Season;
use App\Models\Team;
use App\Models\Tour;
use App\Models\User;
use App\Models\UserTour;
use Livewire\Component;
use Livewire\WithPagination;

class Tours extends Component
{
    // attributes
    use WithPagination;
    public $perPage = 8;
    public $orderBy = 'date';
    public $orderAsc = false;

    public $name = '';
    public $location = '';
    public $selectedSeasonId;
    public $selectedTeamId;
    public $open = "All";

    public $showModal = false;
    public $teams;
    public $users;
    public $seasons;
    public $newTour =
        [
            'id' => null,
            'name' => null,
            'location' => null,
            'team_id' => null,
            'date'=>null,
            'user_id' => null,
            'departure_time'=>null,
            'distance'=>null,
            'description'=>null];

    public function rules()
    {
        return [
            'newTour.name' => 'required|min:3',
            'newTour.location' => 'required|min:3',
            'newTour.team_id' => 'required',
            'newTour.date' => 'required|date',
            'newTour.departure_time' => 'required',
            'newTour.distance' => 'required|numeric',
            ];
    }

    // validation attributes
    protected $validationAttributes = [
        'newTour.name' => 'naam',
        'newTour.location' => 'locatie',
        'newTour.team_id' => 'ploeg selectie',
        'newTour.date' => 'datum',
        'newTour.departure_time' => 'vertrek',
        'newTour.distance' => 'afstand',
    ];

    public function mount()
    {
        $this->teams = Team::orderBy('name')->get();
        $this->users = User::get();
        $this->seasons = Season::get();
    }

    // create a tour
    public function createTour()
    {
        $user_id = (trim($this->newTour['user_id']) == '') ? null : trim($this->newTour['user_id']);
        $this->newTour['team_id'] = $this->newTour['team_id'] == '' ? null : $this->newTour['team_id'];

        $this->validate();

        $tour = Tour::create([
            //'id' => trim($this->newTour['id']),
            'name' => trim($this->newTour['name']),
            'location' => trim($this->newTour['location']),
            'team_id' => trim($this->newTour['team_id']),
            'user_id' => $user_id,
            'date' => trim($this->newTour['date']),
            'departure_time' => trim($this->newTour['departure_time']),
            'distance'=> trim($this->newTour['distance']),
            'description'=> trim($this->newTour['description']),
        ]);

        // alle gebruikers aan de rit koppelen
        foreach(User::get() as $user){
            UserTour::create([
                    'user_id' => $user->id,
                    'tour_id' => $tour->id
            ]);
        }


        $this->showModal = false;
        $this->resetNewTour();
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "De rit <b><i>{$tour->name}</i></b> is toegevoegd",
        ]);
    }

    // set a new tour
    public function setNewTour(Tour $tour = null)
    {
        $this->resetErrorBag();
        if ($tour) {
            $this->newTour['id'] = $tour->id;
            $this->newTour['name'] = $tour->name;
            $this->newTour['location'] = $tour->location;
            $this->newTour['team_id'] = $tour->team_id;
            $this->newTour['user_id'] = $tour->user_id;
            $this->newTour['date'] = $tour->date;
            $this->newTour['departure_time'] = $tour->departure_time;
            $this->newTour['distance'] = $tour->distance;
            $this->newTour['description'] = $tour->description;

        } else {
            $this->reset('newTour');
        }
        $this->showModal = true;
    }

    // update the existing tour
    public function updateTour(Tour $tour)
    {
        $this->newTour['team_id'] = $this->newTour['team_id'] == '' ? null : $this->newTour['team_id'];
        $this->newTour['user_id'] = $this->newTour['user_id'] == '' ? null : $this->newTour['user_id'];

        $this->validate();
        $tour->update([
            'name' => $this->newTour['name'],
            'location' => $this->newTour['location'],
            'team_id' => $this->newTour['team_id'],
            'user_id' => $this->newTour['user_id'],
            'date' => $this->newTour['date'],
            'departure_time' => $this->newTour['departure_time'],
            'distance'=> $this->newTour['distance'],
            'description'=> $this->newTour['description'],
        ]);
        $this->showModal = false;
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "De rit <b><i>{$tour->name} </i></b> is bijgewerkt",
        ]);
    }

    // delete a tour
    public function deleteTour(Tour $tour)
    {
        $tour->delete();
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'danger',
            'html' => "De rit <b><i>{$tour->name} </i></b> is verwijderd",
        ]);
    }

    // listener for the event delete-tour
    protected $listeners = [
        'delete-tour' => 'deleteTour',
    ];

    // reset the new tour
    public function resetNewTour(){
        $this->resetErrorBag();
        $this->reset('newTour');
    }

    // sorting
    public function resort($column)
    {
        if ($this->orderBy === $column) {
            $this->orderAsc = !$this->orderAsc;
        } else {
            $this->orderAsc = true;
        }
        $this->orderBy = $column;
    }
    public function render()
    {
        $query = Tour::where("name","like","%{$this->name}%")
                        ->where("location","like","%{$this->location}%");
        $selectedSeason = Season::find($this->selectedSeasonId);
        if($selectedSeason){
            $query->whereBetween('date', [$selectedSeason->start_date, $selectedSeason->end_date]);
        }
        if($this->selectedTeamId != 0){
            $query->where('team_id', $this->selectedTeamId);
        }
        if($this->open == 'Open'){
            $query->where('open', true);
        } else if ($this->open == 'Closed'){
            $query->where('open', false);
        }
        $tours = $query->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')->paginate($this->perPage);
        return view('livewire.admin.tours', compact('tours'))
            ->layout('layouts.projectPHP', [
                'description' => 'Beheer ritten',
                'title' => 'Geplande ritten',
            ]);
    }

    // reset the paginator
    public function updated($propertyName, $propertyValue)
    {
        if (in_array($propertyName, ['name', 'location', 'selectedSeasonId', 'selectedTeamId', 'open']))
            $this->resetPage();
    }

    // reset the filter
    public function resetFilter()
    {
        $this->reset(['selectedSeasonId', 'selectedTeamId', 'name', 'location', 'open']);
    }

}

