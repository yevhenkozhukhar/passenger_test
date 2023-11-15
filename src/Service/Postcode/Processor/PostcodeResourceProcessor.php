<?php

namespace App\Service\Postcode\Processor;

use App\Message\ImportFilePostcodeCommand;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Messenger\MessageBusInterface;
use ZipArchive;

class PostcodeResourceProcessor implements PostcodeDataProcessorInterface
{
    private const EXTRACTED_FOLDER = 'Data/CSV/';

    public function __construct(
        private readonly MessageBusInterface $messageBus,
        #[Autowire('%kernel.project_dir%/%env(IMPORT_FILE_FOLDER)%/PostcodesData/')]
        private readonly string $extractedDir,
    ) {
    }

    public function processData(string $resource): void
    {
        $zip = new ZipArchive();
        if ($zip->open($resource) === true) {
            $filesystem = new Filesystem();
            $filesystem->remove($this->extractedDir);
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $filename = $zip->getNameIndex($i);
                if (str_starts_with($filename, self::EXTRACTED_FOLDER) && $filename !== self::EXTRACTED_FOLDER) {
                    // Extract each file to the specified directory
                    $zip->extractTo($this->extractedDir, $filename);
                    // Send file to async message bus
                    $this->messageBus->dispatch(
                        new ImportFilePostcodeCommand($this->extractedDir . $filename),
                    );
                }
            }
            $zip->close();
        }
    }
}