<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApplicationResource;
use Illuminate\Http\Request;
use App\Services\AutumPlatformService;

class ApplicationsController extends Controller
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
     * Search Apps
     */
    public function index(Request $request)
    {

        $this->validate($request, [
            'query' => 'string|nullable',
        ]);

        $term = $request->input('query');

        $response = $this->platformClient
                         ->apps()
                         ->getList($term);

        return $this->respondWithCollectionPaginated($response->json(), ApplicationResource::class);
    }


}
