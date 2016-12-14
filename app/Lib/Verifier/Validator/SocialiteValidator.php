<?php

namespace App\Lib\Verifier\Validator;

use GuzzleHttp\Exception\RequestException;
use Laravel\Socialite\Contracts\Provider;
use App\Repositories\UserRepository;

class SocialiteValidator implements TokenValidatorInterface
{

    /**
     *
     * @var type 
     */
    protected $repo;

    /**
     *
     * @var type 
     */
    protected $token;

    /**
     *
     * @var type 
     */
    protected $socialite;

    public function __construct(Provider $socialite, UserRepository $repo, $token)
    {
        $this->socialite = $socialite;
        $this->repo = $repo;
        $this->token = $token;
    }

    public function validate()
    {
        try
        {
            $userInfo = $this->socialite->userFromToken($this->token);
        }
        catch (RequestException $ex)
        {
            return false;
        }

        if ($userInfo == null || !is_object($userInfo))
        {
            return false;
        }

        $user = $this->repo->findByEmail($userInfo->email);

        if ($user)
        {
            return $user;
        }

        return false;
    }

}
