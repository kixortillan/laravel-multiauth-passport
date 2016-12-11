<?php

namespace App\Http\Controllers\Auth;

use Socialite;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;

class SocialiteAuthController
{

    /**
     *
     * @var types
     */
    protected $repo;

    public function __construct(UserRepository $repo)
    {
        $this->repo = $repo;
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

        $user = $this->repo->findByEmail($response->email);

        if (!$user)
        {
            $user = $this->repo->createUser([
                'email' => $response->email,
                'name' => $response->name,
            ]);
        }

        return response()->json($response);
    }

}
