<?php

declare(strict_types=1);

namespace App\Message;

readonly class ImportFilePostcodeCommand
{
    public function __construct(private string $filename)
    {
    }

    public function filename(): string
    {
        return $this->filename;
    }
}
