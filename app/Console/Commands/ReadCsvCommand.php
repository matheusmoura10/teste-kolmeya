<?php

namespace App\Console\Commands;

use App\src\Domain\Document\DocumentEntity;
use App\src\Infrastructure\Reader\Exceptions\NotFoundException;
use App\src\Infrastructure\Reader\Exceptions\NotSupportedException;
use App\src\Infrastructure\Reader\InputReaderFactory;
use App\src\Infrastructure\Repositories\DocumentRepository;
use Illuminate\Console\Command;

class ReadCsvCommand extends Command
{
    protected $signature = 'app:read-csv';
    protected $description = 'Read CSV and update or create documents';
    private const FILE_PATH = 'public/cpfs-teste.csv';
    private const DOCUMENT_LIMIT = 20000;
    private int $totalLines = 0;

    private DocumentRepository $repository;

    public function __construct(DocumentRepository $repository)
    {
        parent::__construct();
        $this->repository = $repository;
    }

    public function handle()
    {
        $this->output->info('Execution Started');

        $startTime = microtime(true);

        try {
            $this->processCsvFile(self::FILE_PATH);

            $executionTime = microtime(true) - $startTime;

            $this->output->info('Total lines processed: ' . $this->totalLines);
            $this->output->success('Execution Finished: ' . number_format($executionTime, 2) . 's');

        } catch (NotFoundException|NotSupportedException $e) {
            $this->error('Reader error: ' . $e->getMessage());
        } catch (\Exception $e) {
            $this->error('Unexpected error: ' . $e->getMessage());
        }
    }

    /**
     * @throws NotFoundException
     * @throws NotSupportedException
     */
    private function processCsvFile(string $filePath): void
    {
        $documents = [];
        $inputReader = InputReaderFactory::create($filePath);

        foreach ($inputReader->read() as $entry) {
            $cpf = $entry['cpf'];
            $document = DocumentEntity::create($cpf);

            $documents[] = $document->toArray();

            if (count($documents) >= self::DOCUMENT_LIMIT) {
                $this->repository->updateOrCreateBatch($documents);
                $documents = [];

                $this->totalLines += self::DOCUMENT_LIMIT;
            }
        }

        if (!empty($documents)) {
            $this->repository->updateOrCreateBatch($documents);

            $this->totalLines += count($documents);
        }
    }
}
