<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260320205531 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE skill_experience (skill_id INT NOT NULL, experience_id INT NOT NULL, INDEX IDX_10191D715585C142 (skill_id), INDEX IDX_10191D7146E90E27 (experience_id), PRIMARY KEY(skill_id, experience_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE skill_experience ADD CONSTRAINT FK_10191D715585C142 FOREIGN KEY (skill_id) REFERENCES skill (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE skill_experience ADD CONSTRAINT FK_10191D7146E90E27 FOREIGN KEY (experience_id) REFERENCES experience (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE experience ADD short_description LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE message ADD is_read TINYINT(1) NOT NULL, ADD subject VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE project ADD link VARCHAR(255) DEFAULT NULL, ADD repolink VARCHAR(255) DEFAULT NULL, ADD short_description LONGTEXT NOT NULL, DROP title, CHANGE description description LONGTEXT NOT NULL, CHANGE created_at created_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE skill ADD description LONGTEXT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE skill_experience DROP FOREIGN KEY FK_10191D715585C142');
        $this->addSql('ALTER TABLE skill_experience DROP FOREIGN KEY FK_10191D7146E90E27');
        $this->addSql('DROP TABLE skill_experience');
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE message DROP is_read, DROP subject');
        $this->addSql('ALTER TABLE project ADD title VARCHAR(255) NOT NULL, DROP link, DROP repolink, DROP short_description, CHANGE description description VARCHAR(1500) NOT NULL, CHANGE created_at created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE skill DROP description');
        $this->addSql('ALTER TABLE experience DROP short_description');
    }
}
