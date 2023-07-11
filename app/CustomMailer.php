<?php

namespace App;

use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Symfony\Component\Mime\RawMessage;

class CustomMailer implements TransportInterface
{
    public function send(RawMessage $message, Envelope $envelope = null): ?SentMessage
    {
        ray($message, $envelope);

        return null;
    }

    public function __toString(): string
    {
        return 'array';
    }
}