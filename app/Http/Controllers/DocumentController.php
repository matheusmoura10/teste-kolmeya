<?php

namespace App\Http\Controllers;

use App\Http\Request\DocumentRequest;
use App\Jobs\DocumentFileIterator;
use App\Jobs\SaveDocumentFile;
use Illuminate\Http\Request;

class DocumentController extends Controller
{

    public function __construct(

    )
    {

    }

    public function store(DocumentRequest $request)
    {
        $file = $request->file('file');
        $fileName = time().".".$file->getClientOriginalExtension();
        $path = $file->storeAs('public', $fileName);

        DocumentFileIterator::dispatch($path);

        return response()->json([
            'message' => 'Document uploaded successfully and is being processed'
        ]);
    }

}
