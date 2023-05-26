<?php

namespace App\Http\Livewire\Admin;

use App\Models\Faq;
use Livewire\Component;
use Livewire\WithPagination;

class Faqs extends Component
{
    // attributes
    use WithPagination;
    public $perPage = 6;
    public $orderAsc = true;
    public $orderBy = 'id';
    public $showModal = false;
    public $question;
    public $newFaq = [
        'id' => null,
        'question' =>null,
        'answer'=>null
    ];

    // validation rules (use the rules() method, not the $rules property)
    protected function rules()
    {
        return [
            'newFaq.question' => 'required|unique:faqs,question',
            'newFaq.answer' => 'required'
        ];
    }

    // validation attributes
    protected $validationAttributes = [
        'newFaq.question' => 'vraag',
        'newFaq.answer' => 'antwoord'
    ];

    // listeners
    protected $listeners = [
        'delete-faq' => 'deleteFaq'
    ];

    //functions
    // reset faq
    public function resetNewFaq()
    {
        $this->reset('newFaq');
        $this->resetErrorBag();
    }

    // create faq
    public function createFaq(){

        $this->validate( $this->rules());

        $faq = Faq::create([
            'id' => trim($this->newFaq['id']),
            'question' => trim($this->newFaq['question']),
            'answer' =>($this->newFaq['answer']),
        ]);

        $this->resetNewFaq();
        $this->showModal = false;

        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "De vraag: <b><i>{$faq->question}</i></b> is toegevoegd",
        ]);
    }

    // delete a faq
    public function deleteFaq(Faq $faq)
    {
        $faq->delete();
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'danger',
            'html' => "De vraag: <b><i>{$faq->question}</i></b> is verwijderd",
        ]);
    }

    // set the new faq
    public function setNewFaq(Faq $faq = null)
    {
        $this->resetErrorBag();

        if ($faq) {
            $this->newFaq['id'] = $faq->id;
            $this->newFaq['question'] = $faq->question;
            $this->newFaq['answer'] = $faq->answer;
        } else {
            $this->reset('newFaq');
        }
        $this->showModal = true;
    }

    // update the faq
    public function updateFaq(Faq $faq)
    {
        $this->validate($this->rules());

        $faq->update([
            'id' => $this->newFaq['id'],
            'question' => $this->newFaq['question'],
            'answer' => $this->newFaq['answer'],
        ]);

        $this->showModal = false;
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "De vraag: <b><i>{$faq->question} </i></b> is bijgewerkt",
        ]);
    }

    // show faq creating popup
    public function showFaq()
    {
        $this->reset('newFaq');
        $this->showModal = true;
        $this->resetErrorBag();
    }

    public function render()
    {
        $faqs = Faq::orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')
            ->where([
                ['question', 'like', "%{$this->question}%"]
            ])
            ->paginate($this->perPage);
        return view('livewire.admin.faqs', compact('faqs'))
            ->layout('layouts.projectPHP', [
                'description' => 'Faq pagina beheren.',
                'title' => 'FAQ pagina beheren'
            ]);
    }
}
