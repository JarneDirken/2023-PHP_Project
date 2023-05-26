<?php

namespace App\Http\Livewire\Admin;

use App\Models\Season;
use App\Models\Team;
use App\Models\Tour;
use App\Models\User;
use App\Models\UserTour;
use Livewire\Component;

class Statistics extends Component
{
    // attributes
    public $maxUser;
    public $heightMultiplier = 30;
    public $widthColumn = 80;
    public $spacingColumn;
    public $maxGraph;
    public $seasons;
    public $selectedSeasonId;
    public $teams;
    public $selectedTeamId;
    public $asc;
    public $search;

    public $infoModal = false;
    public $selectedTour = [
        "id" => null,
        "name" => null,
        "date" => null,
        "present_amount" => null,
        "team" => null,
        "usertours" => null,
        "participating_users" => null,
    ];

    public function render()
    {
        $query = Tour::where('open', false)
            ->where('name', 'like', "%$this->search%");
        $selectedSeason = Season::find($this->selectedSeasonId);
        if($selectedSeason){
            $query->whereBetween('date', [$selectedSeason->start_date, $selectedSeason->end_date]);
        }
        if($this->selectedTeamId != 0){
            $query->where('team_id', $this->selectedTeamId);
        }
        $tours = $query->get();
        //nadat de tours opgehaald zijn, ze nog filteren op het appended attribuut
        if($this->asc){
            $tours = $tours->sortBy(function ($object) {
                return $object->present_amount;
            });
        } else {
            $tours = $tours->sortByDesc(function ($object) {
                return $object->present_amount;
            });
        }
        // nu de maximum hoogte van de grafiek zetten (= het hoogste aantal aanwezigen afgerond tot meervoud van 5)
        $highestAmount = $tours->max(function ($object) {
            return $object->present_amount;
        });
        $this->maxGraph = ceil($highestAmount / 5) * 5;
        $this->heightMultiplier = ($this->maxGraph != 0) ? 300 / $this->maxGraph : 0;
        //ik wil dat grafiek altijd 300 in height is, dus op basis van het maximum moet ik * of / doen zodat het totaal 300 is
        //max = 10 -> 300/10 = 30
        //max = 15 -> 300/15 = 20
        //max = 5 -> 300/5 = 60

        return view('livewire.admin.statistics', compact('tours'))->layout('layouts.projectPHP', [
            'description' => 'Statistieken van de Platte Berg',
            'title' => 'Statistieken',
        ]);
    }

    // function only runs once at start
    public function mount()
    {
        $this->maxUser = User::count();
        //$this->maxUser = User::where('active', true)->count();
        $this->spacingColumn = $this->widthColumn / 4;
        $this->seasons = Season::get();
        $this->teams = Team::get();
    }

    // update
    public function updated($propertyName, $propertyValue)
    {
        // reset if the $search, $selectedSeasonId, $selectedTeamId or $asc property has changed (updated)
        if (in_array($propertyName, ['selectedSeasonId', 'selectedTeamId', 'search', 'asc']))
            $this->hideInfo();
    }

    // reset the filter
    public function resetFilter()
    {
        $this->reset(['selectedSeasonId', 'selectedTeamId', 'search', 'asc']);
    }

    // show the info
    public function showInfo(Tour $tour)
    {
        $this->infoModal = true;
        $this->selectedTour = [
            'id' => $tour->id,
            'name' => $tour->name,
            'date' => $tour->date,
            'present_amount' => $tour->present_amount,
            'team' => $tour->team,
            'usertours' => UserTour::with('user')->where('tour_id', 'like', $tour->id)->where('present',true)->get(),
            'participating_users' => UserTour::where('tour_id', 'like', $tour->id)->count()
        ];
    }

    // hide the info
    public function hideInfo()
    {
        $this->infoModal = false;
        $this->reset('selectedTour');
    }
}
