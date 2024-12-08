<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\TeamResource;
use App\Models\Team;
use Illuminate\Http\Request;
use App\Services\AutumPlatformService;

class TeamsController extends Controller
{

    use ApiResponseTrait;

    protected $platformClient;

    public function __construct(AutumPlatformService $platformClient)
    {
        $this->platformClient = $platformClient;

        $this->middleware(function ($request, $next) {
            $this->platformClient->actingAsUser(auth()->user());
            return $next($request);
        });
    }

    /**
     * Search Teams
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Team::class);

        $this->validate($request, [
            'query' => 'string|nullable',
        ]);

        $term = $request->input('query');

        $response = $this->platformClient
                         ->teams()
                         ->getList($term);

        return response()->json($response->json());
        return $this->respondWithCollectionPaginated($response->json(), TeamResource::class);
    }

    public function show(string $teamId)
    {
        $this->authorize('view', Team::class);

        $response = $this->platformClient
                         ->teams()
                         ->showById($teamId);

        return response()->json($response->json());
        
        if(empty($response))
        {
            return $this->respondWithError('Team not found', 404);
        }

        return $this->respondWithItem($response->json(), TeamResource::class);
    }

}
