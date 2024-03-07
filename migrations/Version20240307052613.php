<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240307052613 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE groupe_utilisateur (groupe_id INT NOT NULL, utilisateur_id INT NOT NULL, INDEX IDX_92C1107D7A45358C (groupe_id), INDEX IDX_92C1107DFB88E14F (utilisateur_id), PRIMARY KEY(groupe_id, utilisateur_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, sujet VARCHAR(50) NOT NULL, date DATETIME NOT NULL, datefin DATETIME NOT NULL, description VARCHAR(70) NOT NULL, background_color VARCHAR(7) NOT NULL, border_color VARCHAR(7) NOT NULL, text_color VARCHAR(7) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE seance (id INT AUTO_INCREMENT NOT NULL, type_seance_id INT DEFAULT NULL, titre VARCHAR(50) NOT NULL, duree TIME NOT NULL, lien LONGTEXT NOT NULL, mot_de_passe VARCHAR(50) NOT NULL, INDEX IDX_DF7DFD0E57BF3B84 (type_seance_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_seance (id INT AUTO_INCREMENT NOT NULL, nom_type VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE groupe_utilisateur ADD CONSTRAINT FK_92C1107D7A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe_utilisateur ADD CONSTRAINT FK_92C1107DFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE seance ADD CONSTRAINT FK_DF7DFD0E57BF3B84 FOREIGN KEY (type_seance_id) REFERENCES type_seance (id)');
        $this->addSql('ALTER TABLE ressources ADD utilisateur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ressources ADD CONSTRAINT FK_6A2CD5C7FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_6A2CD5C7FB88E14F ON ressources (utilisateur_id)');
        $this->addSql('ALTER TABLE utilisateur CHANGE image image VARCHAR(255) DEFAULT NULL, CHANGE reset_token reset_token VARCHAR(255) DEFAULT NULL, CHANGE reset_token_expires_at reset_token_expires_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE groupe_utilisateur DROP FOREIGN KEY FK_92C1107D7A45358C');
        $this->addSql('ALTER TABLE groupe_utilisateur DROP FOREIGN KEY FK_92C1107DFB88E14F');
        $this->addSql('ALTER TABLE seance DROP FOREIGN KEY FK_DF7DFD0E57BF3B84');
        $this->addSql('DROP TABLE groupe_utilisateur');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE seance');
        $this->addSql('DROP TABLE type_seance');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE ressources DROP FOREIGN KEY FK_6A2CD5C7FB88E14F');
        $this->addSql('DROP INDEX IDX_6A2CD5C7FB88E14F ON ressources');
        $this->addSql('ALTER TABLE ressources DROP utilisateur_id');
        $this->addSql('ALTER TABLE utilisateur CHANGE image image VARCHAR(255) DEFAULT \'NULL\', CHANGE reset_token reset_token VARCHAR(255) DEFAULT \'NULL\', CHANGE reset_token_expires_at reset_token_expires_at DATETIME DEFAULT \'NULL\'');
    }
}
