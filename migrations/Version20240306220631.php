<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240306220631 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire ADD auteur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC60BB6FE6 FOREIGN KEY (auteur_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_67F068BC60BB6FE6 ON commentaire (auteur_id)');
        $this->addSql('ALTER TABLE rubrique ADD auteur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE rubrique ADD CONSTRAINT FK_8FA4097C60BB6FE6 FOREIGN KEY (auteur_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_8FA4097C60BB6FE6 ON rubrique (auteur_id)');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC60BB6FE6');
        $this->addSql('DROP INDEX IDX_67F068BC60BB6FE6 ON commentaire');
        $this->addSql('ALTER TABLE commentaire DROP auteur_id');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE rubrique DROP FOREIGN KEY FK_8FA4097C60BB6FE6');
        $this->addSql('DROP INDEX IDX_8FA4097C60BB6FE6 ON rubrique');
        $this->addSql('ALTER TABLE rubrique DROP auteur_id');
    }
}
