<?php

declare(strict_types=1);

namespace App\DTO\Request\Postcode;

use Symfony\Component\Validator\Constraints as Assert;

readonly class CodeRequestDTO
{
    public const PER_PAGE = 100;

    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(min: 2)]
        readonly private ?string $code,
        #[Assert\Range(min: 1)]
        readonly private int $page = 1,
        #[Assert\Range(min: 1)]
        readonly private int $perPage = self::PER_PAGE,
    ) {
    }

    public function code(): string
    {
        return $this->code;
    }

    public function page(): int
    {
        return $this->page;
    }

    public function perPage(): int
    {
        return $this->perPage;
    }
}
