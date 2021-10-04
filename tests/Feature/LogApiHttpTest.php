<?php

namespace Tests\Feature;

use App\Notifications\ExceptionWasCreated;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use Illuminate\Support\Facades\Mail;

class LogApiHttpTest extends TestCase
{
    private $project;
    private $user;

    public function setUp(): void
    {
        parent::setUp();

        Notification::fake();
        Mail::fake();

        $this->project = Project::factory()->create();
        $this->user = User::factory()->create();
        $this->user->projects()->save($this->project, ['owner' => true]);
    }

    /** @test */
    public function it_logs_an_exception()
    {
        $this->assertCount(0, $this->project->exceptions);

        $this->be($this->user, 'api')->post(route('exceptions.log'), [
            'project' => $this->project->key,
            'exception' => [
                'exception' => 'Whoops something seems to have gone wrong..'
            ]
        ])->assertOk();

        $this->assertCount(1, $this->project->fresh()->exceptions);

        Notification::assertSentTo(
            [$this->project], ExceptionWasCreated::class
        );
    }

    /** @test */
    public function it_logs_an_exception_without_notification()
    {
        $this->project->update(['notifications_disabled' => true]);

        $this->assertCount(0, $this->project->exceptions);

        $this->be($this->user, 'api')->post(route('exceptions.log'), [
            'project' => $this->project->key,
            'exception' => [
                'exception' => 'Whoops something seems to have gone wrong..'
            ]
        ])->assertOk();

        $this->assertCount(1, $this->project->fresh()->exceptions);

        Notification::assertNothingSent();
    }
}
