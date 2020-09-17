<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200915174613 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE objects_photos (objects_id INT NOT NULL, photos_id INT NOT NULL, INDEX IDX_5FD2F0E34BEE6933 (objects_id), INDEX IDX_5FD2F0E3301EC62 (photos_id), PRIMARY KEY(objects_id, photos_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE objects_params (objects_id INT NOT NULL, params_id INT NOT NULL, INDEX IDX_D86A1EC94BEE6933 (objects_id), INDEX IDX_D86A1EC9339CCA0F (params_id), PRIMARY KEY(objects_id, params_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE objects_facilities (objects_id INT NOT NULL, facilities_id INT NOT NULL, INDEX IDX_113C83DA4BEE6933 (objects_id), INDEX IDX_113C83DA5263402 (facilities_id), PRIMARY KEY(objects_id, facilities_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE objects_photos ADD CONSTRAINT FK_5FD2F0E34BEE6933 FOREIGN KEY (objects_id) REFERENCES objects (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE objects_photos ADD CONSTRAINT FK_5FD2F0E3301EC62 FOREIGN KEY (photos_id) REFERENCES photos (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE objects_params ADD CONSTRAINT FK_D86A1EC94BEE6933 FOREIGN KEY (objects_id) REFERENCES objects (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE objects_params ADD CONSTRAINT FK_D86A1EC9339CCA0F FOREIGN KEY (params_id) REFERENCES params (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE objects_facilities ADD CONSTRAINT FK_113C83DA4BEE6933 FOREIGN KEY (objects_id) REFERENCES objects (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE objects_facilities ADD CONSTRAINT FK_113C83DA5263402 FOREIGN KEY (facilities_id) REFERENCES facilities (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE objects_photos');
        $this->addSql('DROP TABLE objects_params');
        $this->addSql('DROP TABLE objects_facilities');
    }
}
