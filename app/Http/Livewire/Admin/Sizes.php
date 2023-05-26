<?php

namespace App\Http\Livewire\Admin;

use App\Models\Size;
use Livewire\Component;

class Sizes extends Component
{
    // attributes
    public $showModal = false;

    public $newSize = [
        'id' => null,
        'name' => null,
    ];

    // validation rules
    protected function rules()
    {
        return [
            'newSize.name' => 'required',
        ];
    }

    // validation attributes
    protected $validationAttributes = [
        'newSize.name' => 'naam',
    ];

    // set/reset $newSize and validation
    public function setNewSize(Size $size = null)
    {
        $this->resetErrorBag();
        if ($size) {
            $this->newSize['id'] = $size->id;
            $this->newSize['name'] = $size->name;
        } else {
            $this->reset('newSize');
        }
        $this->showModal = true;
    }

    // create a new size
    public function createSize()
    {
        $this->validate();
        $size = Size::create([
            'name' => $this->newSize['name'],
        ]);
        $this->showModal = false;
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "De maat <b><i>{$size->name}</i></b> is toegevoegd",
        ]);
    }

    // update an existing size
    public function updateSize(Size $size)
    {
        $this->validate();
        $size->update([
            'name' => $this->newSize['name'],
        ]);
        $this->showModal = false;
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "De maat <b><i>{$size->name}</i></b> is aangepast",
        ]);
    }

    // delete an existing size
    public function deleteSize(Size $size)
    {
        $size->delete();
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'danger',
            'html' => "De maat <b><i>{$size->name}</i></b> is verwijderd",
        ]);
    }

    public function render()
    {
        $sizes = Size::get();
        return view('livewire.admin.sizes', compact('sizes'))
            ->layout('layouts.projectPHP', [
                'description' => 'Beheer hier de maten',
                'title' => 'Maten beheren',
            ]);
    }

    // listen to the delete-size event
    protected $listeners = [
        'delete-size' => 'deleteSize',
    ];
}
