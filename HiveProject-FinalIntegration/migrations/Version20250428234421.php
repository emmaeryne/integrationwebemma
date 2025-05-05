<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250428234421 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE abonnement (id INT AUTO_INCREMENT NOT NULL, service_id INT NOT NULL, type_abonnement_id INT NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, est_actif TINYINT(1) NOT NULL, prix_total NUMERIC(10, 2) NOT NULL, est_gratuit TINYINT(1) NOT NULL, statut VARCHAR(255) NOT NULL, nombre_seances_restantes INT NOT NULL, auto_renouvellement TINYINT(1) NOT NULL, duree_mois INT DEFAULT NULL, mode_paiement VARCHAR(50) DEFAULT NULL, INDEX IDX_351268BBED5CA9E6 (service_id), INDEX IDX_351268BB813AF326 (type_abonnement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE adresse (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, company VARCHAR(255) DEFAULT NULL, adress VARCHAR(255) NOT NULL, postal VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, INDEX IDX_C35F0816A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE avis (id INT AUTO_INCREMENT NOT NULL, produit_id INT DEFAULT NULL, auteur VARCHAR(100) NOT NULL, commentaire LONGTEXT NOT NULL, note INT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_8F91ABF0F347EFB (produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE badge (id INT AUTO_INCREMENT NOT NULL, service_id INT NOT NULL, date_attribution DATETIME NOT NULL, INDEX IDX_FEF0481DED5CA9E6 (service_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE carrier (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, price DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE categorie_produit (id INT AUTO_INCREMENT NOT NULL, nomcategorie VARCHAR(255) NOT NULL, image LONGBLOB DEFAULT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE cours (id_cours INT AUTO_INCREMENT NOT NULL, id_user INT DEFAULT NULL, nom_cours VARCHAR(255) NOT NULL, etat_cours VARCHAR(50) NOT NULL, duree INT NOT NULL, INDEX IDX_FDCA8C9C6B3CA4B (id_user), PRIMARY KEY(id_cours)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE cours_participant (id INT AUTO_INCREMENT NOT NULL, id_participant INT DEFAULT NULL, id_cours INT DEFAULT NULL, INDEX IDX_95280B8FCF8DA6E6 (id_participant), INDEX IDX_95280B8F134FCDAC (id_cours), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE equipe (id_equipe INT AUTO_INCREMENT NOT NULL, nom_equipe VARCHAR(255) NOT NULL, type_equipe VARCHAR(255) NOT NULL, PRIMARY KEY(id_equipe)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE favoris (id INT AUTO_INCREMENT NOT NULL, service_id INT NOT NULL, INDEX IDX_8933C432ED5CA9E6 (service_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE joueur (id_joueur INT AUTO_INCREMENT NOT NULL, id_equipe INT DEFAULT NULL, nom_joueur VARCHAR(255) NOT NULL, cin INT NOT NULL, url_photo VARCHAR(255) NOT NULL, id_user INT NOT NULL, INDEX IDX_FD71A9C527E0FF8 (id_equipe), PRIMARY KEY(id_joueur)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE `match` (id_match INT AUTO_INCREMENT NOT NULL, id_tournoi INT DEFAULT NULL, id_equipe1 INT NOT NULL, id_equipe2 INT NOT NULL, id_terrain INT DEFAULT NULL, date_match DATE NOT NULL, score_equipe1 INT NOT NULL, score_equipe2 INT NOT NULL, statut_match VARCHAR(255) NOT NULL, INDEX IDX_7A5BC505C63270AF (id_tournoi), INDEX IDX_7A5BC50530B8EB96 (id_equipe1), INDEX IDX_7A5BC505A9B1BA2C (id_equipe2), INDEX IDX_7A5BC50516EBFAC1 (id_terrain), PRIMARY KEY(id_match)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', carrier_name VARCHAR(255) NOT NULL, carrier_price DOUBLE PRECISION NOT NULL, delivery VARCHAR(255) NOT NULL, is_paid TINYINT(1) NOT NULL, reference VARCHAR(255) NOT NULL, stripe_session_id VARCHAR(255) NOT NULL, INDEX IDX_F5299398A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE order_detail (id INT AUTO_INCREMENT NOT NULL, my_order_id INT NOT NULL, product VARCHAR(255) NOT NULL, quantity INT NOT NULL, price DOUBLE PRECISION NOT NULL, total DOUBLE PRECISION NOT NULL, INDEX IDX_ED896F46BFCDF877 (my_order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE participant (id INT AUTO_INCREMENT NOT NULL, id_user INT DEFAULT NULL, nom VARCHAR(50) NOT NULL, prenom VARCHAR(50) NOT NULL, age INT NOT NULL, adresse VARCHAR(255) NOT NULL, num_telephone VARCHAR(15) NOT NULL, INDEX IDX_D79F6B116B3CA4B (id_user), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE planning (id_planning INT AUTO_INCREMENT NOT NULL, cours INT DEFAULT NULL, id_user INT DEFAULT NULL, type_activite VARCHAR(255) NOT NULL, date_planning DATE NOT NULL, status VARCHAR(50) NOT NULL, INDEX IDX_D499BFF6FDCA8C9C (cours), INDEX IDX_D499BFF66B3CA4B (id_user), PRIMARY KEY(id_planning)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, subtitle VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, price DOUBLE PRECISION NOT NULL, INDEX IDX_D34A04AD12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE produit (Id INT AUTO_INCREMENT NOT NULL, nom_produit VARCHAR(50) NOT NULL, prix DOUBLE PRECISION NOT NULL, stock_dispo INT NOT NULL, date DATETIME NOT NULL, fournisseur VARCHAR(255) NOT NULL, Categorie INT DEFAULT NULL, INDEX IDX_29A5EC27CB8C5497 (Categorie), PRIMARY KEY(Id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE profile (id INT NOT NULL, user_id INT DEFAULT NULL, first_name VARCHAR(50) NOT NULL, last_name VARCHAR(50) NOT NULL, date_of_birth VARCHAR(30) NOT NULL, profile_picture VARCHAR(255) NOT NULL, bio VARCHAR(150) NOT NULL, location VARCHAR(100) NOT NULL, phone_number VARCHAR(20) NOT NULL, website VARCHAR(255) NOT NULL, social_media_links VARCHAR(255) NOT NULL, INDEX IDX_8157AA0FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, type_abonnement_id INT NOT NULL, date_reservation DATETIME NOT NULL, date_debut DATETIME DEFAULT NULL, date_fin DATETIME DEFAULT NULL, statut VARCHAR(50) NOT NULL, INDEX IDX_42C84955813AF326 (type_abonnement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE service (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, description LONGTEXT DEFAULT NULL, prix NUMERIC(10, 2) NOT NULL, est_actif TINYINT(1) NOT NULL, capacite_max INT NOT NULL, categorie VARCHAR(50) NOT NULL, duree_minutes INT NOT NULL, niveau INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, note NUMERIC(3, 2) DEFAULT NULL, nombre_reservations INT NOT NULL, image VARCHAR(255) DEFAULT NULL, salle VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE terrain (id_terrain INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id_terrain)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE tournoi (id_tournoi INT AUTO_INCREMENT NOT NULL, nom_tournoi VARCHAR(255) NOT NULL, type_tournoi VARCHAR(255) NOT NULL, date_tournoi DATE NOT NULL, description_tournoi LONGTEXT NOT NULL, PRIMARY KEY(id_tournoi)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE type_abonnement (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prix VARCHAR(10) NOT NULL, duree_en_mois INT NOT NULL, is_premium TINYINT(1) NOT NULL, description LONGTEXT DEFAULT NULL, reduction DOUBLE PRECISION DEFAULT NULL, prix_reduit VARCHAR(10) DEFAULT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(50) DEFAULT NULL, email VARCHAR(100) DEFAULT NULL, password_hash VARCHAR(255) DEFAULT NULL, is_active TINYINT(1) DEFAULT NULL, role VARCHAR(255) DEFAULT NULL, service_name VARCHAR(255) DEFAULT NULL, service_type VARCHAR(100) DEFAULT NULL, official_id VARCHAR(50) DEFAULT NULL, documents LONGTEXT DEFAULT NULL, specialty VARCHAR(100) DEFAULT NULL, experience_years INT DEFAULT NULL, certifications VARCHAR(255) DEFAULT NULL, security_question_id INT DEFAULT NULL, security_answer VARCHAR(255) DEFAULT NULL, tel VARCHAR(20) DEFAULT NULL, image VARCHAR(150) DEFAULT NULL, localisation VARCHAR(100) DEFAULT NULL, nom VARCHAR(50) DEFAULT NULL, prenom VARCHAR(50) DEFAULT NULL, image_url VARCHAR(255) DEFAULT NULL, verification_code VARCHAR(6) DEFAULT NULL, is_verified TINYINT(1) NOT NULL, reset_token VARCHAR(100) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', facebook_id VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', available_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', delivered_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE abonnement ADD CONSTRAINT FK_351268BBED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE abonnement ADD CONSTRAINT FK_351268BB813AF326 FOREIGN KEY (type_abonnement_id) REFERENCES type_abonnement (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE adresse ADD CONSTRAINT FK_C35F0816A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF0F347EFB FOREIGN KEY (produit_id) REFERENCES produit (Id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE badge ADD CONSTRAINT FK_FEF0481DED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cours ADD CONSTRAINT FK_FDCA8C9C6B3CA4B FOREIGN KEY (id_user) REFERENCES users (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cours_participant ADD CONSTRAINT FK_95280B8FCF8DA6E6 FOREIGN KEY (id_participant) REFERENCES participant (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cours_participant ADD CONSTRAINT FK_95280B8F134FCDAC FOREIGN KEY (id_cours) REFERENCES cours (id_cours) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE favoris ADD CONSTRAINT FK_8933C432ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE joueur ADD CONSTRAINT FK_FD71A9C527E0FF8 FOREIGN KEY (id_equipe) REFERENCES equipe (id_equipe)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `match` ADD CONSTRAINT FK_7A5BC505C63270AF FOREIGN KEY (id_tournoi) REFERENCES tournoi (id_tournoi)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `match` ADD CONSTRAINT FK_7A5BC50530B8EB96 FOREIGN KEY (id_equipe1) REFERENCES equipe (id_equipe)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `match` ADD CONSTRAINT FK_7A5BC505A9B1BA2C FOREIGN KEY (id_equipe2) REFERENCES equipe (id_equipe)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `match` ADD CONSTRAINT FK_7A5BC50516EBFAC1 FOREIGN KEY (id_terrain) REFERENCES terrain (id_terrain)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `order` ADD CONSTRAINT FK_F5299398A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_detail ADD CONSTRAINT FK_ED896F46BFCDF877 FOREIGN KEY (my_order_id) REFERENCES `order` (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participant ADD CONSTRAINT FK_D79F6B116B3CA4B FOREIGN KEY (id_user) REFERENCES users (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE planning ADD CONSTRAINT FK_D499BFF6FDCA8C9C FOREIGN KEY (cours) REFERENCES cours (id_cours) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE planning ADD CONSTRAINT FK_D499BFF66B3CA4B FOREIGN KEY (id_user) REFERENCES users (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product ADD CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27CB8C5497 FOREIGN KEY (Categorie) REFERENCES categorie_produit (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE profile ADD CONSTRAINT FK_8157AA0FA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation ADD CONSTRAINT FK_42C84955813AF326 FOREIGN KEY (type_abonnement_id) REFERENCES type_abonnement (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE abonnement DROP FOREIGN KEY FK_351268BBED5CA9E6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE abonnement DROP FOREIGN KEY FK_351268BB813AF326
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE adresse DROP FOREIGN KEY FK_C35F0816A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF0F347EFB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE badge DROP FOREIGN KEY FK_FEF0481DED5CA9E6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cours DROP FOREIGN KEY FK_FDCA8C9C6B3CA4B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cours_participant DROP FOREIGN KEY FK_95280B8FCF8DA6E6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cours_participant DROP FOREIGN KEY FK_95280B8F134FCDAC
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE favoris DROP FOREIGN KEY FK_8933C432ED5CA9E6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE joueur DROP FOREIGN KEY FK_FD71A9C527E0FF8
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `match` DROP FOREIGN KEY FK_7A5BC505C63270AF
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `match` DROP FOREIGN KEY FK_7A5BC50530B8EB96
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `match` DROP FOREIGN KEY FK_7A5BC505A9B1BA2C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `match` DROP FOREIGN KEY FK_7A5BC50516EBFAC1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_detail DROP FOREIGN KEY FK_ED896F46BFCDF877
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participant DROP FOREIGN KEY FK_D79F6B116B3CA4B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE planning DROP FOREIGN KEY FK_D499BFF6FDCA8C9C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE planning DROP FOREIGN KEY FK_D499BFF66B3CA4B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD12469DE2
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27CB8C5497
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE profile DROP FOREIGN KEY FK_8157AA0FA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955813AF326
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE abonnement
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE adresse
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE avis
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE badge
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE carrier
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE categorie_produit
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE category
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE cours
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE cours_participant
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE equipe
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE favoris
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE joueur
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE `match`
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE `order`
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE order_detail
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE participant
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE planning
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE product
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE produit
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE profile
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE reservation
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE service
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE terrain
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE tournoi
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE type_abonnement
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE users
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}
