<?php

namespace App\Http\Livewire\Admin;

use App\Models\Article;
use App\Models\Garment;
use App\Models\Size;
use Livewire\Component;
use Livewire\WithPagination;

class Articles extends Component
{
    use WithPagination;
    public $perPage = 12;
    public $sizes;
    public $garments;
    public $showModal = false;
    public $name;
    public $selectedArticle = '%';
    public $selectedSize = '%';

    public $newArticle = [
        'id' => null,
        'size_id' => null,
        'garment_id' => null,
        'stock' => null,
    ];

    // validation rules
    protected function rules()
    {
        return [
            'newArticle.size_id' => 'required',
            'newArticle.garment_id' => 'required',
            'newArticle.stock' => 'required',
        ];
    }

    // validation attributes
    protected $validationAttributes = [
        'newArticle.size_id' => 'maat',
        'newArticle.garment_id' => 'kleding',
        'newArticle.stock' => 'voorraad',
    ];

    // listen to the delete-article event
    protected $listeners = [
        'delete-article' => 'deleteArticle',
    ];

    // set/reset $newArticle and validation
    public function setNewArticle(Article $article = null)
    {
        $this->resetErrorBag();
        if ($article) {
            $this->newArticle['id'] = $article->id;
            $this->newArticle['size_id'] = $article->size_id;
            $this->newArticle['garment_id'] = $article->garment_id;
            $this->newArticle['stock'] = $article->stock;
        } else {
            $this->reset('newArticle');
        }
        $this->showModal = true;
    }

    // create a new article
    public function createArticle()
    {
        $this->validate();
        $article = Article::create([
            'size_id' => $this->newArticle['size_id'],
            'garment_id' => $this->newArticle['garment_id'],
            'stock' => $this->newArticle['stock'],
        ]);
        $this->showModal = false;
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "Het artikel is toegevoegd",
        ]);
    }

    // update an existing article
    public function updateArticle(Article $article)
    {
        $this->validate();
        $article->update([
            'size_id' => $this->newArticle['size_id'],
            'garment_id' => $this->newArticle['garment_id'],
            'stock' => $this->newArticle['stock'],
        ]);
        $this->showModal = false;
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "Het artikel is aangepast",
        ]);
    }

    // delete an existing article
    public function deleteArticle(Article $article)
    {
        $article->delete();
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'danger',
            'html' => "Het artikel is verwijderd",
        ]);
    }

    // get all the sizes and garments from the database (runs only once)
    public function mount()
    {
        $this->sizes = Size::get();
        $this->garments = Garment::orderBy('name')->get();
    }

    public function render()
    {
        $articles = Article::when($this->selectedArticle != '%', function ($query) {
            $query->where('garment_id', $this->selectedArticle);
        });
        $articles->when($this->selectedSize != '%', function ($query) {
            $query->where('size_id', $this->selectedSize);
        });
        $articles = $articles->paginate($this->perPage);

        $garments = Garment::all()->unique('name');
        $sizes = Size::all()->unique('name');
        return view('livewire.admin.articles', compact('articles', 'garments', 'sizes'))
            ->layout('layouts.projectPHP', [
                'description' => 'Beheer de artikels',
                'title' => 'Artikelen beheren',
            ]);
    }
}
