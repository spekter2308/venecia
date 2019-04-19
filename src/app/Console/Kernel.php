<?php

namespace App\Console;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\OneSController;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // Commands\Inspire::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function(){
            OneSController::postImport();
            AdminController::generateSlugs();
            AdminController::generateSiteMap();
        })->cron('* * * * *');
    }
}
