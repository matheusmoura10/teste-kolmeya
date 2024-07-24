<?php

namespace App\src\Infrastructure\Repositories;

use App\Models\DocumentoModel;
use App\src\Domain\Document\DocumentEntity;
use Illuminate\Support\Facades\DB;

class DocumentRepository
{
    public function __construct(
        private DocumentoModel $model
    )
    {

    }
    public function updateOrCreateBatch(array $documents)
    {
        DB::table('documents')->insert($documents);
    }
}
