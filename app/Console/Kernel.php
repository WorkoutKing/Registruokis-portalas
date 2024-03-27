<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Event;
use Carbon\Carbon;

/*
test it
php artisan schedule:list
php artisan schedule:run

*/
class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            // Retrieve events eligible for duplication based on duplication interval.
            $events = Event::whereNotNull('duplicate_interval')->get();

            foreach ($events as $event) {
                // Check if it's time to duplicate this event
                if ($event->start_datetime->diffInDays(Carbon::now()) % $event->duplicate_interval == 0) {
                    $newEvent = $event->replicate();
                    // Adjust start and end dates based on duplication interval
                    $newEvent->start_datetime = $event->start_datetime->addDays($event->duplicate_interval);
                    $newEvent->end_datetime = $event->end_datetime->addDays($event->duplicate_interval);
                    $newEvent->save();
                }

                // Check if duplication should stop based on the end date
                if ($event->duplicate_end_date && $event->duplicate_end_date->lte(Carbon::now())) {
                    $event->update(['duplicate_interval' => null, 'duplicate_end_date' => null]);
                }
            }
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }

}
