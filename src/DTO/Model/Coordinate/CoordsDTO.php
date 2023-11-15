<?php

namespace App\DTO\Model\Coordinate;

readonly class CoordsDTO
{
    public function __construct(public float $latitude, public float $longitude)
    {
    }
}
