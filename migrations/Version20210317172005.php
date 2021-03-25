<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210317172005 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reclamation ADD waa VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD rec_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6491669B19B FOREIGN KEY (rec_id) REFERENCES reclamation (id)');
        $this->addSql('CREATE INDEX IDX_8D93D6491669B19B ON user (rec_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reclamation DROP waa');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6491669B19B');
        $this->addSql('DROP INDEX IDX_8D93D6491669B19B ON user');
        $this->addSql('ALTER TABLE user DROP rec_id');
    }
}
