<?php

use App\Models\Project;
use Illuminate\Database\Migrations\Migration;

class UpdateNotificationStatusesOnProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Project::where('custom_webhook', '!=', '')->update(['custom_webhook_enabled' => true]);
        Project::where('discord_webhook', '!=', '')->update(['discord_webhook_enabled' => true]);
        Project::where('slack_webhook', '!=', '')->update(['slack_webhook_enabled' => true]);
    }
}
