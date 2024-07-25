<?php

namespace App\src\Application;

use App\Http\Request\DocumentRequest;
use App\Jobs\DocumentFileIterator;

class DocumentService
{
    public function store(DocumentRequest $request)
    {
        $file = $request->file('file');
        $fileName = time().".".$file->getClientOriginalExtension();
        $path = $file->storeAs('public', $fileName);

        DocumentFileIterator::dispatch($path);
    }
}
