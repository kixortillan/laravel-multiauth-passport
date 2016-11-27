<?php

namespace App\Lib\Verifier\Validator;

use Laravel\Socialite\Contracts\Provider;
use App\Repositories\UserRepository;

class Google implements TokenValidatorInterface
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
        $googleUser = $this->socialite->driver('google')->userFromToken($this->token);

        $user = $this->repo->findByEmail($googleUser->email);

        if ($user)
        {
            return $user;
        }

        return false;
    }

}
