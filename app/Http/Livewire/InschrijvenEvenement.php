<?php

namespace App\Http\Livewire;

use App\Http\Livewire\Admin\MailTemplates;
use App\Mail\InschrijvingEvent;
use App\Models\Event;
use App\Models\MailTemplate;
use App\Models\Point;
use App\Models\PointUser;
use App\Models\Season;
use App\Models\UserEvent;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Auth;
use Livewire\WithPagination;

class InschrijvenEvenement extends Component
{
    use WithPagination;
    public $perPage = 6;
    public $search;
    public $location;
    public $selectedSeasonId;
    public $selectedSeason;
    public $selectedEvent;
    public $showModal = false;
    public $seasons;
    public $alreadyRegistered;
    public $newUserEvent =
        [
            'user_id' => null,
            'event_id' => null];
    public function render()
    {

        $events = Event::where('name', 'like', "%$this->search%")
            ->where('location', 'like', "%$this->location%");
        $this->selectedSeason = Season::find($this->selectedSeasonId);
        if($this->selectedSeason){
            $events->whereBetween('start_date', [$this->selectedSeason->start_date, $this->selectedSeason->end_date]);
        }
        $events = $events->withCount('UserEvents')->paginate($this->perPage);

        return view('livewire.inschrijven-evenement', compact('events'))
            ->layout('layouts.projectPHP', [
                'description' => 'Inschrijven voor een evenement.',
                'title' => 'Inschrijven evenement'
            ]);
    }

    // get all the seasons from the database (runs only once)
    public function mount()
    {
        $this->seasons = Season::get();

        $calenderClickEventId = request()->query('id');
        if($calenderClickEventId){
            $event = Event::find($calenderClickEventId);
            if(!is_null($event)){
                $this->showInfo($event);
            }
        }
    }

    //show a modal with information about the event
    public function showInfo(Event $event)
    {
        $this->selectedEvent = $event;
        $this->showModal = true;

        // Check if the user is already registered for the event
        $userEvent = UserEvent::where('user_id', Auth::user()->id)
            ->where('event_id', $event->id)
            ->first();

        if ($userEvent) {
            // User is already registered, handle accordingly (e.g., set a flag, display a message, etc.)
            $this->alreadyRegistered = true;
        } else {
            // User is not registered, reset the flag
            $this->alreadyRegistered = false;
        }
    }

    //register user in event and give points to user
    public function createUserEvent(int $id)
    {
        $userEvent = UserEvent::create([
            'user_id' => Auth::user()->id,
            'event_id' => $id,
        ]);
        $pointuser = PointUser::where([['point_id', '=', 1], ['user_id', '=', Auth::user()->id], ['season_id', '=', Season::where('active', '=', true)->value('id')]]);
        if ($pointuser->exists()) {
            $pointuser->increment('amount', Point::where('id', '=', 1)->value('amount'));
            $pointuser->increment('points', Point::where('id', '=', 1)->value('amount'));
            if ($pointuser->value('amount') > Point::where('id', '=', 1)->value('maximum')){
                $pointuser->update(['amount' => Point::where('id', '=', 1)->value('maximum')]);
                $pointuser->update(['points' => Point::where('id', '=', 1)->value('maximum')]);
            }
        }else{
            $pointUser = PointUser::create([
                'point_id' => 1,
                'user_id' => Auth::user()->id,
                'season_id' => Season::where('active', '=', true)->value('id'),
                'amount' => Point::where('id', '=', 1)->value('amount'),

                'points' => Point::where('id', '=', 1)->value('amount'),

                'order_id' => null,
            ]);
        }
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "<p>U bent succesvol ingeschreven en er is een <b>mail</b> gestuurd naar uw inbox</p>",
        ]);
        $this->showModal = false;
        $this->sendMail($id);
        $this->resetNewUserEvent();
    }

    //send confirmation mail to the user
    public function sendMail($id) {
        $templateName = 'Inschrijving';
        $columnToRetrieve = 'body';

        $templateC = MailTemplate::where('name', $templateName)->select($columnToRetrieve)->first()->body;
        $eventName = Event::where('id', $id)->value('name');

        $template = new InschrijvingEvent([
            'name' => Auth::user()->email,
            'event' => $eventName,
            'content' => $templateC
        ]);
        $to = new Address(Auth::user()->email);
        Mail::to($to)
            ->send($template);

    }

    //reset $newUserEvent
    public function resetNewUserEvent(){
        $this->resetErrorBag();
        $this->reset('newUserEvent');
    }
}
