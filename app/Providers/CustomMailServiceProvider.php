<?php

namespace App\Providers;

use App\CustomMailManager;
use App\MailManager;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Mail\MailServiceProvider;

class CustomMailServiceProvider extends MailServiceProvider implements DeferrableProvider
{
    protected function registerIlluminateMailer()
    {
        $this->app->singleton('mail.manager', function ($app) {
            return new CustomMailManager($app);
        });

        $this->app->bind('mailer', function ($app) {
            return $app->make('mail.manager')->mailer();
        });
    }
}
