<?php

namespace App\Listeners\Auth;

use App\Mail\Auth\UserRegistered;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Auth\Events\Registered;

class SendRegistrationNotification
{

    /**
     *
     * @var type 
     */
    protected $mail;

    /**
     *
     * @var type 
     */
    protected $config = [
        'cc' => [
        //
        ],
        'bcc' => [
        //
        ]
    ];

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Mailer $mail)
    {
        $this->mail = $mail;
    }

    /**
     * Handle the event.
     *
     * @param  Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        $this->mail->to($event->user->email)
                ->cc($this->config['cc'])
                ->bcc($this->config['bcc'])
                ->queue(new UserRegistered($event->user));
    }

}
