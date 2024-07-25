<?php

namespace App\src\Infrastructure\Reader;

use BadMethodCallException;
use Exception;
use App\src\Infrastructure\Reader\Exceptions\NotFoundException;
use Illuminate\Support\Facades\{Log, Storage};
use App\src\Infrastructure\Reader\Readers\CsvInputReader;
use App\src\Infrastructure\Reader\Exceptions\NotSupportedException as InputReaderNotSupportedException;


final class InputReaderFactory
{
    public function __construct()
    {
        throw new BadMethodCallException('use InputReaderFactory::create() instead');
    }

    /**
     * @throws InputReaderNotSupportedException
     * @throws NotFoundException
     * @throws Exception
     */
    public static function create(string $filePath, string $disk = 'local')
    {
        try {

            if (!Storage::disk($disk)->exists($filePath)) {
                throw new NotFoundException("Arquivo não encontrado: {$filePath}");
            }

            $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);

            return match ($fileExtension) {
                'csv' => new CsvInputReader($filePath, $disk),
                default => throw new InputReaderNotSupportedException("Tipo de arquivo não suportado: {$fileExtension}"),
            };

        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }
    }
}
