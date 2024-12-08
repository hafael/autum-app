<?php

namespace App\Services;

use App\Models\User;
use Autum\SDK\Platform\Client;
use Hafael\HttpClient\Handler\Curl;
use Illuminate\Support\Str;

/**
 * Version 0.1.0 - 2023-05-15
 */
class AutumPlatformService extends Client
{
    
    /**
     * @var User
     */
    protected $user;

    /**
     * @var bool
     */
    protected $skipUser = false;

    /**
     * 
     * @param User $user
     */
    public function actingAsUser(User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Preparing request
     * 
     * @param Curl $resource
     * @param array $params
     * @param array $data
     * @param array $headers
     * @return Curl
     */
    public function preRequestScript(Curl $resource, $params = [], $data = [], $headers = [])
    {
        $resource->addHeader('Cache-control: no-cache');
        $resource->addHeader('Content-type: application/json');
    
        //set auth header
        if($this->user && !$this->skipUser) {
            $resource->addHeader('Authorization: Bearer ' . $this->getUserAccessToken());    
        }else {
            $encoded = base64_encode($this->user->id . '|' . env('IDP_API_SECRET'));
            $resource->addHeader('X-API-KEY: ' . env('IDP_API_KEY'));
            $resource->addHeader('X-API-SECRET: ' . $encoded);
        }

        return $resource;
    }

    /**
     * @return string|null
     */
    public function getUserAccessToken()
    {

        $appName = Str::camel(config('app.name') . '_' . $this->resourceName);

        $currentToken = $this->user->appTokens()->where([
            ['name', '=', $appName],
        ])->first();

        if(empty($currentToken)) {
            //issue new token
            $this->skipUser = true;
            
            $response = $this->users()->issueToken($this->user->id, $appName);

            $this->skipUser = false;
            $token = $response->json();
            $currentToken = $this->user->savePlainTextToken($token['access_token'], $token['name'], $token['abilities']);                
            $currentToken->forceFill(['last_used_at' => now()])->save();
            
            return $currentToken->token;
        }

        //get current token
        $currentToken->forceFill(['last_used_at' => now()])->save();

        return $currentToken->token;
    }

    public function __getUserAccessToken()
    {
        return $this->user->api_key;
    }
}
