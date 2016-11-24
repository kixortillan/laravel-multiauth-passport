<?php

namespace App\Lib\Verifier\Validator;

use App\Repositories\UserRepository;
use Laravel\Socialite\Two\AbstractProvider;

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

    public function __construct(AbstractProvider $socialite, UserRepository $repo, $token)
    {
        $this->repo = $repo;
        $this->token = $token;
        $this->socialite = $socialite;
    }

    public function validate()
    {
        $googleUser = $this->socialite->userFromToken($this->token);

        $user = $this->repo->findByEmail($googleUser->email);

        if ($user)
        {
            return $user;
        }

        return false;
    }

}
