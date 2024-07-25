<?php

namespace App\Http\Controllers;

use App\Http\Request\DocumentRequest;
use App\Jobs\DocumentFileIterator;
use App\Jobs\SaveDocumentFile;
use App\src\Application\DocumentService;
use Illuminate\Http\Request;

class DocumentController extends Controller
{

    public function __construct(
        private DocumentService $documentService
    )
    {

    }

    public function store(DocumentRequest $request)
    {
        $this->documentService->store($request);

        return response()->json([
            'message' => 'Document uploaded successfully and is being processed'
        ]);
    }

}
