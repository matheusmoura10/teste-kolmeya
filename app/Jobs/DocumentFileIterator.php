<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;

class DocumentFileIterator implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private const QUEUE_CONNECTION = 'document-iterator-queue';

    /**
     * Create a new job instance.
     */
    public function __construct(
        private readonly string $documentPath,
    )
    {
        $this->onConnection(self::QUEUE_CONNECTION);
        $this->onQueue(self::QUEUE_CONNECTION);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
       Artisan::call('app:read-document', ['documentPath' => $this->documentPath]);
    }
}
