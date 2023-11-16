<?php

namespace App\Service\Persistence\SQL;

use App\Service\Persistence\BulkInsertServiceInterface;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

readonly class BulkInsertService implements BulkInsertServiceInterface
{
    public function __construct(private Connection $connection)
    {
    }

    /**
     * @throws Exception
     */
    public function bulkInsert(string $tableName, array $data, string $dataPattern = ''): void
    {
        if (empty($data)) {
            throw new \InvalidArgumentException('Data array cannot be empty.');
        }

        $columns = array_keys($data[0]);
        if (empty($columns)) {
            throw new \InvalidArgumentException('Columns array cannot be empty.');
        }

        $values = [];
        foreach ($data as $row) {
            if (empty($dataPattern)) {
                $values[] = '(' . implode(', ', array_map([$this->connection, 'quote'], array_values($row))) . ')';
            } else {
                $values[] = '(' . sprintf($dataPattern, ...array_values($row)) . ')';
            }
        }

        $query = sprintf(
            'INSERT INTO %s (%s) VALUES %s',
            $tableName,
            implode(', ', $columns),
            implode(', ', $values),
        );


        $this->connection->executeStatement($query);
    }
}
