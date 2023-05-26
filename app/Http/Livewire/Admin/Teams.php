<?php

namespace App\Http\Livewire\Admin;

use App\Models\Team;
use Livewire\Component;
use Livewire\WithPagination;

class Teams extends Component
{
    // attributes
    use WithPagination;
    public $perPage = 8;
    public $orderBy = 'id';
    public $orderAsc = true;
    public $showModal = false;
    public $name;
    public $newTeam = [
        'id' => null,
        'name' =>null,
        'speed_aim'=>null
    ];

    // validation rules (use the rules() method, not the $rules property)
    protected function rules()
    {
        return [
            'newTeam.name' => 'required|min:3|max:30|unique:teams,name,' . $this->newTeam['id'],
            'newTeam.speed_aim' => 'required|min:1|max:30',
        ];
    }

    // validation attributes
    protected $validationAttributes = [
        'newTeam.name' => 'naam',
        'newTeam.speed_aim' => 'verwachte snelheid',
    ];

    // listeners
    protected $listeners = [
        'delete-team' => 'deleteTeam'
    ];

    //functions
    // reset team
    public function resetNewTeam()
    {
        $this->reset('newTeam');
        $this->resetErrorBag();
    }

    // create team
    public function createTeam(){

        $this->validate( $this->rules());

        $team = Team::create([
            'id' => trim($this->newTeam['id']),
            'name' => trim($this->newTeam['name']),
            'speed_aim' =>($this->newTeam['speed_aim']),
        ]);

        $this->resetNewTeam();
        $this->showModal = false;

        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "Het team: <b><i>{$team->name}</i></b> is toegevoegd",
        ]);
    }

    // delete a team
    public function deleteTeam(Team $team)
    {
        $team->delete();
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'danger',
            'html' => "Het team: <b><i>{$team->name}</i></b> is verwijderd",
        ]);
    }

    // set a new team
    public function setNewTeam(Team $team = null)
    {
        $this->resetErrorBag();

        if ($team) {
            $this->newTeam['id'] = $team->id;
            $this->newTeam['name'] = $team->name;
            $this->newTeam['speed_aim'] = $team->speed_aim;
        } else {
            $this->reset('newTeam');
        }
        $this->showModal = true;
    }

    // update a team
    public function updateTeam(Team $team)
    {
        $this->validate($this->rules());

        $team->update([
            'id' => $this->newTeam['id'],
            'name' => $this->newTeam['name'],
            'speed_aim' => $this->newTeam['speed_aim'],
        ]);

        $this->showModal = false;
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "De ploeg: <b><i>{$team->name} </i></b> is bijgewerkt",
        ]);
    }

    //venster openen om een nieuw team te maken.
    public function showTeam()
    {
        $this->reset('newTeam');
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

    public function render()
    {
        $teams = Team::withCount('tours')
            ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')
            ->where([
                ['name', 'like', "%{$this->name}%"]
            ])
            ->paginate($this->perPage);
        return view('livewire.admin.teams', compact('teams'))
            ->layout('layouts.projectPHP', [
            'description' => 'Ploegen beheren',
            'title' => 'Ploegen beheren',
        ]);
    }
}
