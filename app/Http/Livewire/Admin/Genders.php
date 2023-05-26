<?php

namespace App\Http\Livewire\Admin;

use App\Models\Gender;
use Livewire\Component;

class Genders extends Component
{
    // attributes
    public $orderBy = 'name';
    public $orderAsc = true;
    public $showModal = false;
    public $name;
    public $newGender =
        [
            'id' => null,
            'name' => null
        ];

    // validation rules (use the rules() method, not the $rules property)
    protected function rules()
    {
        return [
            'newGender.name' => 'required|min:1|max:30|unique:genders,name,' . $this->newGender['id'],
        ];
    }

    // validation attributes
    protected $validationAttributes = [
        'newGender.name' => 'naam'
     ];

    // listeners
    protected $listeners = [
        'delete-gender' => 'deleteGender'
    ];

    //functions
    // create gender
    public function createGender(){

        $this->validate( $this->rules());

        $gender = Gender::create([
            'id' => trim($this->newGender['id']),
            'name' => trim($this->newGender['name']),
        ]);

        $this->resetNewGender();
        $this->showModal = false;

        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "De gender <b><i>{$gender->name}</i></b> is toegevoegd",
        ]);
    }
    // delete a gender
    public function deleteGender(Gender $gender)
    {
        $gender->delete();
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'danger',
            'html' => "de gender <b><i>{$gender->name}</i></b> is verwijderd",
        ]);
    }

    // set the new gender
    public function setNewGender(Gender $gender = null)
    {
        $this->resetErrorBag();

        if ($gender) {
            $this->newGender['id'] = $gender->id;
            $this->newGender['name'] = $gender->name;
        } else {
            $this->reset('newGender');
        }
        $this->showModal = true;
    }

    // update the gender
    public function updateGender(Gender $gender)
    {
        $this->validate($this->rules());

        $gender->update([
            'id' => $this->newGender['id'],
            'name' => $this->newGender['name'],
        ]);

        $this->showModal = false;
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "De gender <b><i>{$gender->name} </i></b> is bijgewerkt",
        ]);
    }

    // reset gender
    public function resetNewGender()
    {
        $this->reset('newGender');
        $this->resetErrorBag();
    }

    // show gender creating popup
    public function showGender()
    {
        $this->reset('newGender');
        $this->showModal = true;
        $this->resetErrorBag();
    }

    // the render
    public function render()
    {
        $genders = Gender::orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')
            ->where([
                ['name', 'like', "%{$this->name}%"]
            ])
            ->get();
        return view('livewire.admin.genders', compact('genders'))
            ->layout('layouts.projectPHP', [
                'description' => 'Het beheren van de genders',
                'title' => 'Genders beheren'
            ]);
    }
}
