<?php

namespace App\Http\Livewire\Admin;

use App\Models\Garment;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class Garments extends Component
{
    use WithFileUploads;
    // attributes
    use WithPagination;
    public $perPage = 8;
    public $photo;
    public $photoURL;
    public $orderBy = 'name';
    public $orderAsc = true;
    public $showModal = false;
    public $name;
    public $photoUploaded = false;

    public $newGarment =
        [
            'id' => null,
            'name' => null,
            'price' => null,
            'description' => null,
            'active' => null,
            'url' => null
        ];

    // validation rules (use the rules() method, not the $rules property)
    protected function rules()
    {
        return [
            'newGarment.name' => 'required|min:3|max:30|unique:garments,name,' . $this->newGarment['id'],
            'newGarment.price' => 'required|numeric|min:1',
            'newGarment.description' => 'required'
        ];
    }

    // validation attributes
    protected $validationAttributes = [
        'newGarment.name' => 'naam',
        'newGarment.price' => 'prijs',
        'newGarment.description' => 'beschrijving',
    ];

    // listeners
    protected $listeners = [
        'delete-garment' => 'deleteGarment'
    ];

    //functions
    //save photo
    public function save()
    {
        $this->validate([
            'photo' => 'image|max:1024', // 1MB Max
        ]);

        $storedPath = '/' .ltrim($this->photo->store('/storage/webshop-photos', 'real_public'));
        $this->photoURL = $storedPath;
        $this->photoUploaded = true;
    }
    // create garment
    public function createGarment(){

        $this->validate( $this->rules());

        $garment = Garment::create([
            'id' => trim($this->newGarment['id']),
            'name' => trim($this->newGarment['name']),
            'price' =>floatval($this->newGarment['price']),
            'description' => trim($this->newGarment['description']),
            'active' => boolval($this->newGarment['active']),
            'url' => trim($this->photoURL)
        ]);

        $this->resetNewGarment();
        $this->showModal = false;

        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "Het kledingstuk <b><i>{$garment->name}</i></b> is toegevoegd<br>
                        met als prijs: <b><i>€{$garment->price}</i></b>",
        ]);
    }
    // delete a garment
    public function deleteGarment(Garment $garment)
    {
        // Delete the associated picture file
        Storage::disk('real_public')->delete($garment->url);
        $garment->delete();
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'danger',
            'html' => "Het kledingstuk <b><i>{$garment->name}</i></b> is verwijderd",
        ]);
    }

    // set a new garment
    public function setNewGarment(Garment $garment = null)
    {
        $this->photoUploaded = false;
        $this->resetErrorBag();

        if ($garment) {
            $this->newGarment['id'] = $garment->id;
            $this->newGarment['name'] = $garment->name;
            $this->newGarment['price'] = $garment->price;
            $this->newGarment['description'] = $garment->description;
            $this->newGarment['active'] = $garment->active;
            $this->photoURL = $garment->url;
        } else {
            $this->reset('newGarment');
        }
        $this->showModal = true;
    }

    // update a garment
    public function updateGarment(Garment $garment)
    {
        $this->validate($this->rules());

        // Check if a new image is uploaded
        if ($this->photo) {
            // Delete the previous image if it exists
            if ($garment->url) {
                Storage::disk('real_public')->delete($garment->url);
            }

            // Save the new image
            $this->photoURL = $this->photo->store('/storage/webshop-photos', 'real_public');
        }

        $garment->update([
            'id' => $this->newGarment['id'],
            'name' => $this->newGarment['name'],
            'price' => $this->newGarment['price'],
            'description' => $this->newGarment['description'],
            'active' => $this->newGarment['active'],
            'url' => $this->photoURL
        ]);

        $this->showModal = false;
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "Het kledingstuk <b><i>{$garment->name} </i></b> is bijgewerkt",
        ]);
    }

    // reset garment
    public function resetNewGarment()
    {
        $this->reset('newGarment');
        $this->resetErrorBag();
    }

    // show garments creating popup
    public function showGarment()
    {
        $this->reset('newGarment');
        $this->showModal = true;
        $this->resetErrorBag();
    }

    //the render
    public function render()
    {
        $garments = Garment::orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')
            ->where([
                ['name', 'like', "%{$this->name}%"]
            ])
            ->paginate($this->perPage);
        return view('livewire.admin.garments', compact('garments'))
            ->layout('layouts.projectPHP', [
                'description' => 'Het beheren van de kledij',
                'title' => 'Kledij beheren'
            ]);
    }
}
