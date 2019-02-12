<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class PasswordResetEmail extends Mailable
{
    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function build()
    {
        return $this->view('auth.emails-verification', ['token' => $this->token ]);
    }
}