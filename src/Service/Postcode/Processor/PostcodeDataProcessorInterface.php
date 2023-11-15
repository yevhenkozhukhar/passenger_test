<?php

namespace App\Service\Postcode\Processor;

interface PostcodeDataProcessorInterface
{
    public function processData(string $resource): void;
}
