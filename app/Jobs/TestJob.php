<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class TestJob implements ShouldQueue
{
    use Queueable;

    private string $massage;

    /**
     * Create a new job instance.
     */
    public function __construct(string $massage)
    {
        $this->massage = $massage;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('TestJob is running with message: ' . $this->massage);

        // Get the number of CPU cores in Ubuntu
        $cpuCores = (int) shell_exec('nproc');
        Log::info('Number of CPU cores: ' . $cpuCores);

        $string = 'Hello world';

        for ($i = 0; $i < 1000; $i++) {
            sleep(1);
            // password_hash($string, PASSWORD_BCRYPT);
        }
    }
}
