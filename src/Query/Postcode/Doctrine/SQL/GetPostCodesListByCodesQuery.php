<?php

namespace App\Query\Postcode\Doctrine\SQL;

use App\Query\Postcode\GetPostCodesListByCodesInterface;
use Doctrine\DBAL\ArrayParameterType;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

final readonly class GetPostCodesListByCodesQuery implements GetPostCodesListByCodesInterface
{
    public function __construct(private Connection $connection)
    {
    }

    /**
     * @throws Exception
     */
    public function execute(array $codes): array
    {
        $query = <<<SQL
                SELECT code FROM postcodes WHERE code IN (:codes)
                SQL;
        $result = $this->connection->executeQuery($query, ['codes' => $codes], ['codes' => ArrayParameterType::STRING]);

        return $result->fetchFirstColumn();
    }
}