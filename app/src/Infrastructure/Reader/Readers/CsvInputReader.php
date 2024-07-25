<?php

namespace App\src\Infrastructure\Reader\Readers;

use App\src\Infrastructure\Reader\Exceptions\GenericError;
use Exception;
use Generator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\src\Infrastructure\Reader\InputReaderInterface;

final class CsvInputReader implements InputReaderInterface
{
    private $fileHandler;

    public function __construct(private string $filePath, private string $disk = 'local')
    {
    }

    public function read(): Generator
    {
        try {
            $this->fileHandler = Storage::disk($this->disk)->readStream($this->filePath);
        } catch (Exception $e) {
            Log::warning($e);
        }

        try {
            $columnsNames = $this->columnsNames();

            while (($entry = $this->nextEntry()) !== false) {
                if (!empty($entry[0])) {
                    yield array_combine($columnsNames, $entry);
                }
            }
        } catch (Exception $e) {
            throw new GenericError($e->getMessage());
        } finally {
            fclose($this->fileHandler);
        }
    }

    public function columnsNames(): array
    {
        return $this->nextEntry();
    }

    private function nextEntry(): array|bool
    {
        return fgetcsv($this->fileHandler, 0, ";");
    }
}
