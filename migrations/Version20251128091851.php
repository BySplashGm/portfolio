<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251128091851 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE message ADD subject VARCHAR(100)');

        // Mise à jour des valeurs existantes
        $this->addSql("UPDATE message SET subject = CONCAT('Message de ', name)");

        $this->addSql('ALTER TABLE message MODIFY subject VARCHAR(100) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE message DROP subject');
    }
}
