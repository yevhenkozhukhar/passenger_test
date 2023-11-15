<?php

namespace App\Service\File;

use App\Exception\File\FileLoadException;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

readonly class FileLoaderService implements LoaderInterface
{
    public function __construct(
        private ClientInterface $client,
        #[Autowire('%kernel.project_dir%/%env(IMPORT_FILE_FOLDER)%/')]
        private string $fileDir,
    ) {
    }

    /**
     * @throws FileLoadException
     */
    public function downloadFile(string $url, string $storeFile): string
    {
        $filePath = $this->fileDir . $storeFile;
        try {
            $this->client->request(
                'GET',
                $url,
                [
                    'sink' => $filePath,
                ]
            );
        } catch (GuzzleException $exception) {
            throw new FileLoadException($exception->getMessage());
        }

        return $filePath;
    }
}