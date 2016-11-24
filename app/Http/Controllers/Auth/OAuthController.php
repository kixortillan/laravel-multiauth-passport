<?php

namespace App\Http\Controllers\Auth;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Lib\Verifier\Validator\Google;
use App\Lib\Verifier\Validator\Internal;
use App\Lib\Verifier\VerifierInterface;

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

    /**
     * 
     * @param VerifierInterface $verifier
     */
    public function __construct(VerifierInterface $verifier)
    {
        $this->verifier = $verifier;
    }

    /**
     * 
     * @param Request $request
     * @return type
     */
    public function info(Request $request)
    {
        $this->source = strtoupper($request->header('OAuth-Source', null));

        switch ($this->source)
        {
            case 'GOOGLE':
                $this->verifier->setValidator(new Google($request->header('Authorization', null)));
                break;
            case 'INTERNAL':
            default :
                $this->verifier->setValidator(new Internal());
                break;
        }

        $this->verifier->setAuthorizationHeader($request->header('Authorization', null));

        try
        {
            $user = $this->verifier->verify();

            return response()->json($user);
        }
        catch (Exception $ex)
        {
            return response()->json('', Response::HTTP_UNAUTHORIZED);
        }
    }

}
