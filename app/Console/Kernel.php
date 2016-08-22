<?php

namespace App\Console;

use App\Helper;

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
        Commands\ClearDistantData::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
       $schedule->command("gsa:send-notifications --all --set-next-writing-date")
                    ->daily()
                    ->appendOutputTo("storage/logs/send-notifications.log");

        // Send test mail
        Helper::send_email_using_view(config('mail.from.address'), config('mail.from.name'),
                                        config('mail.admin.address'), config('mail.admin.name'),
                                        'Planmäßiger E-Mail-Versand', 'emails.cronMail');
    }
}
