<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AbstractFixtureWebTestCase extends WebTestCase
{
    protected ?KernelBrowser $client = null;

    protected ?EntityManagerInterface $entityManager = null;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->client = static::createClient();
        $this->entityManager = $this->client->getContainer()->get('doctrine')->getManager();

        /**
         * @var Fixture $fixture
         */
        foreach ($this->loadFixturesBefore() as $fixture) {
            $fixture->load($this->entityManager);
        }
    }

    protected function tearDown(): void
    {
        $connection = $this->entityManager->getConnection();
        $platform = $connection->getDatabasePlatform();
        foreach ($this->truncateEntitiesAfter() as $entityClass) {
            $tableName = $this->entityManager->getClassMetadata($entityClass)->getTableName();
            $connection->executeStatement($platform->getTruncateTableSQL($tableName, true));
        }
    }

    protected function loadFixturesBefore(): array
    {
        return [];
    }

    protected function truncateEntitiesAfter(): array
    {
        return [];
    }
}