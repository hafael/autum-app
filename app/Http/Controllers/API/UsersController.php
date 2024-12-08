<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\AutumPlatformService;
use App\Services\UserService;

class UsersController extends Controller
{

    use ApiResponseTrait;

    protected $platformClient;
    protected $userService;

    public function __construct(AutumPlatformService $platformClient,
                                UserService $userService)
    {
        $this->platformClient = $platformClient;
        $this->userService = $userService;
        $this->middleware(function ($request, $next) {
            $this->platformClient->actingAsUser(auth()->user());
            return $next($request);
        });
    }

    /**
     * Search Users
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        $this->validate($request, [
            'query' => 'string|nullable',
        ]);

        $term = $request->input('query');

        $response = $this->platformClient
                         ->users()
                         ->getList($term);

        return $this->respondWithCollectionPaginated($response->json(), UserResource::class);
    }

    public function show(string $userId)
    {
        $this->authorize('view', User::class);

        $response = $this->platformClient
                         ->users()
                         ->showById($userId);

        if(empty($response))
        {
            return $this->respondWithError('User not found', 404);
        }

        return $this->respondWithItem($response->json(), UserResource::class);
    }

    public function me(Request $request)
    {
        $this->authorize('view', User::class);

        $userId = $request->user()->id;

        $response = $this->platformClient
                         ->users()
                         ->showById($userId);

        if(empty($response))
        {
            return $this->respondWithError('User not found', 404);
        }

        return $this->respondWithItem($response->json(), UserResource::class);
    }

}
