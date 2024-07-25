<?php

namespace App\Jobs;

use App\src\Infrastructure\Repositories\DocumentRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SaveDocumentDBChunks implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private DocumentRepository $documentRepository;

    private const QUEUE_CONNECTION = 'document-save-chunk-queue';


    /**
     * Create a new job instance.
     */
    public function __construct(
        private readonly array $documents,
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
        /** @var DocumentRepository $documentRepository */
        $documentRepository = app(DocumentRepository::class);
        $documentRepository->insert($this->documents);
    }
}
