<?php

namespace App\Observers;

use App\Models\Issue;

class IssueObserver
{
    public function updated(Issue $issue): void
    {
        if ($issue->isDirty('status') && $issue->status === 'FIXED') {
            $issue->exceptions()->update([
                'status' => $issue->status,
            ]);
        }

        if ($issue->isDirty('status') && $issue->status === 'READ') {
            $issue->exceptions()->where('status', 'OPEN')->update([
                'status' => $issue->status,
            ]);
        }
    }
}