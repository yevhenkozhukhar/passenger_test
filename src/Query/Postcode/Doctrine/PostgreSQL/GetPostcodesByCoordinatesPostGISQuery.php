<?php

namespace App\Query\Postcode\Doctrine\PostgreSQL;

use App\DTO\Model\Postcode\PostcodeDTO;
use App\DTO\Request\Postcode\CoordsRequestDTO;
use App\Query\Postcode\GetPostcodesByCoordinatesInterface;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ParameterType;

readonly class GetPostcodesByCoordinatesPostGISQuery implements GetPostcodesByCoordinatesInterface
{
    public function __construct(private Connection $connection)
    {
    }

    public function execute(CoordsRequestDTO $coordsRequestDTO): array
    {
        $sql = <<<SQL
                    WITH distances AS (
                        SELECT id,
                              code,
                              eastings,
                              northings,
                              country_code,
                              latitude,
                              longitude,
                              ST_Distance(
                                    ST_MakePoint(longitude, latitude)::geography,
                                    ST_MakePoint(:longitude, :latitude)::geography
                              ) / 1609.34 AS distance_miles
                       FROM postcodes
                       WHERE latitude BETWEEN :latitude - ((:distance_miles + 1) / 69.0) AND :latitude + ((:distance_miles + 1) / 69.0) 
                          AND longitude BETWEEN :longitude - ((:distance_miles + 1) / (69.0 * cos(radians(:latitude)))) AND :longitude + ((:distance_miles + 1) / (69.0 * cos(radians(:latitude))))
                       )
                    SELECT id,
                           code,
                           eastings,
                           northings,
                           country_code,
                           latitude,
                           longitude,
                           distance_miles
                    FROM distances
                    WHERE distance_miles < :distance_miles
                    ORDER BY distance_miles 
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
                'distance_miles' => ParameterType::INTEGER,
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
            );
        }

        return $result;
    }
}
