<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200916081919 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE objects_photos');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE objects_photos (objects_id INT NOT NULL, photos_id INT NOT NULL, INDEX IDX_5FD2F0E34BEE6933 (objects_id), INDEX IDX_5FD2F0E3301EC62 (photos_id), PRIMARY KEY(objects_id, photos_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE objects_photos ADD CONSTRAINT FK_5FD2F0E3301EC62 FOREIGN KEY (photos_id) REFERENCES photos (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE objects_photos ADD CONSTRAINT FK_5FD2F0E34BEE6933 FOREIGN KEY (objects_id) REFERENCES objects (id) ON DELETE CASCADE');
    }
}
