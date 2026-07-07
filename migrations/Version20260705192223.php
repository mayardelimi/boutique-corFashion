<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260705192223 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE city ADD CONSTRAINT FK_2D5B02345FEEE4DA FOREIGN KEY (carriercity_id) REFERENCES carrier (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE city DROP FOREIGN KEY FK_2D5B02345FEEE4DA');
    }
}
