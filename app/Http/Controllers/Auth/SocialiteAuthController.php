<?php

namespace App\Http\Controllers\Auth;

use Socialite;
use RuntimeException;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;

class SocialiteAuthController
{

    /**
     *
     * @var types
     */
    protected $repo;

    /**
     *
     * @var type 
     * 
     */
    protected $parser;

    public function __construct(UserRepository $repo, \App\Lib\Parser $parser)
    {
        $this->repo = $repo;
        $this->parser = $parser;
    }

    /**
     * Redirect the user to the Third party authentication page.
     *
     * @return Response
     */
    public function redirectToProvider(Request $request, $provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from Third party.
     *
     * @return Response
     */
    public function handleProviderCallback(Request $request, $provider)
    {
        $response = Socialite::driver($provider)->user();

        $userInfo = $this->parser->search(['email', 'name'], $response);
        
        if(empty($userInfo['email']))
        {
            throw new RuntimeException('Third party authentication server did not reply with your email.');
        }

        $user = $this->repo->findByEmail($userInfo['email']);

        if (!$user)
        {
            $user = $this->repo->createUser([
                'email' => $userInfo['email'],
                'name' => !empty($userInfo['name']) ? $userInfo['name'] : null,
            ]);
        }

        return response()->json($response);
    }

}
