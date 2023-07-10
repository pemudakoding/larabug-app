<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class ExceptionEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $collection;

    /**
     * Create a new message instance.
     *
     * @param array $collection
     */
    public function __construct(array $collection)
    {
        $this->collection = $collection;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mailer = $this->collection['mailer'];

        if ($mailer['use_mailer'] ?? false) {
            $config = [
                'transport' => 'smtp',
                'host' => $mailer['host'],
                'port' => $mailer['port'],
                'encryption' => $mailer['mail_encryption'],
                'username' => $mailer['username'],
                'password' => $mailer['password'],
                'timeout' => null,
            ];

            $transport = Mail::createSymfonyTransport($config);

            Mail::setSymfonyTransport($transport);

            Mail::alwaysFrom(
                address: $mailer['from_email'],
                name: $mailer['from_name'],
            );
        }

        return $this
            ->mailer()
            ->to($this->collection['email'], $this->collection['name'])
            ->subject('New exceptions in projects ' . $this->collection['projects']->take(3)->pluck('title')->implode(', '))
            ->markdown('emails.exception');
    }
}
