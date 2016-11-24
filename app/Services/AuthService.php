<?php

namespace App\Services;

use Exception;
use Illuminate\Http\Request;
use App\Lib\Verifier\TokenVerifyFactory;
use App\Services\Contracts\AuthServiceInterface;

class AuthService implements AuthServiceInterface
{

    /**
     *
     * @var type 
     */
    protected $factory;

    /**
     *
     * @var type 
     */
    protected $token;

    /**
     *
     * @var type 
     */
    protected $source;

    /**
     * 
     * @param TokenVerifyFactory $factory
     */
    public function __construct(TokenVerifyFactory $factory)
    {
        $this->factory = $factory;
    }

    public function infoFromToken(Request $request)
    {
        $this->source = strtoupper($request->header('OAuth-Source', null));
        $this->token = preg_replace('\^BEARER', '', $request->header('Authorization', null));

        try
        {
            return $this->factory->getVerifier($this->source, $this->token)->verify();
        }
        catch (Exception $ex)
        {
            throw $ex;
        }
    }

}
