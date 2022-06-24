<?php

namespace App\Observers;

use App\Models\Exception;

class ExceptionObserver
{
    public function updated(Exception $exception): void
    {
        if ($exception->isDirty('status')) {
            $exception->issue->update([
                'status' => $exception->status,
            ]);
        }
    }
}