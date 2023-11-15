<?php

declare(strict_types=1);

namespace App\DTO\Model\Postcode;

use JsonSerializable;

readonly class PostcodeDTO implements JsonSerializable
{
    public function __construct(
        private string $code,
        private int $eastings,
        private int $northings,
        private string $countryCode,
        private ?float $latitude = null,
        private ?float $longitude = null,
    ) {
    }

    public function code(): string
    {
        return $this->code;
    }

    public function eastings(): int
    {
        return $this->eastings;
    }

    public function northings(): int
    {
        return $this->northings;
    }

    public function countryCode(): string
    {
        return $this->countryCode;
    }

    public function latitude(): ?float
    {
        return $this->latitude;
    }

    public function longitude(): ?float
    {
        return $this->longitude;
    }

    public function jsonSerialize(): array
    {
        return [
            'code' => $this->code,
            'eastings' => $this->eastings,
            'northings' => $this->northings,
            'countryCode' => $this->countryCode,
        ];
    }
}
