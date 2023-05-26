<?php

namespace App\Http\Livewire\Admin;

use App\Models\Event;
use Livewire\Component;
use Livewire\WithPagination;

class Events extends Component
{
    use WithPagination;
    public $perPage = 6;
    public $name;
    public $showModal = false;
    public $newEvent = ['id' => null, 'name' => null, 'start_date' => null, 'end_date'=>null, 'location'=>null, 'description'=>null, 'max_volunteer'=>null];
    public $start_date;
    public $end_date;

    // validation rules
    protected function rules() {
        return[
            'newEvent.name' => 'required|min:1|max:50|unique:events,name,' . $this->newEvent['id'],
            'newEvent.start_date' => 'required',
            'newEvent.end_date' => 'required',
            'newEvent.location' => 'required|min:1|max:30',
            'newEvent.description' => 'required|min:1|max:300',
            'newEvent.max_volunteer' => 'int|required',
        ];}
    // validation attributes
    protected $validationAttributes = [
        'newEvent.name' => 'naam',
        'newEvent.start_date' => 'startdatum',
        'newEvent.end_date' => 'einddatum',
        'newEvent.location' => 'locatie',
        'newEvent.description' => 'beschrijving',
        'newEvent.max_volunteer' => 'maximum vrijwilligers',
    ];
    //gebruikersfilter resetten
    public function resetFilters() {
        $this->name = '';
        $this->start_date = '';
        $this->end_date = '';
    }
    public function render()
    {
        //filter opties
        $startDate = strtotime($this->start_date);
        $endDate = strtotime($this->end_date);

        $query = Event::with('users')
            ->withCount('users')
            ->orderBy('start_date')
            ->where('name', 'like', "%{$this->name}%");

        if (!empty($this->start_date)) {
            $startDate = strtotime($this->start_date);
            $query->where('start_date', '>', date('Y-m-d H:i:s', $startDate));
        }

        if (!empty($this->end_date)) {
            $endDate = strtotime($this->end_date);
            $query->where('end_date', '<', date('Y-m-d H:i:s', $endDate));
        }

        $events = $query->paginate($this->perPage);

        return view('livewire.admin.events', compact('events'))
            ->layout('layouts.projectPHP', [
                'description' => 'Evenementen beheren',
                'title' => 'Evenementen beheren',
            ]);
    }
    // listeners
    protected $listeners = [
        'delete-event' => 'deleteEvent'
    ];
    // delete een event
    public function deleteEvent(Event $event)
    {
        $event->delete();
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'danger',
            'html' => "Het evenement <b><i>{$event->name}</i></b> is verwijderd",
        ]);
    }
    // reset the fields
    public function resetNewEvent()
    {
        $this->reset('newEvent');
        $this->resetErrorBag();
        $this->showModal = true;
    }
    //nieuw evenement aanmaken
    public function createEvent()
    {
        //$this->validateOnly('newEvent');
        $this->validate();

        try {
            $event = Event::create([
                'name' => $this->newEvent['name'],
                'start_date' => $this->newEvent['start_date'],
                'end_date' => $this->newEvent['end_date'],
                'location' => $this->newEvent['location'],
                'description' => $this->newEvent['description'],
                'max_volunteer' => $this->newEvent['max_volunteer'],
            ]);

            // Toast success message
            $this->dispatchBrowserEvent('swal:toast', [
                'background' => 'success',
                'html' => "Het evenement <b><i>{$event->name}</i></b> is toegevoegd",
            ]);

            $this->resetNewEvent();
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
        $this->showModal = false;
    }
    //nieuw event waardes geven
    public function setNewEvent(Event $event = null)
    {
        $this->resetErrorBag();
        if ($event) {
            $this->newEvent['id'] = $event->id;
            $this->newEvent['name'] = $event->name;
            $this->newEvent['start_date'] = $event->start_date;
            $this->newEvent['end_date'] = $event->end_date;
            $this->newEvent['location'] = $event->location;
            $this->newEvent['description'] = $event->description;
            $this->newEvent['max_volunteer'] = $event->max_volunteer;
        } else {
            $this->reset('newEvent');
        }
        $this->showModal = true;
    }
    // event updaten
    public function updateEvent(Event $event)
    {
        $this->validate();

        try {

            $event->update([
                'name' => $this->newEvent['name'],
                'start_date' => $this->newEvent['start_date'],
                'end_date' => $this->newEvent['end_date'],
                'location' => $this->newEvent['location'],
                'description' => $this->newEvent['description'],
                'max_volunteer' => $this->newEvent['max_volunteer'],
            ]);
            $this->showModal = false;
            $this->dispatchBrowserEvent('swal:toast', [
                'background' => 'success',
                'html' => "Het evenement <b><i>{$event->name}</i></b> is aangepast",
            ]);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

}
