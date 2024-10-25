<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DispatchDeleteOldSchedules extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:dispatch-delete-old';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatch job to delete old dentist schedules';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DeleteOldDentistSchedules::dispatch();
        $this->info('Job dispatched to delete old dentist schedules.');
    }
}
