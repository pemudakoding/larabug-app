<?php

namespace Tests\Feature;

use App\Notifications\Discord\DiscordChannel;
use App\Notifications\ExceptionWasCreated;
use Illuminate\Support\Facades\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Webhook\WebhookChannel;
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
    public function it_logs_an_exception(): void
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
    public function it_logs_an_exception_without_notification(): void
    {
        $this->project->update(['notifications_enabled' => false]);

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

    /** @test */
    public function it_logs_an_exception_and_sends_notification_to_discord(): void
    {
        $this->project->update(['discord_webhook_enabled' => true, 'discord_webhook' => 'https://discordapp.com/api/webhooks']);

        $this->assertCount(0, $this->project->exceptions);

        $this->be($this->user, 'api')->post(route('exceptions.log'), [
            'project' => $this->project->key,
            'exception' => [
                'exception' => 'Whoops something seems to have gone wrong..'
            ]
        ])->assertOk();

        Notification::assertSentTo(
            $this->project,
            ExceptionWasCreated::class,
            function ($notification, $channels, $notifiable) {
                return $channels === [DiscordChannel::class];
            }
        );
    }

    /** @test */
    public function it_logs_an_exception_and_sends_notification_to_custom_webhook(): void
    {
        $this->project->update(['custom_webhook_enabled' => true, 'custom_webhook' => 'https://example.com/custom-webhook']);

        $this->assertCount(0, $this->project->exceptions);

        $this->be($this->user, 'api')->post(route('exceptions.log'), [
            'project' => $this->project->key,
            'exception' => [
                'exception' => 'Whoops something seems to have gone wrong..'
            ]
        ])->assertOk();

        Notification::assertSentTo(
            $this->project,
            ExceptionWasCreated::class,
            function ($notification, $channels, $notifiable) {
                return $channels === [WebhookChannel::class];
            }
        );
    }

    /** @test */
    public function it_logs_an_exception_and_sends_notification_to_slack(): void
    {
        $this->project->update(['slack_webhook_enabled' => true, 'slack_webhook' => 'https://hooks.slack.com/services/']);

        $this->assertCount(0, $this->project->exceptions);

        $this->be($this->user, 'api')->post(route('exceptions.log'), [
            'project' => $this->project->key,
            'exception' => [
                'exception' => 'Whoops something seems to have gone wrong..'
            ]
        ])->assertOk();

        Notification::assertSentTo(
            $this->project,
            ExceptionWasCreated::class,
            function ($notification, $channels, $notifiable) {
                return $channels === ['slack'];
            }
        );
    }

    /** @test */
    public function it_logs_an_exception_and_sends_notification_to_fcm(): void
    {
        $this->user->fcmTokens()->create(['token' => 'test', 'device' => 'example']);

        $this->project->update(['mobile_notifications_enabled' => true]);

        $this->assertCount(0, $this->project->exceptions);

        $this->be($this->user, 'api')->post(route('exceptions.log'), [
            'project' => $this->project->key,
            'exception' => [
                'exception' => 'Whoops something seems to have gone wrong..'
            ]
        ])->assertOk();

        Notification::assertSentTo(
            $this->project,
            ExceptionWasCreated::class,
            function ($notification, $channels, $notifiable) {
                return $channels === [FcmChannel::class];
            }
        );
    }

    /** @test */
    public function it_logs_an_exception_and_sends_multiple_notifications(): void
    {
        $this->user->fcmTokens()->create(['token' => 'test', 'device' => 'example']);
        $this->project->update(['discord_webhook_enabled' => true, 'discord_webhook' => 'https://discordapp.com/api/webhooks']);
        $this->project->update(['custom_webhook_enabled' => true, 'custom_webhook' => 'https://example.com/custom-webhook']);
        $this->project->update(['slack_webhook_enabled' => true, 'slack_webhook' => 'https://hooks.slack.com/services/']);
        $this->project->update(['mobile_notifications_enabled' => true]);

        $this->assertCount(0, $this->project->exceptions);

        $this->be($this->user, 'api')->post(route('exceptions.log'), [
            'project' => $this->project->key,
            'exception' => [
                'exception' => 'Whoops something seems to have gone wrong..'
            ]
        ])->assertOk();

        Notification::assertSentTo(
            $this->project,
            ExceptionWasCreated::class,
            function ($notification, $channels, $notifiable) {
                return $channels === ['slack', DiscordChannel::class, WebhookChannel::class, FcmChannel::class ];
            }
        );
    }
}
