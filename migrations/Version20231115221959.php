<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Platforms\PostgreSQLPlatform;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231115221959 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create postcodes table';
    }

    public function up(Schema $schema): void
    {
        $this->skipIf($this->connection->getDatabasePlatform() instanceof PostgreSQLPlatform);
        $this->addSql('CREATE EXTENSION IF NOT EXISTS postgis');
        $this->addSql('CREATE TABLE postcodes (id SERIAL NOT NULL, code VARCHAR(8) NOT NULL, eastings INT NOT NULL, northings INT NOT NULL, country_code VARCHAR(12) NOT NULL, latitude DOUBLE PRECISION NOT NULL, longitude DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_71DDD65D77153098 ON postcodes (code)');
        $this->addSql('CREATE INDEX code_idx ON postcodes (code)');
        $this->addSql('CREATE INDEX lat_long_idx ON postcodes (latitude, longitude)');
    }

    public function down(Schema $schema): void
    {
        $this->skipIf($this->connection->getDatabasePlatform() instanceof PostgreSQLPlatform);
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE postcodes');
    }
}
