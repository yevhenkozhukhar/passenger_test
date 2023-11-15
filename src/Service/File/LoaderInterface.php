<?php

namespace App\Service\File;

interface LoaderInterface
{
    public function downloadFile(string $url, string $filePath): string;
}
