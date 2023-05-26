<?php

namespace App\Http\Livewire\Admin;

use App\Models\Membership;
use Livewire\Component;

class Memberships extends Component
{
    // attributes
    public $orderBy = 'name';
    public $orderAsc = true;
    public $showModal = false;
    public $name;
    public $newMembership =
        [
            'id' => null,
            'name' => null,
            'price' => null
        ];

    // validation rules (use the rules() method, not the $rules property)
    protected function rules()
    {
        return [
            'newMembership.name' => 'required|min:3|max:30|unique:memberships,name,' . $this->newMembership['id'],
            'newMembership.price' => 'required|numeric|min:1' . $this->newMembership['id'],
        ];
    }

    // validation attributes
    protected $validationAttributes = [
        'newMembership.name' => 'naam',
        'newMembership.price' => 'prijs',
    ];

    // listeners
    protected $listeners = [
        'delete-membership' => 'deleteMembership'
    ];

    //functions
    // create membership
    public function createMembership(){

        $this->validate( $this->rules());

        $membership = Membership::create([
            'name' => trim($this->newMembership['name']),
            'price' =>floatval($this->newMembership['price']),
        ]);

        $this->resetNewMembership();
        $this->showModal = false;

        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "De membership <b><i>{$membership->name}</i></b> is toegevoegd<br>
                        met als prijs: <b><i>€{$membership->price}</i></b>",
        ]);
    }
    // delete a membership
    public function deleteMembership(Membership $membership)
    {
        $membership->delete();
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'danger',
            'html' => "de membership <b><i>{$membership->name}</i></b> is verwijderd",
        ]);
    }

    // set the new membership
    public function setNewMembership(Membership $membership = null)
    {
        $this->resetErrorBag();

        if ($membership) {
            $this->newMembership['id'] = $membership->id;
            $this->newMembership['name'] = $membership->name;
            $this->newMembership['price'] = $membership->price;
        } else {
            $this->reset('newMembership');
        }
        $this->showModal = true;
    }

    // update the membership
    public function updateMembership(Membership $membership)
    {
        $this->validate($this->rules());

        $membership->update([
            'id' => $this->newMembership['id'],
            'name' => $this->newMembership['name'],
            'price' => $this->newMembership['price'],
        ]);

        $this->showModal = false;
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "De membership <b><i>{$membership->name} </i></b> is bijgewerkt",
        ]);
    }

    // reset membership
    public function resetNewMembership()
    {
        $this->reset('newMembership');
        $this->resetErrorBag();
    }

    // show membership creating popup
    public function showMembership()
    {
        $this->reset('newMembership');
        $this->showModal = true;
        $this->resetErrorBag();
    }

    // the render
    public function render()
    {
        $memberships = Membership::orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')
            ->where([
                ['name', 'like', "%{$this->name}%"]
            ])
            ->get();
        return view('livewire.admin.memberships', compact('memberships'))
            ->layout('layouts.projectPHP', [
                'description' => 'Het beheren van de lidmaatschappen',
                'title' => 'Lidmaatschappen beheren'
            ]);
    }
}
