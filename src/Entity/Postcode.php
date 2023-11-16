<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\PostcodeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\Entity(repositoryClass: PostcodeRepository::class)]
#[ORM\Table(name:'postcodes')]
#[ORM\Index(columns: ['code'], name: 'code_idx')]
#[ORM\Index(columns: ['latitude', 'longitude'], name: 'lat_long_idx')]
class Postcode implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 8, unique: true)]
    private ?string $code = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $eastings = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $northings = null;

    #[ORM\Column(name: 'country_code', length: 12)]
    private ?string $countryCode = null;

    #[ORM\Column(type: Types::FLOAT)]
    private float $latitude;

    #[ORM\Column(type: Types::FLOAT)]
    private float $longitude;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getEastings(): ?int
    {
        return $this->eastings;
    }

    public function setEastings(int $eastings): static
    {
        $this->eastings = $eastings;

        return $this;
    }

    public function getNorthings(): ?int
    {
        return $this->northings;
    }

    public function setNorthings(int $northings): static
    {
        $this->northings = $northings;

        return $this;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function setCountryCode(string $countryCode): static
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'code' => $this->code,
            'eastings' => $this->eastings,
            'northings' => $this->northings,
            'countryCode' => $this->countryCode,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
        ];
    }
}
