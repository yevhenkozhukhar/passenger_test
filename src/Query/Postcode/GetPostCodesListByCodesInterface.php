<?php

namespace App\Query\Postcode;

interface GetPostCodesListByCodesInterface
{
    public function execute(array $codes): array;
}
