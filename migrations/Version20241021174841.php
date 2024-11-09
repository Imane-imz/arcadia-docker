<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241021174841 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE utilisateur DROP CONSTRAINT fk_1d1c63b3d60322ac');
        $this->addSql('DROP SEQUENCE role_id_seq CASCADE');
        $this->addSql('DROP TABLE role');
        $this->addSql('ALTER TABLE animal ADD image VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE animal ADD habitat_reel VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE animal ADD nourriture_globale TEXT NOT NULL');
        $this->addSql('ALTER TABLE habitat DROP commentaire_habitat');
        $this->addSql('DROP INDEX idx_1d1c63b3d60322ac');
        $this->addSql('ALTER TABLE utilisateur DROP role_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE role_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE role (id INT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE animal DROP image');
        $this->addSql('ALTER TABLE animal DROP habitat_reel');
        $this->addSql('ALTER TABLE animal DROP nourriture_globale');
        $this->addSql('ALTER TABLE habitat ADD commentaire_habitat TEXT NOT NULL');
        $this->addSql('ALTER TABLE utilisateur ADD role_id INT NOT NULL');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT fk_1d1c63b3d60322ac FOREIGN KEY (role_id) REFERENCES role (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_1d1c63b3d60322ac ON utilisateur (role_id)');
    }
}
