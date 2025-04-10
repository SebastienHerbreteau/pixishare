<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250409152418 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE album DROP FOREIGN KEY FK_39986E43922726E9');
        $this->addSql('ALTER TABLE album ADD CONSTRAINT FK_39986E43922726E9 FOREIGN KEY (cover_id) REFERENCES photo (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE album DROP FOREIGN KEY FK_39986E43922726E9');
        $this->addSql('ALTER TABLE album ADD CONSTRAINT FK_39986E43922726E9 FOREIGN KEY (cover_id) REFERENCES photo (id) ON UPDATE NO ACTION ON DELETE CASCADE');
    }
}
