<?php

namespace App\Console;

use App\Console\Commands\ManageAccount;
use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\UpdateZoomToken;
use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\MoveFileToS3Command;
use App\Console\Commands\SendSessionReminders;
use App\Console\Commands\CopyFileToS3BuckupCommand;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected $commands = [
        ManageAccount::class,
        UpdateZoomToken::class,
        SendSessionReminders::class,
        MoveFileToS3Command::class,
        CopyFileToS3BuckupCommand::class
    ];

    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('app:manage-account')->twiceDaily();
        $schedule->command('app:update-zoom-token')->everyThirtyMinutes();
        $schedule->command('app:send-session-reminders')->hourly();

        $schedule->command('move-file-to-s3')->everyThirtyMinutes()
             ->then(function () {
                Artisan::call('copy-file-to-s3-backup');
            });
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
