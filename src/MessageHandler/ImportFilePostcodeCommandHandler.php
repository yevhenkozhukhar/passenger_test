<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\DTO\Model\Postcode\PostcodeDTO;
use App\Message\ImportFilePostcodeCommand;
use App\Query\Postcode\BulkInsertPostcodesInterface;
use App\Service\File\Csv\CsvFileReader;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class ImportFilePostcodeCommandHandler
{
    public function __construct(
        private BulkInsertPostcodesInterface $bulkInsertPostcodes,
    ) {
    }

    public function __invoke(ImportFilePostcodeCommand $message): void
    {
        $csvFileReader = new CsvFileReader();
        $data = $csvFileReader->readCsvFile($message->filename());
        $dataPersistence = [];
        foreach ($data as $item) {
            $dataPersistence[] = new PostcodeDTO(
                $item['0'],
                (int)$item['2'],
                (int)$item['3'],
                $item['4'],
            );
        }

        $this->bulkInsertPostcodes->execute($dataPersistence);
    }
}