<?php

namespace App\Actions\Fortify;
use App\Http\Livewire\InschrijvenEvenement;
use App\Http\Livewire\Mailing;
use App\Models\MailTemplate;
use App\Models\Membership;
use App\Models\MembershipUser;
use App\Models\Season;
use App\Models\Tour;
use App\Models\User;
use App\Models\UserTour;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;


class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */

    public function sendmail($mail,$name) {
        $inschrijvenEvenement = new Mailing();
        $inschrijvenEvenement->sendWelcome($mail,$name);
        Mailing::sendWelcome($mail,$name);
    }

    public function create(array $input)
    {
        $validator = Validator::make($input, [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
            'first_name' => ['required', 'string', 'max:255', 'min:2'],
            'last_name' => ['required', 'string', 'max:255', 'min:2',
                Rule::unique('users')
                    ->where(fn ($query) => $query->where('first_name', $input['first_name']))],
            'phone' => ['required', 'string', 'max:255', 'unique:users'],
            'city' => ['required', 'string', 'max:255', 'min:3'],
            'postal_code' => ['required', 'string', 'max:255'],
            'street' => ['required', 'string', 'max:255', 'min:3'],
            'house_number' => ['required', 'string', 'max:255'],
            'gender_id' => ['required', 'integer', 'digits_between:1,3' ],
            'householder_name' => ['string', 'max:255', 'nullable'],
            'actual_wbv_insurance' => ['boolean', 'nullable'],
        ],
            //custom error message waarin dat unique van achternaam wordt gecheckt samen met voornaam
            [
                'last_name.unique' => 'De combinatie van voor- en achternaam is al in gebruik.',
            ]);

        $validator->validate();

        $this->sendmail($input['email'],$input['first_name']);


        // Convert actual_wbv_insurance to a boolean value
        $actual_wbv_insurance = session('insurance', false);

        // Redirect to the payment page
        $user = User::create([
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'first_name' => $input['first_name'],
            'last_name' => $input['last_name'],
            'phone' => $input['phone'],
            'city' => $input['city'],
            'postal_code' => $input['postal_code'],
            'street' => $input['street'],
            'house_number' => $input['house_number'],
            'gender_id' => $input['gender_id'],
            'householder_name' => $input['householder_name'],
            'actual_wbv_insurance' => $actual_wbv_insurance,
        ]);
        //de gebruiker het gekozen lidmaatschap geven
        $membership_id = ($actual_wbv_insurance) ? 2 : 1;
        MembershipUser::create(
            [
                'user_id' => $user->id,
                'membership_id' => $membership_id,
                'season_id' => Season::where('active', true)->value('id')
            ],
        );
        //de gebruiker verbinden aan alle open ritten
        $openTours = Tour::where('open', true)->get();
        foreach($openTours as $tour){
            UserTour::create(
                [
                    'tour_id' => $tour->id,
                    'user_id' => $user->id,
                ]
            );
        }

        return $user;
    }
}
