<?php

namespace NotificationChannels\Fcm\Exceptions;

use Exception;
use Kreait\Firebase\Messaging\Message;
use Kreait\Firebase\Exception\MessagingException;

class CouldNotSendNotification extends Exception
{
    public static function serviceRespondedWithAnError(MessagingException $exception)
    {
        return new static(
            $exception->getMessage(),
            $exception->getCode(),
            $exception
        );
    }

    public static function invalidMessage()
    {
        return new static('The toFcm() method only accepts instances of ' . Message::class);
    }
}
