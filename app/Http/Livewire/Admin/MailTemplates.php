<?php

namespace App\Http\Livewire\Admin;

use App\Models\MailTemplate;
use Livewire\Component;
use Livewire\WithPagination;

class MailTemplates extends Component
{
    // attributes
    use WithPagination;
    public $perPage = 10;
    public $showModal = false;
    public $orderAsc = true;
    public $orderBy = 'id';
    public $name;

    public $newMailTemplate = [
        'id' => null,
        'name' => null,
        'subject' => null,
        'body' => null,
    ];

    // validation rules
    protected function rules()
    {
        return [
            'newMailTemplate.name' => 'required',
        ];
    }

    // validation attributes
    protected $validationAttributes = [
        'newMailTemplate.name' => 'naam',
        'newMailTemplate.subject' => 'onderwerp',
        'newMailTemplate.body' => 'inhoud',
    ];

    // set/reset $newMailTemplate and validation
    public function setNewMailTemplate(MailTemplate $mailTemplate = null)
    {
        $this->resetErrorBag();
        if ($mailTemplate) {
            $this->newMailTemplate['id'] = $mailTemplate->id;
            $this->newMailTemplate['name'] = $mailTemplate->name;
            $this->newMailTemplate['subject'] = $mailTemplate->subject;
            $this->newMailTemplate['body'] = $mailTemplate->body;
        } else {
            $this->reset('newMailTemplate');
        }
        $this->showModal = true;
    }

    // create a new mailTemplate
    public function createMailTemplate()
    {
        $this->validate();
        $mailTemplate = MailTemplate::create([
            'name' => $this->newMailTemplate['name'],
            'subject' => $this->newMailTemplate['subject'],
            'body' => $this->newMailTemplate['body'],
        ]);
        $this->showModal = false;
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "De template <b><i>{$mailTemplate->name}</i></b> is toegevoegd",
        ]);
    }

    // update an existing mailTemplate
    public function updateMailTemplate(MailTemplate $mailTemplate)
    {
        $this->validate();
        $mailTemplate->update([
            'name' => $this->newMailTemplate['name'],
            'subject' => $this->newMailTemplate['subject'],
            'body' => $this->newMailTemplate['body'],
        ]);
        $this->showModal = false;
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "De template <b><i>{$mailTemplate->name}</i></b> is aangepast",
        ]);
    }

    // delete an existing mailTemplate
    public function deleteMailTemplate(MailTemplate $mailTemplate)
    {
        $mailTemplate->delete();
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'danger',
            'html' => "De template <b><i>{$mailTemplate->name}</i></b> is verwijderd",
        ]);
    }

    public function render()
    {
        $mailTemplates = MailTemplate::orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')
        ->where([
            ['name', 'like', "%{$this->name}%"]
        ])
        ->paginate($this->perPage);
        return view('livewire.admin.mail-templates', compact('mailTemplates'))
            ->layout('layouts.projectPHP', [
                'description' => 'Beheer de mail templates',
                'title' => 'Mailing templates Beheren',
            ]);
    }

    // listen to the delete-mailTemplate event
    protected $listeners = [
        'delete-mailTemplate' => 'deleteMailTemplate',
    ];
}
