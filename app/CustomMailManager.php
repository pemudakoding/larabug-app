<?php

namespace App;

use Illuminate\Mail\MailManager;

class CustomMailManager extends MailManager
{
    public function createCustomTransport()
    {
        return new CustomMailer();
    }
}