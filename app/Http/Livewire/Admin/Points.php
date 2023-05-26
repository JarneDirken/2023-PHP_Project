<?php

namespace App\Http\Livewire\Admin;

use App\Models\Point;
use Livewire\Component;

class Points extends Component
{
    // attributes
    public $orderBy = 'name';
    public $orderAsc = true;
    public $showModal = false;
    public $name;
    public $newPoint = ['id' => null,'name'=> null, 'amount'=>null,'maximum'=>null];

    // validation rules (use the rules() method, not the $rules property)
    public function rules()
    {
        return [
            'newPoint.name' => 'required|min:3|max:30|unique:points,name,' . $this->newPoint['id'],
            'newPoint.amount' => 'required|integer|lte:newPoint.maximum',
            'newPoint.maximum' => 'required|integer',
        ];
    }

    // validation attributes
    protected $validationAttributes = [
        'newPoint.name' => 'naam',
        'newPoint.amount' => 'hoeveelheid',
        'newPoint.maximum ' => 'maximum',
    ];

    // listeners
    protected $listeners = [
        'delete-point' => 'deletePoint'
    ];

    //functions
    // reset points
    public function resetNewPoint()
    {
        $this->reset('newPoint');
        $this->resetErrorBag();
    }

    // create point
    public function createPoint(){

        $this->validate( $this->rules());

        $point = Point::create([
            'id' => trim($this->newPoint['id']),
            'name' => trim($this->newPoint['name']),
            'amount' =>($this->newPoint['amount']),
            'maximum' =>($this->newPoint['maximum']),
        ]);

        $this->resetNewPoint();
        $this->showModal = false;

        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "De puntensoort: <b><i>{$point->name}</i></b> is toegevoegd",
        ]);
    }

    // delete a point
    public function deletePoint(Point $point)
    {
        $point->delete();
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'danger',
            'html' => "De puntensoort: <b><i>{$point->name}</i></b> is verwijderd",
        ]);
    }
    //Nieuwe point maken met de values van de user
    public function setNewPoint(Point $point = null)
    {
        $this->resetErrorBag();

        if ($point) {
            $this->newPoint['id'] = $point->id;
            $this->newPoint['name'] = $point->name;
            $this->newPoint['amount'] = $point->amount;
            $this->newPoint['maximum'] = $point->maximum;
        } else {
            $this->reset('newPoint');
        }
        $this->showModal = true;
    }

    // update a point with validation check
    public function updatePoint(Point $point)
    {
        $this->validate($this->rules());

        $point->update([
            'id' => $this->newPoint['id'],
            'name' => $this->newPoint['name'],
            'amount' => $this->newPoint['amount'],
            'maximum' => $this->newPoint['maximum'],
        ]);

        $this->showModal = false;
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "De puntensoort: <b><i>{$point->name} </i></b> is bijgewerkt",
        ]);
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
        $points = Point::orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')
            ->where([
                ['name', 'like', "%{$this->name}%"]
            ])
            ->get();
        return view('livewire.admin.points',compact('points'))
            ->layout('layouts.projectPHP', [
                'description' => 'Beheer de soorten punten hier',
                'title' => 'Punten beheren',
            ]);
    }
}
