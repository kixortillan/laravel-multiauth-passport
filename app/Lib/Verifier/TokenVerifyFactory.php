<?php

namespace App\Lib\Verifier;

use Socialite;
use App\Lib\Verifier\Validator\SocialiteValidator;
use App\Lib\Verifier\Validator\Internal;
use App\Repositories\UserRepository;

class TokenVerifyFactory
{

    public function __construct()
    {
        
    }

    /**
     * 
     * @param type $provider
     * @param type $token
     */
    public function getVerifier($provider, $token = null)
    {
        $provider = strtolower($provider);
        
        switch ($provider)
        {
            case 'facebook':
            case 'google':
                return new TokenVerify(new SocialiteValidator(Socialite::with($provider), new UserRepository(), $token));
            case 'internal':
            default :
                return new TokenVerify(new Internal(auth('api')));
        }
    }

}
