<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Events\AccountCreated;
use Hafael\LaraFlake\Traits\LaraFlakeTrait;
use Hafael\Mesh\Auth\Models\AppAccessToken;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasApiTokens;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * Indicates if the IDs are auto-incrementing.
     * @var bool
     */
    public $incrementing = false;

    //public $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 
        'lastname', 
        'email',
        'username',
        'phone', 
        'password', 
        'currency', 
        'language', 
        'country',
        'spend_limit_code',
        'subscription_id',
        'current_team_owner',
        'current_team_name',
        'current_team_organization',
        'blocked_at',
    ];

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
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => AccountCreated::class,
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'blocked_at' => 'datetime',
            'current_team_id' => 'string',
            'current_team_owner' => 'string',
        ];
    }

    public function getIsAdminAttribute()
    {
        return $this->isAdmin();
    }

    public function setPhoneAttribute($phone)
    {
        return $this->attributes['phone'] = (new Phone($phone))->globalNumber();
    }

    public function toArray()
    {
        return array_merge(parent::toArray(), [
            'is_admin' => $this->isAdmin(),
        ]);
    }

    /**
     * The owner of current team fot the model.
     */
    public function currentTeamOwner()
    {
        if($this->currentTeam && 
           $this->currentTeam->owner &&
           $this->currentTeam->user_id !== $this->id)
        {
            return $this->currentTeam->owner;
        }

        return $this;
    }

    public function isAdmin()
    {
        return str_ends_with($this->email, '@autum.com.br') || $this->email === 'villa655321verde@gmail.com';
    }

    public function isBlocked()
    {
        return $this->blocked_at !== null;
    }

    public function accountLimits()
    {
        return $this->currentSpendUser->toArray();
    }

    /**
     * Store a generated personal access token for the user.
     *
     * @param  string  $plainTextToken
     * @param  string  $name
     * @param  array  $abilities
     * @return \App\Models\AppAccessToken
     */
    public function savePlainTextToken($plainTextToken, string $name, array $abilities = ['*'])
    {
        $token = $this->appTokens()->create([
            'name' => $name,
            'token' => $plainTextToken,
            'abilities' => $abilities,
        ]);

        return $token;
    }

    public function spendLimit()
    {
        return $this->belongsTo('App\Models\SpendLimit', 'spend_limit_code', 'code');
    }

    public function currentSpendUser()
    {
        return $this->hasOne('App\Models\CurrentSpendUser');
    }

    public function appTokens()
    {
        return $this->hasMany(AppAccessToken::class);
    }

}
