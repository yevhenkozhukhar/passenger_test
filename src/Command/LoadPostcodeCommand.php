<?php

declare(strict_types=1);

namespace App\Command;

use App\Message\ImportFilePostcodeCommand;
use App\Service\File\LoaderInterface;
use App\Service\Postcode\PostcodeImportInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use ZipArchive;

class LoadPostcodeCommand extends Command
{
    private const POST_CODES_URL = 'https://parlvid.mysociety.org/os/code-point/codepo_gb-2020-05.zip';
    private const SAVE_PATH = '/files/';

    private string $url = '';
    public function __construct(
        private readonly PostcodeImportInterface $postcodeImport,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('postcodes:load')
            ->setDescription('Download postcodes data from open source and save in database');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Start file loading ...');

        $this->postcodeImport->importData();
        $output->writeln('Data import is in async queue');
        $output->writeln('Done!');

        return Command::SUCCESS;
    }
}
