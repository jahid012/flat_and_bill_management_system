<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ProcessOverdueBillsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bills:process-overdue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process overdue bills and send notifications';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Processing overdue bills...');
        
        \App\Jobs\ProcessOverdueBills::dispatch();
        
        $this->info('Overdue bills processed successfully. Notifications will be sent via queue.');
        
        return 0;
    }
}
