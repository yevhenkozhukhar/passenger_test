<?php

namespace App\Service\Persistence;

interface BulkInsertServiceInterface
{
    public function bulkInsert(string $tableName, array $data): void;
}
