<?php

namespace App\src\Infrastructure\Reader;

use Generator;

interface InputReaderInterface
{
    public function read(): Generator;
}
