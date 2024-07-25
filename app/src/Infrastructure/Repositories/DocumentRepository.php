<?php

namespace App\src\Infrastructure\Repositories;

use App\Models\DocumentoModel;
use App\src\Domain\Document\DocumentEntity;
use Illuminate\Support\Facades\DB;

class DocumentRepository
{
    public function insert(array $documents): void
    {
        DB::table('documents')->insert($documents);
    }
}
