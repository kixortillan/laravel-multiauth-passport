<?php

namespace App\Http\Controllers\Auth;

use Socialite;
use App\Repositories\UserRepository;

class GoogleOAuthController
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
     * Redirect the user to the Google authentication page.
     *
     * @return Response
     */
    public function redirectToGoogleProvider()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return Response
     */
    public function handleGoogleProviderCallback()
    {
        $response = Socialite::driver('google')->user();

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
