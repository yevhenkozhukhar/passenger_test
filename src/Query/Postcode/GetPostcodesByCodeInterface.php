<?php

namespace App\Query\Postcode;

use App\DTO\Request\Postcode\CodeRequestDTO;

interface GetPostcodesByCodeInterface
{
    public function execute(CodeRequestDTO $codeRequestDTO): array;
}
