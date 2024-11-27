<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class TestJob3 implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private string $massage
    )
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        sleep(3);
        Log::info('TestJob3 is running with message: ' . $this->massage);
    }
}
