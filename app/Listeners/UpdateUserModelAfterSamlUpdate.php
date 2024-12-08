<?php

namespace App\Listeners;

use App\Models\User;
use Autum\SAML\Events\Webhooks\AccountUpdatedEvent;


class UpdateUserModelAfterSamlUpdate
{

    /**
     * @var array
     */
    public $payload = [];
    
    public function handle(AccountUpdatedEvent $event): void
    {

        $this->payload = $event->payload;

        $userData = collect($this->payload['payload']);

        $user = User::where('id', $userData->get('id'))->first();

        if($user) {

            $fields = [
                'name',
                'username',
                'lastname',
                'email',
                'profile_photo_path',
            ];
            $diff = $userData->only($fields)->diffAssoc($user->only($fields));

            if(!$diff->isEmpty()) {
                $user->update($diff->all());
            }
            
        }

        
    }
    
}
