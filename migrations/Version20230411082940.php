<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230411082940 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE artykul (id INT AUTO_INCREMENT NOT NULL, magazyny_id INT DEFAULT NULL, nazwa VARCHAR(255) NOT NULL, jednostka_miary VARCHAR(255) NOT NULL, INDEX IDX_BEEC19FD27A1A0A1 (magazyny_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE magazyny (id INT AUTO_INCREMENT NOT NULL, nazwa VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE magazyny_user (magazyny_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_8534C93E27A1A0A1 (magazyny_id), INDEX IDX_8534C93EA76ED395 (user_id), PRIMARY KEY(magazyny_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE artykul ADD CONSTRAINT FK_BEEC19FD27A1A0A1 FOREIGN KEY (magazyny_id) REFERENCES magazyny (id)');
        $this->addSql('ALTER TABLE magazyny_user ADD CONSTRAINT FK_8534C93E27A1A0A1 FOREIGN KEY (magazyny_id) REFERENCES magazyny (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE magazyny_user ADD CONSTRAINT FK_8534C93EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE artykul DROP FOREIGN KEY FK_BEEC19FD27A1A0A1');
        $this->addSql('ALTER TABLE magazyny_user DROP FOREIGN KEY FK_8534C93E27A1A0A1');
        $this->addSql('ALTER TABLE magazyny_user DROP FOREIGN KEY FK_8534C93EA76ED395');
        $this->addSql('DROP TABLE artykul');
        $this->addSql('DROP TABLE magazyny');
        $this->addSql('DROP TABLE magazyny_user');
        $this->addSql('DROP TABLE user');
    }
}
