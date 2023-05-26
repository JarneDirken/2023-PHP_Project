<?php

namespace App\Models;

use App\Http\Livewire\Admin\Events;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    //het originele User.php bestand had onderstaande $fillable, ik heb deze vervangen door de $gaurded array
    //protected $fillable = ['name', 'email', 'password',];
    //protected $guarded = ['id', 'created_at', 'updated_at', 'actual_wbv_insurance', 'user_id', 'management', 'active'];
    protected $guarded = ['id', 'created_at', 'updated_at'];


    //hier zelf nog toegevoegd, de relaties
    public function gender()
    {
        return $this->belongsTo(Gender::class)->withDefault();
    }

    public function userevents()
    {
        return $this->hasMany(UserEvent::class);
    }
    public function events()
    {
        return $this->belongsToMany(Event::class, 'user_events', 'user_id', 'event_id');
    }

    //dit is als user een ritverkenner is (R17)
    public function tours()
    {
        return $this->hasMany(Tour::class);
    }

    //dit als user bij de groep van deelnemers is
    public function usertours()
    {
        return $this->hasMany(UserTour::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function pointusers()
    {
        return $this->hasMany(PointUser::class);
    }

    public function membershipusers()
    {
        return $this->hasMany(MembershipUser::class);
    }

    //de recursieve relatie(s)
    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**  Apply the scope to a given Eloquent query builder */
    public function scopeSearchNameOrHouseholder($query, $search = '%')
    {
        $fullName = $this->first_name . ' ' . $this->last_name;
        return $query->whereRaw("concat(first_name, ' ', last_name) like ?", "%{$search}%")
            //'concat(name," ",last_name) like ?', "%{$q}%"
            ->orWhere('first_name', 'like', "%{$search}%")
            ->orWhere('last_name', 'like', "%{$search}%")
            ->orWhere('householder_name', 'like', "%{$search}%");
    }

    //einde toevoeging

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    /**  Add additional attributes that do not have a corresponding column in your database */
    protected function currentMembership(): Attribute
    {
        return Attribute::make(
            get: function($value, $attributes) {
                $active_season_id = Season::where('active', true)->value('id');
                $membership_id = MembershipUser::where('user_id', $attributes['id'])
                    ->where('season_id', $active_season_id)->value('membership_id');
                return Membership::find($membership_id);
            }
        );
    }

    protected function actualWbvInsurance(): Attribute
    {
        return Attribute::make(
            get: function() {
                $active_season_id = Season::where('active', true)->value('id');
                $householder_id = $this->user_id;
                // heeft de gebruiker zelf insurance?
                if(MembershipUser::where('user_id', $this->id)
                        ->where('season_id', $active_season_id)
                        ->value('membership_id') == 2)
                {
                    return true;
                }
                // heeft de gebruiker een actieve ouder met insurance?
                if($householder_id && User::find($householder_id)->active &&
                    MembershipUser::where('user_id', $householder_id)
                        ->where('season_id', $active_season_id)
                        ->value('membership_id') == 2)
                {
                    return true;
                }
                return false;
        });
    }

/*    public function getActualWbvInsuranceAttribute()
    {
        $active_season_id = Season::where('active', true)->value('id');
        $householder_id = $this->user_id;
        // heeft de gebruiker zelf insurance?
        if(MembershipUser::where('user_id', $this->id)
                ->where('season_id', $active_season_id)
                ->value('membership_id') == 2)
        {
            return 'zelf wbv';
        }
        // heeft de gebruiker een actieve ouder met insurance?
        if($householder_id && User::find($householder_id)->active &&
            MembershipUser::where('user_id', $householder_id)
                ->where('season_id', $active_season_id)
                ->value('membership_id') == 2)
        {
            return 'ouder met wbv';
        }
        return 'geen wbv';
    }*/

    protected function amountConnection(): Attribute
    {
        return Attribute::make(
            get: function($value, $attributes) {
                $connections = MembershipUser::where('user_id', $attributes['id'])->count();
                $connections += UserTour::where('user_id', $attributes['id'])->count();
                $connections += Tour::where('user_id', $attributes['id'])->count();
                $connections += UserEvent::where('user_id', $attributes['id'])->count();
                $connections += PointUser::where('user_id', $attributes['id'])->count();
                $connections += Order::where('user_id', $attributes['id'])->count();
                return $connections;
            }
        );
    }

    protected function isTourGuide(): Attribute
    {
        return Attribute::make(
            get: function($value, $attributes) {
                return Tour::where('user_id', $attributes['id'])->count() > 0;
            }
        );
    }
    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url', 'current_membership', 'actual_wbv_insurance'
    ];
}
