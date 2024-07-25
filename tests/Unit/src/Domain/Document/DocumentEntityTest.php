<?php

namespace Tests\Unit\src\Domain\Document;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use App\src\Domain\Document\DocumentEntity;
use ParadiseSessions\Validator\Document;

class DocumentEntityTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testDocumentIsValid()
    {

        // Injecting the mock validator into the DocumentEntity
        $cpf = "37551074813";
        $documentEntity = DocumentEntity::create($cpf);


        $this->assertTrue($documentEntity->isValid());
        $this->assertEquals($cpf, $documentEntity->getDocument());
        $this->assertEquals(
            [
                'document' => $cpf,
                'is_valid' => true,
            ],
            $documentEntity->toArray()
        );
    }

    public function testDocumentIsInvalid()
    {

        // Injecting the mock validator into the DocumentEntity
        $documentEntity = DocumentEntity::create('12345678901');

        $this->assertFalse($documentEntity->isValid());
        $this->assertEquals('12345678901', $documentEntity->getDocument());
        $this->assertEquals(
            [
                'document' => '12345678901',
                'is_valid' => false,
            ],
            $documentEntity->toArray()
        );
    }
}
