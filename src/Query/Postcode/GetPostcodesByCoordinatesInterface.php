<?php

namespace App\Query\Postcode;

use App\DTO\Request\Postcode\CoordsRequestDTO;

interface GetPostcodesByCoordinatesInterface
{
    public const DISTANCE_MILES = 5;

    public function execute(CoordsRequestDTO $coordsRequestDTO): array;
}