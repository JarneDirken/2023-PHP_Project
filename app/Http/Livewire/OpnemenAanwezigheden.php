<?php

namespace App\Http\Livewire;

use App\Models\Point;
use App\Models\PointUser;
use App\Models\Season;
use App\Models\Tour;
use App\Models\User;
use App\Models\UserTour;
use Livewire\Component;
use Livewire\WithPagination;

class OpnemenAanwezigheden extends Component
{
    use WithPagination;
    public $perPage = 10;
    public $selectedTour;
    public $name;

    // reset the paginator
    public function updated($propertyName, $propertyValue)
    {
        // reset if the $selectedTour or $name property has changed (updated)
        if (in_array($propertyName, ['selectedTour', 'name']))
            $this->resetPage();
    }

    public function render()
    {
        $queryTour = Tour::where('date', '<=', date("Y-m-d"))->where('open', true);
        if(!auth()->user()->management){
            $queryTour = $queryTour->where('user_id', 'like', auth()->user()->id);
        }
        $tours = $queryTour->get();

        $queryUser = User::where(function ($query) {
                    $query->whereRaw("concat(first_name, ' ', last_name) like ?", "%{$this->name}%")
                        ->orWhere('first_name', 'like', "%{$this->name}%")
                        ->orWhere('last_name', 'like', "%{$this->name}%");
                });

        $guideId = Tour::where('id', 'like', $this->selectedTour)->value('user_id');
        if($guideId != null){
            $queryUser = $queryUser->where('id', 'not like', $guideId);
        }
        $users = $queryUser->paginate($this->perPage);


        return view('livewire.opnemen-aanwezigheden', compact(['tours', 'users']))
            ->layout('layouts.projectPHP', [
                'description' => 'Aanwezigheden opnemen',
                'title' => 'Aanwezigheden opnemen'
            ]);
    }

    public function mount()
    {
        $this->resetSelectedTour();
    }

    //de geselecteerde tour id gelijk zetten aan de eerste tour in de dropdown (doen in het eerst laden pagina en als bevestigt is)
    private function resetSelectedTour()
    {
        $queryTour = Tour::where('date', '<=', date("Y-m-d"))->where('open', true);
        if(!auth()->user()->management){
            $queryTour = $queryTour->where('user_id', 'like', auth()->user()->id);
        }
        $tours = $queryTour->get();
        $this->selectedTour = (sizeof($tours) > 0) ? $tours[0]->id : -1;
    }

    public function togglePresent(User $user)
    {
        $userTour = UserTour::where('user_id','like',$user->id)
            ->where('tour_id','like',$this->selectedTour);
        $userTour->update([
                'present' => !$userTour->value('present')
        ]);
    }

    public function confirmPresences()
    {
        // alle users van huidige tour
        $usersTour = UserTour::where('tour_id', 'like', $this->selectedTour)->get();
        // week van huidige tour
        $weekTour = date("W", strtotime(Tour::find($this->selectedTour)->date));
        foreach($usersTour as $userTour){
            if($userTour->present) {
                $toursSameWeek = $this->getRidesOfWeek($userTour->id, $weekTour);
                //zijn er tour(s) van dezelfde week?
                if($toursSameWeek > 0){
                    // dezelfde week -> extra rit punten
                    $this->addPointsTour(User::find($userTour->user_id), false);
                } else {
                    // geen zelfde week -> eerste rit punten
                    $this->addPointsTour(User::find($userTour->user_id), true);
                }
            }
        }
        // ritverkenner punten geven (is altijd aanwezig?)
        if(!is_null(Tour::find($this->selectedTour)->user_id)) {
            $guideId = Tour::find($this->selectedTour)->user_id;
            $toursSameWeekGuide = $this->getRidesOfWeek($guideId, $weekTour);
            if($toursSameWeekGuide > 0){
                // dezelfde week -> extra rit punten
                $this->addPointsTour(User::find($guideId), false);
            } else {
                // geen zelfde week -> eerste rit punten
                $this->addPointsTour(User::find($guideId), true);
            }
            //nu nog de ritverkenner op aanwezig zetten
            UserTour::where('tour_id', 'like', $this->selectedTour)
                ->where('user_id', 'like', $guideId)->update([
                    'present' => true
                ]);
        }
        // de tour sluiten
        Tour::where('id', 'like', $this->selectedTour)->update([
            'open' => false
        ]);
        // toast
        $rit = Tour::find($this->selectedTour);
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "Aanwezigheden voor <b><i>{$rit->name}</b></i> zijn opgenomen",
        ]);
        //de geselecteerde tour updaten
        $this->resetSelectedTour();
    }

    private function addPointsTour(User $user, bool $isFirst){
        // je moet nog checken of de max punten van eerste rit al max is, en dan toevoegen aan extra
        $point_id = ($isFirst) ? 2 : 3;
        $pointuser = PointUser::where([['point_id', '=', $point_id], ['user_id', '=', $user->id], ['season_id', '=', Season::where('active', '=', true)->value('id')]]);
        if ($pointuser->exists()) {
            if($isFirst && $pointuser->value('amount') == Point::where('id', '=', $point_id)->value('maximum')) {
                $this->addPointsTour($user, false);
            } else {
                $pointuser->increment('amount', Point::where('id', '=', $point_id)->value('amount'));
                $pointuser->increment('points', Point::where('id', '=', $point_id)->value('amount'));
                if ($pointuser->value('amount') > Point::where('id', '=', $point_id)->value('maximum')){
                    $pointuser->update(['amount' => Point::where('id', '=', $point_id)->value('maximum')]);
                    $pointuser->update(['points' => Point::where('id', '=', $point_id)->value('maximum')]);
                }
            }
        }else{
            PointUser::create([
                'point_id' => $point_id,
                'user_id' => $user->id,
                'season_id' => Season::where('active', '=', true)->value('id'),
                'amount' => Point::where('id', '=', $point_id)->value('amount'),

                'points' => Point::where('id', '=', $point_id)->value('amount'),

                'order_id' => null,
            ]);
        }
    }

    private function getRidesOfWeek(int $userId, string $weekTour): int
    {
        //eerst checken of dat de persoon al een rit gereden heeft dezelfde week van de week van deze rit
        //alle usertours van de user pakken waar persoon present was (excluding de huidige)
        $userToursOfUser = UserTour::whereNot('tour_id', 'like', $this->selectedTour)
            ->where('user_id', 'like', $userId)
            ->where('present', true)->get();
        //nu checken of dat er afgesloten tours zijn, en ofdat deze in dezelfde week liggen als de te sluiten tour
        $toursSameWeekCounter = 0;
        foreach ($userToursOfUser as $userTourOfUser) {
            if(Tour::where('id', 'like', $userTourOfUser->tour_id)->where('open', false)->exists()
                && date("W", strtotime(Tour::where('id', 'like', $userTourOfUser->tour_id)->where('open', false)->value('date'))) == $weekTour) {
                $toursSameWeekCounter++;
            }
        }
        return $toursSameWeekCounter;
    }
}
