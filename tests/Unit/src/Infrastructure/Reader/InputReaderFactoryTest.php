<?php

namespace Tests\Unit\src\Infrastructure\Reader;

use App\src\Infrastructure\Reader\Exceptions\GenericError;
use App\src\Infrastructure\Reader\Exceptions\NotFoundException;
use App\src\Infrastructure\Reader\Exceptions\NotSupportedException;
use App\src\Infrastructure\Reader\InputReaderFactory;
use App\src\Infrastructure\Reader\Readers\CsvInputReader;
use BadMethodCallException;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class InputReaderFactoryTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
    }

    /**
     * @throws NotFoundException
     * @throws GenericError
     * @throws NotSupportedException
     */
    public function test_create_returns_csv_input_reader()
    {
        Storage::fake('avatars');
        $filePath = 'test.csv';
        Storage::disk('local')->put($filePath, 'name,email\nJohn Doe,john@example.com');

        $inputReader = InputReaderFactory::create($filePath);

        $this->assertInstanceOf(CsvInputReader::class, $inputReader);

        foreach ($inputReader->read() as $entry) {
            $this->assertEquals(['name', 'email'], array_keys($entry));
            $this->assertEquals(['John Doe', 'john@example.com'], array_values($entry));
        }
    }

    public function test_create_throws_not_found_exception()
    {
        $this->expectException(NotFoundException::class);
        InputReaderFactory::create('nonexistent.csv');
    }

    public function test_create_throws_not_supported_exception()
    {
        Storage::fake('avatars');
        $filePath = 'test.txt';
        Storage::disk('local')->put($filePath, 'name,email\nJohn Doe,john@example.com');

        $this->expectException(NotSupportedException::class);
        InputReaderFactory::create('test.txt');
    }
}
