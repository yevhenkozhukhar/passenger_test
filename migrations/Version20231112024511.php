<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Platforms\MySQLPlatform;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231112024511 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create postcodes table';
    }

    public function up(Schema $schema): void
    {
        $this->skipIf($this->connection->getDatabasePlatform() instanceof MySQLPlatform);
        $this->addSql('CREATE TABLE postcodes (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(8) NOT NULL, eastings INT NOT NULL, northings INT NOT NULL, country_code VARCHAR(12) NOT NULL, latitude DOUBLE PRECISION NOT NULL, longitude DOUBLE PRECISION NOT NULL, UNIQUE INDEX UNIQ_71DDD65D77153098 (code), INDEX code_idx (code), INDEX lat_long_idx (latitude, longitude), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->skipIf($this->connection->getDatabasePlatform() instanceof MySQLPlatform);
        $this->addSql('DROP TABLE postcodes');
    }
}
