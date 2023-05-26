<?php

namespace App\Http\Livewire;

use App\Models\Team;
use App\Models\Tour;
use Auth;
use Livewire\Component;
use Livewire\WithPagination;

class InschrijvenRitverkenner extends Component
{
    use WithPagination;

    public $perPage = 4;
    public $search;
    public $location;
    public $teamSearch = "0";
    public $orderBy = "name";
    public $orderAsc = true;
    public $begin_date;
    public $end_date;

    public $showModal = false;
    public $selectedTour;

    public $teams;

    public function render()
    {
        $query = Tour::where('user_id', null)
            ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')
            ->where('name', 'like', "%$this->search%")
            ->where('location', 'like', "%$this->location%")
            ->where('open', true);

        if (!empty($this->begin_date) && !empty($this->end_date)) {
            $query->whereBetween('date', [$this->begin_date, $this->end_date]);
        } elseif (!empty($this->begin_date)) {
            $query->where('date', '>=', $this->begin_date);
        } elseif (!empty($this->end_date)) {
            $query->where('date', '<=', $this->end_date);
        }

        if($this->teamSearch != "0"){
            $query->where('team_id', 'like', "$this->teamSearch");
        }

        $tours = $query->paginate($this->perPage);

        //ritten waarbij de gebruiker ritverkenner is (om uit te schrijven)
        $toursTourGuide = Tour::where('user_id', Auth::user()->id)->get();

        return view('livewire.inschrijven-ritverkenner', compact(['tours', 'toursTourGuide']))
            ->layout('layouts.projectPHP', [
                'description' => 'Inschrijven als ritverkenner.',
                'title' => 'Ritverkenner worden'
            ]);
    }

    public function mount()
    {
        $this->teams = Team::get();

        $calenderClickTourId = request()->query('id');
        if($calenderClickTourId){
            $tour = Tour::where('open', true)
                ->where('user_id',null)->find($calenderClickTourId);
            if(!is_null($tour)){
                //$this->search = $tour->name;
                $this->showInfo($tour);
            }
        }
    }

    public function resetFilter()
    {
        $this->reset(['search', 'teamSearch', 'begin_date', 'end_date', 'location']);
    }

    // resort the genres by the given column
    public function resort($column)
    {
        if ($this->orderBy === $column) {
            $this->orderAsc = !$this->orderAsc;
        } else {
            $this->orderAsc = true;
        }
        $this->orderBy = $column;
    }

    // reset the paginator
    public function updated($propertyName, $propertyValue)
    {
        if (in_array($propertyName, ['search', 'teamSearch', 'location', 'begin_date', 'end_date']))
            $this->resetPage();
    }

    public function showInfo(Tour $tour)
    {
        $this->selectedTour = $tour;
        $this->showModal = true;
    }

    public function becomeTourGuide()
    {
        $tour_id = $this->selectedTour->id;
        Tour::where('id', 'like', "$tour_id")
        ->update([
            'user_id' => Auth::user()->id
        ]);
        $this->showModal = false;

        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "Ingeschreven als ritverkenner voor <b><i>{$this->selectedTour->name}</i></b>",
        ]);

        $this->reset("selectedTour");
        $this->resetPage();
    }

    public function removeTourGuide(Tour $tour)
    {
        $tour->update([
            'user_id' => null
        ]);
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'info',
            'html' => "Uitgeschreven als ritverkenner voor <b><i>{$tour->name}</i></b>",
        ]);
    }
}
