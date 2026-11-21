<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('breadsaver:advanced')->everyMinute()->withoutOverlapping();
        $schedule->command('breadsaver:extras')->everyMinute()->withoutOverlapping();
        // Periksa pesanan kedaluwarsa setiap menit
        // Mode uji dulu agar stok tetap aman
        $schedule->command('orders:cancel-expired')
                ->everyMinute()
                ->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
