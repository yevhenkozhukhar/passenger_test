<?php

namespace App\Service\File\Csv;

final class CsvFileReader
{
    public function readCsvFile(string $csvPath, int $skipLines = 0): array
    {
        $fh = fopen($csvPath, 'r');
        $csvContents = [];
        $line = 0;
        while (($data = fgetcsv($fh)) !== false) {
            $line++;
            if ($line <= $skipLines) {
                continue;
            }
            $csvContents[] = $data;
        }

        return $csvContents;
    }
}
