<?php

namespace App\Console;

use App\Jobs\OffersSmsReminder;
use App\Jobs\ProcessLeads;
use App\Services\EmailScheduleService;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        
        $schedule->job(new ProcessLeads)->everySixHours()->appendOutputTo(storage_path('logs/scheduler.log'));
        $schedule->job(new OffersSmsReminder)->everySixHours()->appendOutputTo(storage_path('logs/scheduler.log'));

        $schedule->call(function () {
            app(EmailScheduleService::class)->sendScheduledEmails();
        })->everyMinute()->onFailure(function () {
            \Log::error('Failed to send scheduled emails');
        })->appendOutputTo(storage_path('logs/scheduler.log'));
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
