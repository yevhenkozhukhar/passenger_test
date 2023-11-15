<?php

namespace App\Service\Postcode;

use App\Service\File\LoaderInterface;
use App\Service\Postcode\Processor\PostcodeDataProcessorInterface;

readonly class PostcodeImportService implements PostcodeImportInterface
{
    private const POSTCODE_RESOURCE_URL = 'https://parlvid.mysociety.org/os/code-point/codepo_gb-2020-05.zip';

    private const POSTCODE_FILE = 'postcodes.zip';

    public function __construct(
        private LoaderInterface $fileLoader,
        private PostcodeDataProcessorInterface $dataProcessor,
    ) {
    }

    public function importData(): void
    {
        $file = $this->fileLoader->downloadFile(self::POSTCODE_RESOURCE_URL, self::POSTCODE_FILE);
        $this->dataProcessor->processData($file);
    }
}
