<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrentSpendUser extends Model
{
    use HasFactory;

    protected $table = 'current_spend_user';

    protected $casts = [
        'teams_count' => 'integer',
        'max_teams' => 'integer',
        'max_team_members' => 'integer',
        'trial_period_days' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
