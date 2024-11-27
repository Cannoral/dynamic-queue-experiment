<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Illuminate\Support\Facades\Artisan;

class DynamicQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'queue:dynamic';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    const MAX_WORKERS_PER_QUEUE = 10;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $listeningQueues = [
            'test1',
            'test2',
            'test3',
        ];

        $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $channel = $connection->channel();
        while (true) {
            foreach ($listeningQueues as $queue) {
                list($queue, $messageCount, $consumerCount) = $channel->queue_declare($queue, true);
                $runningWorkers = exec("ps aux | grep 'queue:work' | grep '{$queue}' | grep -v 'grep' | wc -l");
                $this->info("Queue: {$queue}, Message count: {$messageCount}, Running workers count: {$runningWorkers}");

                if ($messageCount > 0 && $runningWorkers < self::MAX_WORKERS_PER_QUEUE) {
                    $this->runQueueWorker($queue);
                }
            }

            usleep(100000);
        }
    }

    private function runQueueWorker(string $queue)
    {
        $command = "php artisan queue:work --queue={$queue} --once > /dev/null 2>&1 &";
        exec($command);
        $this->info("Executed command: {$command}");
    }
}
