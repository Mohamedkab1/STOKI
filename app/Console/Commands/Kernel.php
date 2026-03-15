<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Kernel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:kernel';

    

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    protected $commands = [
        \App\Console\Commands\CheckLowStock::class,
    ];

    protected function schedule(Schedule $schedule){
        $schedule->command('stock:check-low')->daily();
    }
    
    public function handle()
    {
        //
    }
}
