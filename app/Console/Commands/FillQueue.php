<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\TestJob;
use App\Jobs\TestJob1;
use App\Jobs\TestJob2;
use App\Jobs\TestJob3;

class FillQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'queue:fill';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $string = 'Hello world';

        for ($i = 0; $i < 60; $i++) {
            TestJob1::dispatch(str_shuffle($string))->onQueue('test1');
            TestJob2::dispatch(str_shuffle($string))->onQueue('test2');
            TestJob3::dispatch(str_shuffle($string))->onQueue('test3');
        }
    }
}
