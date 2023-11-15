<?php

namespace App\Query\Postcode;

interface BulkInsertPostcodesInterface
{
    public function execute(array $data): void;
}
