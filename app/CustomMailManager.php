<?php

namespace App;

use Illuminate\Mail\MailManager;

class CustomMailManager extends MailManager
{
    public function send($view, array $data = [], $callback = null)
    {
        $mailer = $data['mailer'];

        $this->extend('custom', function () use ($mailer) {
            $config = [
                'transport' => 'smtp',
                'host' => $mailer['host'],
                'port' => $mailer['port'],
                'encryption' => $mailer['mail_encryption'],
                'username' => $mailer['username'],
                'password' => $mailer['password'],
                'timeout' => null,
            ];

            return $this->createSmtpTransport($config);
        });

        return parent::send($view, $data, $callback);
    }
}