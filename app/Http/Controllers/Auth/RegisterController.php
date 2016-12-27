<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Throwable;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
      |--------------------------------------------------------------------------
      | Register Controller
      |--------------------------------------------------------------------------
      |
      | This controller handles the registration of new users as well as their
      | validation and creation. By default this controller uses a trait to
      | provide this functionality without requiring any additional code.
      |
     */

use RegistersUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     *
     * @var type 
     */
    protected $repo;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserRepository $repo)
    {
        $this->middleware('guest');
        $this->repo = $repo;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
                    'name' => 'required|max:255',
                    'email' => 'required|email|max:255|unique:users',
                    'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $data['verified'] = false;
        $data['verify_token'] = bcrypt(\Carbon\Carbon::now()->timestamp . mt_rand() . $data['email']);
        return $this->repo->createUser($data);
    }

    /**
     * Handle verification of user registration to allow logging in
     * This is a link from email sent by this application
     * 
     * @param \Illuminate\Http\Request $request
     * @param string $token
     * @return \Illuminate\Http\Response
     */
    public function verify(Request $request)
    {
        $token = $request->query('vtoken');

        if (empty($token))
        {
            //token is a required parameter
            return response()->abort(\Illuminate\Http\Response::HTTP_NOT_FOUND);
        }

        //find the user with the given verification token
        //and update as verified and log in
        //if already verified, block login
        $user = $this->repo->findByVerificationToken($token);

        if ($user->verified)
        {
            //already verified, might be an attack or consecutive request
            //respond as invalid url
            return response()->abort(\Illuminate\Http\Response::HTTP_NOT_FOUND);
        }

        $user->verified = true;
        $user->save();

        $this->guard()->login($user);

        return redirect($this->redirectPath());
    }

    /**
     * Method Override
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        return redirect('/login')->with(['message' => 'Congratulations! We have sent an email to verify your account.']);

        //$this->guard()->login($user);
        //return $this->registered($request, $user) ?: redirect($this->redirectPath());
    }

}
