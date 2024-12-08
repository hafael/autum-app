<?php

namespace App\Listeners;

use App\Models\Team;
use App\Services\AutumPlatformService;
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;

class SetupUserTeamAfterLogin
{

    protected $service;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(AutumPlatformService $service)
    {
        $this->service = $service;
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(Login $event)
    {

        try {
            $currentTeam = $this->service->actingAsUser($event->user)
                                        ->teams()
                                        ->getCurrentTeam();

        } catch (\Exception $e) {

            $this->failed($event, $e);
            return;

        }

        if($currentTeam && !empty($currentTeam->json()['data'])) {

            $data = $currentTeam->json()['data'];

            $team = $this->getTeamById($data['id']);

            if(empty($team))
            {
                $team = $this->createNewTeam($data);
            }

            if($event->user->id != $data['user_id']) {
                $this->updateUserTeamRole($event->user, $team, $data['role']);
            }

            $this->updateCurrentTeam($event->user, $data);
        }
    }

    private function updateUserTeamRole($user, $team, $role)
    {
        $user->teams()->syncWithoutDetaching($team->id, ['role' => $role]);
    }

    private function getTeamById($id)
    {
        return Team::where('id', $id)->first();
    }

    private function updateCurrentTeam($user, $data)
    {
        $user->forceFill([
            'current_team_id' => $data['id'],
            'current_team_name' => $data['name'],
            'current_team_owner' => $data['user_id'],
            'current_team_organization' => $data['organization_name'],
        ])->saveQuietly();
    }


    private function createNewTeam($data)
    {
        $team = Team::forceCreate([
            'id' => $data['id'],
            'user_id' => $data['user_id'],
            'name' => $data['name'],
            'personal_team' => $data['personal_team'],
        ]);

        return $team;
    }

    /**
     * Handle a job failure.
     */
    public function failed(Login $event, Throwable $exception): void
    {
        Log::critical('SetupUserTeamAfterLogin failed', [
            'user' => $event->user->id,
            'exception' => $exception->getMessage(),
        ]);
    }
}
