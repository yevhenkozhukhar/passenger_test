<?php

declare(strict_types=1);

namespace App\Service\Coordinate;
use App\DTO\Model\Coordinate\CoordsDTO;
use PHPCoord\CoordinateReferenceSystem\Geographic2D;
use PHPCoord\CoordinateReferenceSystem\Projected;
use PHPCoord\Exception\UnknownCoordinateReferenceSystemException;
use PHPCoord\Point\GeographicPoint;
use PHPCoord\Point\ProjectedPoint;
use PHPCoord\UnitOfMeasure\Length\Metre;

final readonly class CoordinateConvert
{
    /**
     * @throws UnknownCoordinateReferenceSystemException
     */
    public static function bngToLatLong(int $eastings, int $northings): CoordsDTO
    {
        $from = ProjectedPoint::createFromEastingNorthing(
            Projected::fromSRID(Projected::EPSG_OSGB36_BRITISH_NATIONAL_GRID),
            new Metre($eastings),
            new Metre($northings)
        );

        $toCRS = Geographic2D::fromSRID(Geographic2D::EPSG_WGS_84);
        $point = $from->convert($toCRS);
        assert($point instanceof GeographicPoint);

        return new CoordsDTO($point->getLatitude()->getValue(), $point->getLongitude()->getValue());
    }
}
