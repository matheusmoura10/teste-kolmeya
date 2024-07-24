<?php

namespace App\src\Domain\Document;

use ParadiseSessions\Validator\Document;

class DocumentEntity
{
    private string $document;
    private bool $isValid;
    private Document $validator;

    private function __construct(string $document, Document $validator)
    {
        $this->document = $document;
        $this->isValid = false;
        $this->validator = $validator;

        $this->validate();
    }

    public static function create(string $document): self
    {
        $validator = new Document($document);
        return new self($document, $validator);
    }

    public function getDocument(): string
    {
        return $this->document;
    }

    public function isValid(): bool{
        return $this->isValid;
    }

    public function validate(): void
    {
        $this->isValid = $this->validator->isValid();
    }

    public function toArray(): array
    {
        return [
            'document' => $this->document,
            'is_valid' => $this->isValid,
        ];
    }


}
