<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201017233048 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C7F630046');
        $this->addSql('DROP TABLE test_class');
        $this->addSql('DROP INDEX IDX_9474526C7F630046 ON comment');
        $this->addSql('ALTER TABLE comment ADD is_updated TINYINT(1) DEFAULT \'0\', ADD updated_at DATETIME DEFAULT NULL, DROP test_class_id, CHANGE is_archived is_archived TINYINT(1) DEFAULT \'0\'');
        $this->addSql('ALTER TABLE user_published CHANGE published_at published_at DATETIME NOT NULL, CHANGE is_archived is_archived TINYINT(1) DEFAULT \'0\', CHANGE is_updated is_updated TINYINT(1) DEFAULT \'0\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE test_class (id INT AUTO_INCREMENT NOT NULL, last_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, first_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE comment ADD test_class_id INT DEFAULT NULL, DROP is_updated, DROP updated_at, CHANGE is_archived is_archived TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C7F630046 FOREIGN KEY (test_class_id) REFERENCES test_class (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_9474526C7F630046 ON comment (test_class_id)');
        $this->addSql('ALTER TABLE user_published CHANGE published_at published_at DATE NOT NULL, CHANGE is_archived is_archived TINYINT(1) NOT NULL, CHANGE is_updated is_updated TINYINT(1) DEFAULT NULL');
    }
}
