<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211012073244 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE inscription (id INT AUTO_INCREMENT NOT NULL, date_inscription DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE lieu ADD villes_no_ville_id INT NOT NULL');
        $this->addSql('ALTER TABLE lieu ADD CONSTRAINT FK_2F577D5927E30153 FOREIGN KEY (villes_no_ville_id) REFERENCES ville (id)');
        $this->addSql('CREATE INDEX IDX_2F577D5927E30153 ON lieu (villes_no_ville_id)');
        $this->addSql('ALTER TABLE participant ADD sites_no_site_id INT NOT NULL, ADD inscriptions_participants_id INT NOT NULL');
        $this->addSql('ALTER TABLE participant ADD CONSTRAINT FK_D79F6B11DDF76323 FOREIGN KEY (sites_no_site_id) REFERENCES site (id)');
        $this->addSql('ALTER TABLE participant ADD CONSTRAINT FK_D79F6B118DF687AF FOREIGN KEY (inscriptions_participants_id) REFERENCES inscription (id)');
        $this->addSql('CREATE INDEX IDX_D79F6B11DDF76323 ON participant (sites_no_site_id)');
        $this->addSql('CREATE INDEX IDX_D79F6B118DF687AF ON participant (inscriptions_participants_id)');
        $this->addSql('ALTER TABLE site ADD sortie VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE sortie ADD organisateur_id INT NOT NULL, ADD lieux_no_lieu_id INT NOT NULL, ADD etats_no_etat_id INT NOT NULL, ADD inscriptions_sorties_id INT NOT NULL');
        $this->addSql('ALTER TABLE sortie ADD CONSTRAINT FK_3C3FD3F2D936B2FA FOREIGN KEY (organisateur_id) REFERENCES participant (id)');
        $this->addSql('ALTER TABLE sortie ADD CONSTRAINT FK_3C3FD3F2171DE0C3 FOREIGN KEY (lieux_no_lieu_id) REFERENCES lieu (id)');
        $this->addSql('ALTER TABLE sortie ADD CONSTRAINT FK_3C3FD3F2A04E64FD FOREIGN KEY (etats_no_etat_id) REFERENCES etat (id)');
        $this->addSql('ALTER TABLE sortie ADD CONSTRAINT FK_3C3FD3F29CDE3463 FOREIGN KEY (inscriptions_sorties_id) REFERENCES inscription (id)');
        $this->addSql('CREATE INDEX IDX_3C3FD3F2D936B2FA ON sortie (organisateur_id)');
        $this->addSql('CREATE INDEX IDX_3C3FD3F2171DE0C3 ON sortie (lieux_no_lieu_id)');
        $this->addSql('CREATE INDEX IDX_3C3FD3F2A04E64FD ON sortie (etats_no_etat_id)');
        $this->addSql('CREATE INDEX IDX_3C3FD3F29CDE3463 ON sortie (inscriptions_sorties_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE participant DROP FOREIGN KEY FK_D79F6B118DF687AF');
        $this->addSql('ALTER TABLE sortie DROP FOREIGN KEY FK_3C3FD3F29CDE3463');
        $this->addSql('DROP TABLE inscription');
        $this->addSql('ALTER TABLE lieu DROP FOREIGN KEY FK_2F577D5927E30153');
        $this->addSql('DROP INDEX IDX_2F577D5927E30153 ON lieu');
        $this->addSql('ALTER TABLE lieu DROP villes_no_ville_id');
        $this->addSql('ALTER TABLE participant DROP FOREIGN KEY FK_D79F6B11DDF76323');
        $this->addSql('DROP INDEX IDX_D79F6B11DDF76323 ON participant');
        $this->addSql('DROP INDEX IDX_D79F6B118DF687AF ON participant');
        $this->addSql('ALTER TABLE participant DROP sites_no_site_id, DROP inscriptions_participants_id');
        $this->addSql('ALTER TABLE site DROP sortie');
        $this->addSql('ALTER TABLE sortie DROP FOREIGN KEY FK_3C3FD3F2D936B2FA');
        $this->addSql('ALTER TABLE sortie DROP FOREIGN KEY FK_3C3FD3F2171DE0C3');
        $this->addSql('ALTER TABLE sortie DROP FOREIGN KEY FK_3C3FD3F2A04E64FD');
        $this->addSql('DROP INDEX IDX_3C3FD3F2D936B2FA ON sortie');
        $this->addSql('DROP INDEX IDX_3C3FD3F2171DE0C3 ON sortie');
        $this->addSql('DROP INDEX IDX_3C3FD3F2A04E64FD ON sortie');
        $this->addSql('DROP INDEX IDX_3C3FD3F29CDE3463 ON sortie');
        $this->addSql('ALTER TABLE sortie DROP organisateur_id, DROP lieux_no_lieu_id, DROP etats_no_etat_id, DROP inscriptions_sorties_id');
    }
}
