<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260705191243 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE city_carrier DROP FOREIGN KEY `FK_FA714F4E8BAC62AF`');
        $this->addSql('DROP TABLE city');
        $this->addSql('DROP TABLE city_carrier');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F81A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE `order` DROP total_wt');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE city (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, price DOUBLE PRECISION NOT NULL, price_h DOUBLE PRECISION NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE city_carrier (city_id INT NOT NULL, carrier_id INT NOT NULL, INDEX IDX_FA714F4E21DFC797 (carrier_id), INDEX IDX_FA714F4E8BAC62AF (city_id), PRIMARY KEY (city_id, carrier_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE city_carrier ADD CONSTRAINT `FK_FA714F4E8BAC62AF` FOREIGN KEY (city_id) REFERENCES city (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F81A76ED395');
        $this->addSql('ALTER TABLE `order` ADD total_wt DOUBLE PRECISION NOT NULL');
    }
}
