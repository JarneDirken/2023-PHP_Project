<?php

namespace App\Http\Livewire\Admin;

use App\Models\Season;
use Livewire\Component;
use Livewire\WithPagination;

class Seasons extends Component
{
    // attributes
    use WithPagination;
    public $perPage = 6;
    public $orderBy = 'start_date';
    public $orderAsc = true;
    public $showModal = false;
    public $newSeason =
        [
            'id' => null,
            'start_date' => null,
            'end_date' => null,
            'active' => null
        ];

    // validation rules (use the rules() method, not the $rules property)
    protected function rules()
    {
        return [
            'newSeason.start_date' => [
                'required',
                'date',
                'before:newSeason.end_date',
                'date_format:Y-m-d',
                // start date HAS to be the first of january
                // the end date HAS to be the 31 of december
                // start date has to come before the end date
                function ($attribute, $value, $fail) {
                    $year = date('Y', strtotime($value));
                    $startOfYear = $year . '-01-01';
                    $endOfYear = $year . '-12-31';
                    if ($value != $startOfYear) {
                        $fail('De startdatum moet altijd 01-01 zijn.');
                    }
                    if ($this->newSeason['end_date'] != $endOfYear) {
                        $fail('De einddatum moet altijd 31-12 zijn.');
                    }
                },
            ],
            'newSeason.end_date' => [
                'required',
                'date',
                'after:newSeason.start_date',
                'date_format:Y-m-d',
            ],
            'newSeason.active' => [
                function ($attribute, $value, $fail) {
                    // Check if another season is already active
                    if ($value) {
                        $activeSeasonsCount = Season::where('active', true)->count();
                        if ($activeSeasonsCount > 0) {
                            $fail('Er kan maar 1 seizoen actief zijn.');
                        }
                    }
                },
            ],
        ];
    }

    // validation attributes
    protected $validationAttributes = [
        'newSeason.start_date' => 'startdatum',
        'newSeason.end_date' => 'einddatum',
        'newSeason.active' => 'activiteit',
    ];

    // listeners
    protected $listeners = [
        'delete-season' => 'deleteSeason'
    ];

    //functions
    // create season
    public function createSeason(){

        $this->validate( $this->rules());

        $season = Season::create([
            'id' => trim($this->newSeason['id']),
            'start_date' => trim($this->newSeason['start_date']),
            'end_date' => trim($this->newSeason['end_date']),
            'active' => boolval($this->newSeason['active'])
        ]);

        $this->resetNewSeason();
        $this->showModal = false;

        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "Het seizoen start <b><i>{$season->start_date}</i></b><br>
                        en eindigd <b><i>{$season->end_date}</i></b> is toegevoegd",
        ]);
    }
    // delete a season
    public function deleteSeason(Season $season)
    {
        $season->delete();
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'danger',
            'html' => "Het seizoen dat begint <br>
                        <b><i>{$season->start_date}</i></b> en eindigd <b><i>{$season->end_date}</i></b> is verwijderd",
        ]);
    }

    // set a new season
    public function setNewSeason(Season $season = null)
    {
        $this->resetErrorBag();

        if ($season) {
            $this->newSeason['id'] = $season->id;
            $this->newSeason['start_date'] = $season->start_date;
            $this->newSeason['end_date'] = $season->end_date;
            $this->newSeason['active'] = $season->active;
        } else {
            $this->reset('newSeason');
        }
        $this->showModal = true;
    }

    // update the season
    public function updateSeason(Season $season)
    {
        $this->validate($this->rules());

        $season->update([
            'id' => $this->newSeason['id'],
            'start_date' => $this->newSeason['start_date'],
            'end_date' => $this->newSeason['end_date'],
            'active' => $this->newSeason['active'],
        ]);

        $this->showModal = false;
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "Het seizoen <b><i>{$season->start_date} </i></b> is bijgewerkt",
        ]);
    }

    // reset season
    public function resetNewSeason()
    {
        $this->reset('newSeason');
        $this->resetErrorBag();
    }

    // show season creating popup
    public function showSeason()
    {
        $this->reset('newSeason');
        $this->showModal = true;
        $this->resetErrorBag();
    }

    public function resort($column)
    {
        if ($this->orderBy === $column) {
            $this->orderAsc = !$this->orderAsc;
        } else {
            $this->orderAsc = true;
        }
        $this->orderBy = $column;
    }

    // the render
    public function render()
    {
        $seasons = Season::orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')
            ->paginate($this->perPage);
        return view('livewire.admin.seasons', compact('seasons'))
            ->layout('layouts.projectPHP', [
                'description' => 'Het beheren van de seizoenen',
                'title' => 'Seizoenen beheren'
            ]);
    }
}
