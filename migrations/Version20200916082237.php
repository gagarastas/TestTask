<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200916082237 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE photos ADD object_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE photos ADD CONSTRAINT FK_876E0D9FB67CAB5 FOREIGN KEY (object_id_id) REFERENCES objects (id)');
        $this->addSql('CREATE INDEX IDX_876E0D9FB67CAB5 ON photos (object_id_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE photos DROP FOREIGN KEY FK_876E0D9FB67CAB5');
        $this->addSql('DROP INDEX IDX_876E0D9FB67CAB5 ON photos');
        $this->addSql('ALTER TABLE photos DROP object_id_id');
    }
}
