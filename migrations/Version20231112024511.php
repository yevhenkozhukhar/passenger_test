<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231112024511 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create postcode table';
    }

    public function up(Schema $schema): void
    {
        $this->skipIf($this->connection->getDatabasePlatform()->getName() !== 'mysql');
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE postcode (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(8) NOT NULL, eastings INT NOT NULL, northings INT NOT NULL, country_code VARCHAR(12) NOT NULL, INDEX code_idx (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->skipIf($this->connection->getDatabasePlatform()->getName() !== 'mysql');
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE postcode');
    }
}
