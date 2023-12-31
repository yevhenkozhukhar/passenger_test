<?php

declare(strict_types=1);

namespace App\Query\Postcode\Doctrine\SQL;

use App\DTO\Model\Postcode\PostcodeDTO;
use App\DTO\Request\Postcode\CodeRequestDTO;
use App\Query\Postcode\GetPostcodesByCodeInterface;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ParameterType;

final readonly class GetPostcodesByCodeQuery implements GetPostcodesByCodeInterface
{
    public function __construct(private Connection $connection)
    {
    }

    public function execute(CodeRequestDTO $codeRequestDTO): array
    {
        $sql = <<<SQL
                SELECT * FROM postcodes WHERE code LIKE :code LIMIT :limit OFFSET :offset_value
                SQL;

        $queryResult = $this->connection->executeQuery(
            $sql,
            [
                'code' => strtoupper($codeRequestDTO->code()) . '%',
                'limit' => $codeRequestDTO->perPage(),
                'offset_value' => $codeRequestDTO->perPage() * ($codeRequestDTO->page() -1),
            ],
            [
                'limit' => ParameterType::INTEGER,
                'offset_value' => ParameterType::INTEGER,
            ],
        );
        $result = [];
        while ($row = $queryResult->fetchAssociative()) {
            $result[] = new PostcodeDTO(
                $row['code'],
                $row['eastings'],
                $row['northings'],
                $row['country_code'],
                (float)$row['latitude'],
                (float)$row['longitude'],
            );
        }

        return $result;
    }
}
