<?php

namespace App\Http\Middleware;

use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = $request->user();
        
        return [
            ...parent::share($request),
            'ziggy' => fn () => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
            'account_limits' => [],
            'auth' => [
                'user' => $user ? [
                    'id' => $user->id,
                    'name' => $user->name, 
                    'lastname' => $user->lastname, 
                    'email' => $user->email,
                    'username' => $user->username,
                    'phone' => $user->phone, 
                    'currency' => $user->currency, 
                    'language' => $user->language, 
                    'country' => $user->country,
                    'account_limits' => $user->accountLimits(),
                    'spend_limit_code' => $user->spend_limit_code,
                    'subscription_id' => $user->subscription_id,
                    'current_team' => $user->currentTeam,
                    'current_team_id' => $user->currentTeam->id,
                    'current_team_owner' => $user->current_team_owner,
                    'current_team_name' => $user->current_team_name,
                    'current_team_organization' => $user->current_team_organization,
                    'all_teams' => $user->allTeams(),
                    'blocked_at' => $user->blocked_at,
                    'profile_photo_url' => $user->profile_photo_url,
                    'two_factor_enabled' => ! is_null($user->two_factor_secret),
                    'is_admin' => $user->isAdmin(),
                ] : null,
                'permissions' => $user ? [
                    'user' => [
                        'viewAny' => $user->can('viewAny', User::class),
                        'create' => $user->can('create', User::class),
                    ],
                    'team' => [
                        'viewAny' => $user->can('viewAny', Team::class),
                        'create' => $user->can('create', Team::class),
                    ],
                ] : null
            ],
        ];
    }
}
