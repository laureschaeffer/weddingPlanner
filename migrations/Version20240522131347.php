<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240522131347 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE batch (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(100) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE booking (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, reservation_id INT NOT NULL, quantite INT NOT NULL, INDEX IDX_E00CEDDE4584665A (product_id), INDEX IDX_E00CEDDEB83297E7 (reservation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE budget (id INT AUTO_INCREMENT NOT NULL, min_price INT NOT NULL, max_price INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE creation (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, title VARCHAR(50) NOT NULL, description LONGTEXT NOT NULL, picture JSON DEFAULT NULL, INDEX IDX_57EE857412469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE destination (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE job (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prestation (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(50) NOT NULL, subtitle VARCHAR(50) NOT NULL, description LONGTEXT NOT NULL, url_picture VARCHAR(255) DEFAULT NULL, alt_picture VARCHAR(50) DEFAULT NULL, price DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, batch_id INT NOT NULL, name VARCHAR(100) NOT NULL, description LONGTEXT NOT NULL, url_picture VARCHAR(255) DEFAULT NULL, alt_picture VARCHAR(50) DEFAULT NULL, INDEX IDX_D34A04ADF39EBE7A (batch_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project (id INT AUTO_INCREMENT NOT NULL, destination_id INT DEFAULT NULL, budget_id INT DEFAULT NULL, firstname VARCHAR(50) NOT NULL, surname VARCHAR(100) NOT NULL, email VARCHAR(50) NOT NULL, telephone VARCHAR(20) NOT NULL, date_event DATETIME NOT NULL, nb_guest INT NOT NULL, description LONGTEXT NOT NULL, date_receipt DATETIME DEFAULT NULL, is_contacted TINYINT(1) DEFAULT NULL, INDEX IDX_2FB3D0EE816C6140 (destination_id), INDEX IDX_2FB3D0EE36ABA6B8 (budget_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project_prestation (project_id INT NOT NULL, prestation_id INT NOT NULL, INDEX IDX_496EF49F166D1F9C (project_id), INDEX IDX_496EF49F9E45C554 (prestation_id), PRIMARY KEY(project_id, prestation_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, reference_order INT DEFAULT NULL, date_order DATETIME DEFAULT NULL, total_price DOUBLE PRECISION NOT NULL, date_picking DATE NOT NULL, is_prepared TINYINT(1) DEFAULT NULL, is_picked TINYINT(1) DEFAULT NULL, INDEX IDX_42C84955A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE testimony (id INT AUTO_INCREMENT NOT NULL, couple_name VARCHAR(200) NOT NULL, description LONGTEXT NOT NULL, is_published TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE worker (id INT AUTO_INCREMENT NOT NULL, job_id INT NOT NULL, name VARCHAR(50) NOT NULL, description LONGTEXT NOT NULL, picture VARCHAR(255) DEFAULT NULL, alt_picture VARCHAR(50) DEFAULT NULL, INDEX IDX_9FB2BF62BE04EA9 (job_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDEB83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id)');
        $this->addSql('ALTER TABLE creation ADD CONSTRAINT FK_57EE857412469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADF39EBE7A FOREIGN KEY (batch_id) REFERENCES batch (id)');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE816C6140 FOREIGN KEY (destination_id) REFERENCES destination (id)');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE36ABA6B8 FOREIGN KEY (budget_id) REFERENCES budget (id)');
        $this->addSql('ALTER TABLE project_prestation ADD CONSTRAINT FK_496EF49F166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project_prestation ADD CONSTRAINT FK_496EF49F9E45C554 FOREIGN KEY (prestation_id) REFERENCES prestation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE worker ADD CONSTRAINT FK_9FB2BF62BE04EA9 FOREIGN KEY (job_id) REFERENCES job (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDE4584665A');
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDEB83297E7');
        $this->addSql('ALTER TABLE creation DROP FOREIGN KEY FK_57EE857412469DE2');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADF39EBE7A');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EE816C6140');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EE36ABA6B8');
        $this->addSql('ALTER TABLE project_prestation DROP FOREIGN KEY FK_496EF49F166D1F9C');
        $this->addSql('ALTER TABLE project_prestation DROP FOREIGN KEY FK_496EF49F9E45C554');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955A76ED395');
        $this->addSql('ALTER TABLE worker DROP FOREIGN KEY FK_9FB2BF62BE04EA9');
        $this->addSql('DROP TABLE batch');
        $this->addSql('DROP TABLE booking');
        $this->addSql('DROP TABLE budget');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE creation');
        $this->addSql('DROP TABLE destination');
        $this->addSql('DROP TABLE job');
        $this->addSql('DROP TABLE prestation');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE project_prestation');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE testimony');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE worker');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
