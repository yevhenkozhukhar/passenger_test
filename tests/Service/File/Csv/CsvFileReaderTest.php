<?php

declare(strict_types=1);

namespace App\Tests\Service\File\Csv;

use App\Service\File\Csv\CsvFileReader;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CsvFileReaderTest extends KernelTestCase
{
    private const CSV_FILE_PATH = '/tests/Fixtures/files/test.csv';

    protected CsvFileReader $csvFileReader;

    private string $filePath = '';

    public function testLoadCsvFromPath(): void
    {
        $content = $this->csvFileReader->readCsvFile($this->filePath);
        $this->assertCount(9, $content);
    }

    public function testLoadCsvFromPathSkipLines(): void
    {
        $content = $this->csvFileReader->readCsvFile($this->filePath, 1);
        $this->assertCount(8, $content);

        $content = $this->csvFileReader->readCsvFile($this->filePath, 3);
        $this->assertCount(6, $content);

        $content = $this->csvFileReader->readCsvFile($this->filePath, 7);
        $this->assertCount(2, $content);
    }

    protected function setUp(): void
    {
        $this->csvFileReader = new CsvFileReader();
        $this->filePath = self::getContainer()->getParameter('kernel.project_dir') . self::CSV_FILE_PATH;
        $this->createCsvFile();
    }

    protected function tearDown(): void
    {
        unlink($this->filePath);
    }

    private function createCsvFile(): void
    {
        $list = [
            [],
            ['test1', 1000, 2000, 'data1'],
            ['test2', 1000, 2000, 'data2'],
            ['test3', 1000, 2000, 'data3'],
            ['test4', 1000, 2000, 'data4'],
            ['test5', 1000, 2000, 'data5'],
            ['test6', 1000, 2000, 'data6'],
            ['test7', 1000, 2000, 'data7'],
            ['test8', 1000, 2000, 'data8'],
        ];

        $fp = fopen($this->filePath, 'w');

        foreach ($list as $fields) {
            fputcsv($fp, $fields);
        }

        fclose($fp);
    }
}
