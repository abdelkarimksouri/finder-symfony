<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201016233940 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_published (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, published_text VARCHAR(255) DEFAULT NULL, media_id INT DEFAULT NULL, published_at DATE NOT NULL, is_archived TINYINT(1) NOT NULL, archived_at DATETIME DEFAULT NULL, is_updated TINYINT(1) DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_68DCEDF3A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_published ADD CONSTRAINT FK_68DCEDF3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment ADD comment_body VARCHAR(255) NOT NULL, ADD comment_at DATETIME NOT NULL, ADD is_archived TINYINT(1) DEFAULT \'0\' NOT NULL, ADD archived_at DATETIME NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user_published');
        $this->addSql('ALTER TABLE comment DROP comment_body, DROP comment_at, DROP is_archived, DROP archived_at');
    }
}
