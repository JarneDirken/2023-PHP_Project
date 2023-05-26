<?php

namespace App\Http\Livewire\Admin;

use App\Models\Gender;
use App\Models\Membership;
use App\Models\MembershipUser;
use App\Models\Season;
use App\Models\Tour;
use App\Models\User;
use App\Models\UserTour;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class Users extends Component
{
    use WithPagination;

    public $perPage = 6;
    public $search;
    public $admin = false;
    public $status = 'All';
    public $insurance = 'All';

    public $showModal = false;
    public $genders;
    public $allUsers;
    public $activeSeasonId;
    // array that contains the values for updating a user
    public $newUser = [
        "id"=> null,
        "first_name"=> null,
        "last_name"=> null,
        "email"=> null,
        "phone"=> null,
        "city"=> null,
        "postal_code"=> null,
        "street"=> null,
        "house_number"=> null,
        "management"=> null,
        "gender_id"=> 1,
        "active"=> false,
        "householder_name"=> null,
        "user_id" => null
    ];
    public $last_name;

    protected function rules()
    {
        return [
            'newUser.first_name' => 'required|min:2|max:255',
            'newUser.last_name' => 'required|min:2|max:255',
            //een speciale rule die kijkt of de last_name unique is samen met de first_name , de combinatie moet dus uniek zijn
            //de unique('users') methode kijkt in de users tabel naar de kolom met als naam 'last_name',
            //dus ik kon niet newUser.last_name gebruiken hiervoor. Het kijkt ook niet naar waar de id's gelijk zijn, anders krijg je bij updated problemen
            'last_name' => Rule::unique('users')->where(fn ($query) => $query->whereNot('id', $this->newUser['id'])->where('first_name', $this->newUser['first_name'])),
            'newUser.email' => 'required|email|unique:users,email,' . $this->newUser['id'],
            'newUser.phone' => 'required|unique:users,phone,' . $this->newUser['id'],
            'newUser.city' => 'required|min:3|max:255',
            'newUser.postal_code' => 'required|max:255',
            'newUser.street' => 'required|min:3|max:255',
            'newUser.house_number' => 'required|max:255',
            'newUser.management' => 'required',
            'newUser.gender_id' => 'required',
        ];
    }

    // validation attributes
    protected $validationAttributes = [
        'newUser.first_name' => 'voornaam',
        'newUser.last_name' => 'achternaam',
        'last_name' => 'volledige naam',
        'newUser.email' => 'e-mail',
        'newUser.phone' => 'telefoonnummer',
        'newUser.city' => 'gemeente',
        'newUser.postal_code' => 'postcode',
        'newUser.street' => 'straat',
        'newUser.house_number' => 'huisnummer',
        'newUser.management' => 'adminstatus',
        'newUser.gender_id' => 'geslacht',
    ];

    protected $listeners = [
        'inactive-user' => 'inactiveUser',
        'delete-user' => 'deleteUser'
    ];

    public function render()
    {
        $query = User::orderBy('id')
            ->searchNameOrHouseholder($this->search);
        //if admin filter on
        if($this->admin){
            $query->where('management', true);
        }
        //status filter
        if($this->status == 'Active'){
            $query->where('active', true);
        } else if ($this->status == 'Inactive'){
            $query->where('active', false);
        }

        $usersBeforeFilter = $query->get();

        //insurance filter
        switch ($this->insurance){
            case 'Yes':
                $users = $usersBeforeFilter->filter(function ($user) {
                    return $user->actual_wbv_insurance;
                });
                break;
            case 'No':
                $users = $usersBeforeFilter->filter(function ($user) {
                    return !$user->actual_wbv_insurance;
                });
                break;
            default:
                $users = $usersBeforeFilter;
        }

        $users = new \Illuminate\Pagination\LengthAwarePaginator(
            $users->forPage(\Illuminate\Pagination\Paginator::resolveCurrentPage(), $this->perPage),
            $users->count(),
            $this->perPage,
            \Illuminate\Pagination\Paginator::resolveCurrentPage(),
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );

        return view('livewire.admin.users', compact(['users']))
            ->layout('layouts.projectPHP', [
                'description' => 'Beheer de gebruikers',
                'title' => 'Gebruikers beheren',
            ]);
    }

    public function mount()
    {
        $this->genders = Gender::get();
        $this->allUsers = User::get();
        $this->activeSeasonId = Season::where('active', true)->value('id');
    }

    // reset the paginator
    public function updated($propertyName, $propertyValue)
    {
        // reset if the $search, $noCover, $noStock or $perPage property has changed (updated)
        if (in_array($propertyName, ['search', 'admin', 'status', 'insurance']))
            $this->resetPage();
    }

    // set/reset $newUser and validation
    public function setNewUser(User $user = null)
    {
        $this->resetErrorBag();
        if ($user) {
            $this->newUser['id'] = $user->id;
            $this->newUser['first_name'] = $user->first_name;
            $this->newUser['last_name'] = $user->last_name;
            $this->newUser['email'] = $user->email;
            $this->newUser['phone'] = $user->phone;
            $this->newUser['city'] = $user->city;
            $this->newUser['postal_code'] = $user->postal_code;
            $this->newUser['street'] = $user->street;
            $this->newUser['house_number'] = $user->house_number;
            $this->newUser['management'] = $user->management;
            $this->newUser['gender_id'] = $user->gender_id;
            $this->newUser['active'] = $user->active;
            $this->newUser['householder_name'] = $user->householder_name;
            $this->newUser['user_id'] = $user->user_id;
        } else {
            $this->reset(['newUser', 'last_name']);
        }
        $this->showModal = true;
    }

    public function createUser(){

        $this->newUser['management'] = is_null($this->newUser['management']) ? false : $this->newUser['management'];
        $this->newUser['gender_id'] = is_null($this->newUser['gender_id']) ? 1 : $this->newUser['gender_id'];
        $this->last_name = $this->newUser['last_name'];

        $this->validate();

        $user_id = ($this->newUser['user_id'] == '0') ? null : $this->newUser['user_id'];
        $householder_name = (empty($this->newUser['householder_name'])) ? null : trim($this->newUser['householder_name']);

        $user = User::create([
            'first_name' => trim($this->newUser['first_name']),
            'last_name' => trim($this->newUser['last_name']),
            'email' => trim($this->newUser['email']),
            'phone' => trim($this->newUser['phone']),
            'city' => trim($this->newUser['city']),
            'postal_code' => trim($this->newUser['postal_code']),
            'street' => trim($this->newUser['street']),
            'house_number' => trim($this->newUser['house_number']),
            'management' => $this->newUser['management'],
            'gender_id' => $this->newUser['gender_id'],
            'active' => true,
            'householder_name' => $householder_name,
            'user_id' => $user_id,
            'password' => Hash::make('wachtwoord'), // het default wachtwoord voor een nieuwe gebruiker
        ]);
        //nadat een gebruiker is aangemaakt, deze aan alle open ritten verbinden
        $openTours = Tour::where('open', true)->get();
        foreach ($openTours as $openTour)
        {
            UserTour::create([
                'user_id' => $user->id,
                'tour_id' => $openTour->id,
            ]);
        }
        $this->showModal = false;
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "De gebruiker <b><i>{$user->first_name} {$user->last_name}</i></b> is toegevoegd",
        ]);
    }

    // update an existing user
    public function updateUser(User $user)
    {
        $this->last_name = $this->newUser['last_name'];
        $this->validate();

        $user_id = ($this->newUser['user_id'] == '0') ? null : $this->newUser['user_id'];
        $householder_name = (empty($this->newUser['householder_name'])) ? null : trim($this->newUser['householder_name']);

        $user->update([
            'first_name' => trim($this->newUser['first_name']),
            'last_name' => trim($this->newUser['last_name']),
            'email' => trim($this->newUser['email']),
            'phone' => trim($this->newUser['phone']),
            'city' => trim($this->newUser['city']),
            'postal_code' => trim($this->newUser['postal_code']),
            'street' => trim($this->newUser['street']),
            'house_number' => trim($this->newUser['house_number']),
            'management' => $this->newUser['management'],
            'gender_id' => $this->newUser['gender_id'],
            'active' => $this->newUser['active'],
            'householder_name' => $householder_name,
            'user_id' => $user_id,
        ]);
        $this->showModal = false;
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "De gebruiker <b><i>{$user->first_name} {$user->last_name}</i></b> is geupdate.",
        ]);
    }

    public function inactiveUser(User $user){
        $user->update([
                'active' => false
        ]);
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "De gebruiker <b><i>{$user->first_name} {$user->last_name}</i></b> is inactief",
        ]);
    }

    public function deleteUser(User $user){
        $user->delete();
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'danger',
            'html' => "De gebruiker <b><i>{$user->first_name} {$user->last_name}</i></b> is verwijderd",
        ]);
    }

    public function resetFilter(){
        $this->reset(['search', 'status', 'admin', 'insurance']);
    }
}
