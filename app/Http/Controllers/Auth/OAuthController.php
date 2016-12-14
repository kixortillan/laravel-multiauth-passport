<?php

namespace App\Http\Controllers\Auth;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\Contracts\AuthServiceInterface;

class OAuthController
{

    /**
     *
     * @var type 
     */
    protected $service;

    /**
     *
     * @var type 
     */
    protected $source;

    public function __construct(AuthServiceInterface $service)
    {
        $this->service = $service;
    }

    /**
     * 
     * @param Request $request
     * @return type
     */
    public function info(Request $request)
    {
        try
        {
            $user = $this->service->infoFromToken($request);
            
            return response()->json($user);
        }
        catch (Exception $ex)
        {
            abort(Response::HTTP_UNAUTHORIZED);
        }
    }

}
