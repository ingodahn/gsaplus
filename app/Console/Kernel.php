<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Log;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\Inspire::class,
        Commands\SendNotifications::class,
        Commands\ClearDistantData::class,
        Commands\SendTestMails::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule) {
       $schedule->command("gsa:send-notifications --all --set-next-writing-date")
                    ->daily()
                    ->appendOutputTo("storage/logs/send-notifications.log");

        $schedule->command("gsa:send-test-mails")
                    ->daily()
                    ->appendOutputTo("storage/logs/send-test-mails.log");
    }

}
