<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240306214954 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, ressource_id INT DEFAULT NULL, nom_categorie VARCHAR(50) NOT NULL, description VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, INDEX IDX_497DD634FC6CD52A (ressource_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupe (id INT AUTO_INCREMENT NOT NULL, typegroupe_id INT DEFAULT NULL, nom VARCHAR(20) NOT NULL, description VARCHAR(255) NOT NULL, datecreation DATETIME NOT NULL, image VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_4B98C21997A5710 (typegroupe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ressources (id INT AUTO_INCREMENT NOT NULL, titre_r VARCHAR(255) NOT NULL, type_r VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, nom_role VARCHAR(30) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE typegroupe (id INT AUTO_INCREMENT NOT NULL, nomtype VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, role_id INT DEFAULT NULL, nom VARCHAR(30) NOT NULL, prenom VARCHAR(30) NOT NULL, email VARCHAR(255) NOT NULL, tel BIGINT NOT NULL, mdp VARCHAR(255) NOT NULL, genre VARCHAR(30) NOT NULL, image VARCHAR(255) DEFAULT NULL, ville VARCHAR(30) NOT NULL, active VARCHAR(30) NOT NULL, INDEX IDX_1D1C63B3D60322AC (role_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE categorie ADD CONSTRAINT FK_497DD634FC6CD52A FOREIGN KEY (ressource_id) REFERENCES ressources (id)');
        $this->addSql('ALTER TABLE groupe ADD CONSTRAINT FK_4B98C21997A5710 FOREIGN KEY (typegroupe_id) REFERENCES typegroupe (id)');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B3D60322AC FOREIGN KEY (role_id) REFERENCES role (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categorie DROP FOREIGN KEY FK_497DD634FC6CD52A');
        $this->addSql('ALTER TABLE groupe DROP FOREIGN KEY FK_4B98C21997A5710');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B3D60322AC');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE groupe');
        $this->addSql('DROP TABLE ressources');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE typegroupe');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
