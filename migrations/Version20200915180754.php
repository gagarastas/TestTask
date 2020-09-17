<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200915180754 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE objects DROP FOREIGN KEY FK_B21ACCF3DF6E65AD');
        $this->addSql('DROP INDEX IDX_B21ACCF3DF6E65AD ON objects');
        $this->addSql('ALTER TABLE objects DROP admin_id_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE objects ADD admin_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE objects ADD CONSTRAINT FK_B21ACCF3DF6E65AD FOREIGN KEY (admin_id_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_B21ACCF3DF6E65AD ON objects (admin_id_id)');
    }
}
