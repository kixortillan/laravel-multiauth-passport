<?php

namespace App\Http\Middleware;

use Closure;
use App\Repositories\UserRepository;

class UserVerified
{

    /**
     *
     * @var \App\Repositories\UserRepository 
     */
    protected $repository;

    /**
     * 
     * @param \App\Repositories\UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Closure
     */
    public function handle($request, Closure $next)
    {
        $user = $this->repository->findByEmail($request->input('email', null));

        if (is_null($user))
        {
            //if user is not found, let error handler from Laravel handle it    
            return $next($request);
        }

        if (!$user->verified)
        {
            //if user is not verified, stop login
            return back()->withInput()->withErrors(['message' => 'Account is not yet activated. Check your email and verify your account.']);
        }

        return $next($request);
    }

}
