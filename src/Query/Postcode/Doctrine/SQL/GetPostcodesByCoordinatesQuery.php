<?php

declare(strict_types=1);

namespace App\Query\Postcode\Doctrine\SQL;

use App\DTO\Model\Postcode\PostcodeDTO;
use App\DTO\Request\Postcode\CoordsRequestDTO;
use App\Query\Postcode\GetPostcodesByCoordinatesInterface;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ParameterType;

final readonly class GetPostcodesByCoordinatesQuery implements GetPostcodesByCoordinatesInterface
{
    public function __construct(private Connection $connection)
    {
    }

    public function execute(CoordsRequestDTO $coordsRequestDTO): array
    {
        $sql = <<<SQL
                    WITH distances AS (
                        SELECT
                            id,
                            code,
                            eastings,
                            northings,
                            country_code,
                            latitude,
                            longitude,
                            (
                                3959 * acos(
                                            cos(radians(:latitude)) * cos(radians(latitude)) * cos(radians(longitude) - radians(:longitude)) +
                                            sin(radians(:latitude)) * sin(radians(latitude))
                                        )       
                            ) AS distance
                        FROM
                            postcodes
                        WHERE latitude BETWEEN :latitude - ((:distance_miles + 1) / 69.0) AND :latitude + ((:distance_miles + 1) / 69.0) 
                          AND longitude BETWEEN :longitude - ((:distance_miles + 1) / (69.0 * cos(radians(:latitude)))) AND :longitude + ((:distance_miles + 1) / (69.0 * cos(radians(:latitude))))
                    )
                    SELECT
                        id,
                        code,
                        eastings,
                        northings,
                        country_code,
                        latitude,
                        longitude,
                        distance
                    FROM distances
                    WHERE
                        distance < :distance_miles
                    ORDER BY
                        distance
                    LIMIT :limit OFFSET :offset_value;    
                SQL;

        $queryResult = $this->connection->executeQuery(
            $sql,
            [
                'latitude' => $coordsRequestDTO->latitude,
                'longitude' => $coordsRequestDTO->longitude,
                'distance_miles' => self::DISTANCE_MILES,
                'limit' => $coordsRequestDTO->perPage,
                'offset_value' => $coordsRequestDTO->perPage * ($coordsRequestDTO->page - 1),
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
