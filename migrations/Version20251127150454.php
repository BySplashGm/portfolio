<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251127150454 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE experience (
            id INT AUTO_INCREMENT NOT NULL,
            label VARCHAR(255) NOT NULL,
            description TEXT NOT NULL,
            company VARCHAR(255) NOT NULL,
            short_description TEXT NOT NULL,
            PRIMARY KEY(id)
        )');

        $this->addSql('CREATE TABLE message (
            id INT AUTO_INCREMENT NOT NULL,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            message TEXT NOT NULL,
            created_at DATETIME NOT NULL,
            PRIMARY KEY(id)
        )');

        $this->addSql('CREATE TABLE project (
            id INT AUTO_INCREMENT NOT NULL,
            name VARCHAR(255) NOT NULL,
            description TEXT NOT NULL,
            created_at DATETIME DEFAULT NULL,
            status VARCHAR(255) NOT NULL,
            image_path VARCHAR(255) DEFAULT NULL,
            link VARCHAR(255) DEFAULT NULL,
            repolink VARCHAR(255) DEFAULT NULL,
            short_description TEXT NOT NULL,
            PRIMARY KEY(id)
        )');

        $this->addSql('CREATE TABLE project_skill (
            project_id INT NOT NULL,
            skill_id INT NOT NULL,
            PRIMARY KEY(project_id, skill_id)
        )');

        $this->addSql('CREATE INDEX IDX_PROJECT_SKILL_PROJECT ON project_skill (project_id)');
        $this->addSql('CREATE INDEX IDX_PROJECT_SKILL_SKILL ON project_skill (skill_id)');

        $this->addSql('CREATE TABLE skill (
            id INT AUTO_INCREMENT NOT NULL,
            type_id INT NOT NULL,
            name VARCHAR(255) NOT NULL,
            description TEXT NOT NULL,
            PRIMARY KEY(id)
        )');

        $this->addSql('CREATE INDEX IDX_SKILL_TYPE ON skill (type_id)');

        $this->addSql('CREATE TABLE skill_experience (
            skill_id INT NOT NULL,
            experience_id INT NOT NULL,
            PRIMARY KEY(skill_id, experience_id)
        )');

        $this->addSql('CREATE INDEX IDX_SKILL_EXPERIENCE_SKILL ON skill_experience (skill_id)');
        $this->addSql('CREATE INDEX IDX_SKILL_EXPERIENCE_EXPERIENCE ON skill_experience (experience_id)');

        $this->addSql('CREATE TABLE skill_type (
            id INT AUTO_INCREMENT NOT NULL,
            label VARCHAR(255) NOT NULL,
            PRIMARY KEY(id)
        )');

        $this->addSql('CREATE TABLE `user` (
            id INT AUTO_INCREMENT NOT NULL,
            email VARCHAR(180) NOT NULL,
            roles JSON NOT NULL,
            password VARCHAR(255) NOT NULL,
            PRIMARY KEY(id),
            UNIQUE KEY UNIQ_IDENTIFIER_EMAIL (email)
        )');

        $this->addSql('CREATE TABLE messenger_messages (
            id BIGINT AUTO_INCREMENT NOT NULL,
            body TEXT NOT NULL,
            headers TEXT NOT NULL,
            queue_name VARCHAR(190) NOT NULL,
            created_at DATETIME NOT NULL,
            available_at DATETIME NOT NULL,
            delivered_at DATETIME DEFAULT NULL,
            PRIMARY KEY(id)
        )');

        $this->addSql('CREATE INDEX IDX_MESSENGER_QUEUE ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_MESSENGER_AVAILABLE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_MESSENGER_DELIVERED ON messenger_messages (delivered_at)');

        // Les triggers et notifications PostgreSQL ne sont pas traduisibles directement, on les supprime

        $this->addSql('ALTER TABLE project_skill ADD CONSTRAINT FK_PROJECT_SKILL_PROJECT FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project_skill ADD CONSTRAINT FK_PROJECT_SKILL_SKILL FOREIGN KEY (skill_id) REFERENCES skill (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE skill ADD CONSTRAINT FK_SKILL_TYPE FOREIGN KEY (type_id) REFERENCES skill_type (id)');
        $this->addSql('ALTER TABLE skill_experience ADD CONSTRAINT FK_SKILL_EXPERIENCE_SKILL FOREIGN KEY (skill_id) REFERENCES skill (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE skill_experience ADD CONSTRAINT FK_SKILL_EXPERIENCE_EXPERIENCE FOREIGN KEY (experience_id) REFERENCES experience (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE project_skill DROP FOREIGN KEY FK_PROJECT_SKILL_PROJECT');
        $this->addSql('ALTER TABLE project_skill DROP FOREIGN KEY FK_PROJECT_SKILL_SKILL');
        $this->addSql('ALTER TABLE skill DROP FOREIGN KEY FK_SKILL_TYPE');
        $this->addSql('ALTER TABLE skill_experience DROP FOREIGN KEY FK_SKILL_EXPERIENCE_SKILL');
        $this->addSql('ALTER TABLE skill_experience DROP FOREIGN KEY FK_SKILL_EXPERIENCE_EXPERIENCE');

        $this->addSql('DROP TABLE experience');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE project_skill');
        $this->addSql('DROP TABLE skill');
        $this->addSql('DROP TABLE skill_experience');
        $this->addSql('DROP TABLE skill_type');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
