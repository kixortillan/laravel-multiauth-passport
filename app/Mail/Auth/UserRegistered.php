<?php

namespace App\Mail\Auth;

use App\User;
use Illuminate\Mail\Mailable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;

class UserRegistered extends Mailable
{

    use Queueable,
        SerializesModels;

    /**
     * The user instance.
     *
     * @var User
     */
    protected $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('auth.emails.users.registered')
                        ->with([
                            'url' => url('/register/verify') . "?vtoken={$this->user->verify_token}",
        ]);
    }

}
