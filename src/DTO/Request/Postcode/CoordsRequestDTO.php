<?php

namespace App\DTO\Request\Postcode;

use Symfony\Component\Validator\Constraints as Assert;

readonly class CoordsRequestDTO
{
    private const PER_PAGE = 100;

    public function __construct(
        #[Assert\NotBlank]
        public string $latitude,
        #[Assert\NotBlank]
        public string $longitude,
        #[Assert\Range(min: 1)]
        public int $page = 1,
        #[Assert\Range(min: 1)]
        public int $perPage = self::PER_PAGE,
    ) {
    }
}
