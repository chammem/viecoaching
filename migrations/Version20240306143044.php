<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240306143044 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ressources_utilisateur DROP FOREIGN KEY FK_85B94C4F3C361826');
        $this->addSql('ALTER TABLE ressources_utilisateur DROP FOREIGN KEY FK_85B94C4FFB88E14F');
        $this->addSql('DROP TABLE ressources_utilisateur');
        $this->addSql('ALTER TABLE ressources ADD utilisateur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ressources ADD CONSTRAINT FK_6A2CD5C7FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_6A2CD5C7FB88E14F ON ressources (utilisateur_id)');
        $this->addSql('ALTER TABLE utilisateur CHANGE image image VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ressources_utilisateur (ressources_id INT NOT NULL, utilisateur_id INT NOT NULL, INDEX IDX_85B94C4FFB88E14F (utilisateur_id), INDEX IDX_85B94C4F3C361826 (ressources_id), PRIMARY KEY(ressources_id, utilisateur_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE ressources_utilisateur ADD CONSTRAINT FK_85B94C4F3C361826 FOREIGN KEY (ressources_id) REFERENCES ressources (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ressources_utilisateur ADD CONSTRAINT FK_85B94C4FFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE ressources DROP FOREIGN KEY FK_6A2CD5C7FB88E14F');
        $this->addSql('DROP INDEX IDX_6A2CD5C7FB88E14F ON ressources');
        $this->addSql('ALTER TABLE ressources DROP utilisateur_id');
        $this->addSql('ALTER TABLE utilisateur CHANGE image image VARCHAR(255) DEFAULT \'NULL\'');
    }
}
