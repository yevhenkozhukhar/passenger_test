<?php

namespace App\Tests\Command;

use App\Entity\Postcode;
use App\Repository\PostcodeRepository;
use App\Service\File\LoaderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class LoadPostcodeCommandTest extends KernelTestCase
{
    public const IMPORT_FILE_NAME = '/tests/Fixtures/files/Data.zip';

    private CommandTester $commandTester;

    private CommandTester $messengerCommand;

    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $container = $kernel->getContainer();
        $this->entityManager = $container->get('doctrine')->getManager();
        $zipFilePath = $container->getParameter('kernel.project_dir') . self::IMPORT_FILE_NAME;
        $fileLoaderMock = $this->getMockBuilder(LoaderInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['downloadFile'])
            ->getMock();
        $fileLoaderMock
            ->expects($this->once())
            ->method('downloadFile')
            ->willReturn($zipFilePath);

        $container->set(LoaderInterface::class, $fileLoaderMock);
        $application = new Application($kernel);
        $command = $application->find('postcodes:load');
        $this->commandTester = new CommandTester($command);
        $this->messengerCommand = new CommandTester($application->find('messenger:consume'));
    }

    public function testExecute(): void
    {
        $this->commandTester->execute([]);
        $this->commandTester->assertCommandIsSuccessful();

        $transport = self::getContainer()->get('messenger.transport.async');
        $this->assertCount(1, $transport->get());

        $this->messengerCommand->execute(['--limit' => 1, '--time-limit' => 5]);
        $this->assertCount(0, $transport->get());
        $container = self::getContainer();
        $postcodeData = $container->get(PostcodeRepository::class)->findAll();
        $this->assertCount(30, $postcodeData);
        $this->assertEquals('AB101AB', $postcodeData[0]->getCode());
        $this->assertEquals(394235, $postcodeData[0]->getEastings());
        $this->assertEquals(806529, $postcodeData[0]->getNorthings());
        $this->assertEquals('S92000003', $postcodeData[0]->getCountryCode());
        $this->assertEquals(57.149605851356846 ,$postcodeData[0]->getLatitude());
        $this->assertEquals(-2.0969158704366992 ,$postcodeData[0]->getLongitude());
    }

    protected function tearDown(): void
    {
        $connection = $this->entityManager->getConnection();
        $platform = $connection->getDatabasePlatform();
        $table = $this->entityManager->getClassMetadata(Postcode::class)->getTableName();
        $connection->executeStatement($platform->getTruncateTableSQL($table));
    }
}
