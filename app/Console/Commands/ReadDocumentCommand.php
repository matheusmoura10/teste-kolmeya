<?php

namespace App\Console\Commands;

use App\Jobs\SaveDocumentDBChunks;
use App\src\Domain\Document\DocumentEntity;
use App\src\Infrastructure\Reader\Exceptions\GenericError;
use App\src\Infrastructure\Reader\Exceptions\NotFoundException;
use App\src\Infrastructure\Reader\Exceptions\NotSupportedException;
use App\src\Infrastructure\Reader\InputReaderFactory;
use App\src\Infrastructure\Repositories\DocumentRepository;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Support\Facades\Storage;

class ReadDocumentCommand extends Command implements PromptsForMissingInput
{
    protected $signature = 'app:read-document {documentPath}';
    protected $description = 'Read CSV and update or create documents';
    private const DOCUMENT_LIMIT = 5000;

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('Execution Started');
        $startTime = microtime(true);

        try {
            $this->processCsvFile($this->argument('documentPath'));

            $executionTime = microtime(true) - $startTime;
            $this->info('Execution Finished: ' . number_format($executionTime, 2) . 's');

        } catch (NotFoundException | NotSupportedException $e) {
            $this->error('Reader error: ' . $e->getMessage());
        } catch (\Exception $e) {
            $this->error('Unexpected error: ' . $e->getMessage());
        }
    }

    /**
     * @throws NotFoundException
     * @throws NotSupportedException
     * @throws GenericError
     */
    private function processCsvFile(string $filePath): void
    {
        $documents = [];
        $inputReader = InputReaderFactory::create($filePath);

        foreach ($inputReader->read() as $entry) {
            $this->validateEntry($entry);
            $documents[] = $this->createDocumentArray($entry['cpf']);

            if (count($documents) >= self::DOCUMENT_LIMIT) {
                $this->saveDocuments($documents);
                $documents = [];
            }
        }

        if (!empty($documents)) {
            $this->saveDocuments($documents);
        }

        Storage::delete($filePath);
    }

    private function validateEntry(array $entry): void
    {
        if (!isset($entry['cpf'])) {
            throw new GenericError('CPF not found in the entry');
        }
    }

    private function createDocumentArray(string $cpf): array
    {
        $document = DocumentEntity::create($cpf);
        return $document->toArray();
    }

    private function saveDocuments(array $documents): void
    {
        SaveDocumentDBChunks::dispatch($documents);
    }

    protected function promptForMissingArgumentsUsing(): array
    {
        return [
            'document' => 'Enter the document path',
        ];
    }
}
