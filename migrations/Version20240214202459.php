<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240214202459 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ressources_categorie (ressources_id INT NOT NULL, categorie_id INT NOT NULL, INDEX IDX_410D02353C361826 (ressources_id), INDEX IDX_410D0235BCF5E72D (categorie_id), PRIMARY KEY(ressources_id, categorie_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE ressources_categorie ADD CONSTRAINT FK_410D02353C361826 FOREIGN KEY (ressources_id) REFERENCES ressources (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ressources_categorie ADD CONSTRAINT FK_410D0235BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ressources_categorie DROP FOREIGN KEY FK_410D02353C361826');
        $this->addSql('ALTER TABLE ressources_categorie DROP FOREIGN KEY FK_410D0235BCF5E72D');
        $this->addSql('DROP TABLE ressources_categorie');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT \'NULL\'');
    }
}
