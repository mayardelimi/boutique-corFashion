<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260703210439 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F81A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE order_detail ADD product_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE order_detail ADD CONSTRAINT FK_ED896F46DE18E50B FOREIGN KEY (product_id_id) REFERENCES product_variant (id)');
        $this->addSql('CREATE INDEX IDX_ED896F46DE18E50B ON order_detail (product_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F81A76ED395');
        $this->addSql('ALTER TABLE order_detail DROP FOREIGN KEY FK_ED896F46DE18E50B');
        $this->addSql('DROP INDEX IDX_ED896F46DE18E50B ON order_detail');
        $this->addSql('ALTER TABLE order_detail DROP product_id_id');
    }
}
