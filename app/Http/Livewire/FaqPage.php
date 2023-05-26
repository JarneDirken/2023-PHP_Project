<?php

namespace App\Http\Livewire;

use App\Models\Faq;
use Livewire\Component;

class FaqPage extends Component
{
    // attributes
    public $showModal = false;
    public $name;
    public $email;
    public $message;

    // validation rules
    protected $rules = [
        'name' => 'required|min:2',
        'email' => 'required|email',
        'message' => 'required|min:10',
    ];

    // real-time validation
    public function updated($propertyName, $propertyValue)
    {
        $this->validateOnly($propertyName);
    }

    // send email
    public function sendEmail()
    {
        // validate the whole request before sending the email
        $validatedData = $this->validate();
        // send email
        $this->sendQuestion($this->message,$this->email);
        // show a success toast
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "<p class='font-bold mb-2'>Beste $this->name,</p>
                       <p>Dank je voor je bericht.<br>We contacteren je zo snel mogenlijk.</p>",
        ]);

        // reset all public properties
        $this->reset();
    }

    // show vraag popup
    public function showModal()
    {
        $this->showModal = true;
    }
    public function render()
    {
        $faqs = Faq::all();

        return view('livewire.faq-page', compact('faqs'))
            ->layout('layouts.projectPHP', [
                'description' => 'Faq pagina.',
                'title' => 'FAQ pagina'
            ]);
    }

    public function sendQuestion($question,$frommail) {
        $inschrijvenEvenement = new Mailing();
        $inschrijvenEvenement->sendQuestion($question,$frommail);
        Mailing::sendQuestion($question,$frommail);
    }
}
