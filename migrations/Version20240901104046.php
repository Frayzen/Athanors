<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240901104046 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE action (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE answer_user_session (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, session_id INT DEFAULT NULL, presence TINYINT(1) NOT NULL, INDEX IDX_2AEFB43CA76ED395 (user_id), INDEX IDX_2AEFB43C613FECDF (session_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ask_cancel (id INT AUTO_INCREMENT NOT NULL, rdv_id INT DEFAULT NULL, reason VARCHAR(255) NOT NULL, viewed TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_AB49B94A4CCE3F86 (rdv_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE atelier (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, start DATETIME NOT NULL, end DATETIME NOT NULL, description VARCHAR(255) NOT NULL, content VARCHAR(4096) NOT NULL, picture VARCHAR(255) NOT NULL, max_user INT NOT NULL, price_per_session DOUBLE PRECISION NOT NULL, sessions_mandatory TINYINT(1) NOT NULL, can_join_after_start TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE atelier_user (atelier_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_4D145FAA82E2CF35 (atelier_id), INDEX IDX_4D145FAAA76ED395 (user_id), PRIMARY KEY(atelier_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE atelier_supervisor (atelier_id INT NOT NULL, supervisor_id INT NOT NULL, INDEX IDX_809631F582E2CF35 (atelier_id), INDEX IDX_809631F519E9AC5F (supervisor_id), PRIMARY KEY(atelier_id, supervisor_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE atelier_session (id INT AUTO_INCREMENT NOT NULL, atelier_id INT DEFAULT NULL, start DATETIME NOT NULL, end DATETIME NOT NULL, calendar_id INT NOT NULL, name VARCHAR(255) DEFAULT NULL, themes VARCHAR(255) DEFAULT NULL, delay_answer DATETIME DEFAULT NULL, INDEX IDX_E27D8E6C82E2CF35 (atelier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE calendar_exception (id INT AUTO_INCREMENT NOT NULL, pro_id INT NOT NULL, start DATETIME NOT NULL, end DATETIME NOT NULL, INDEX IDX_3CA50527C3B7E4BA (pro_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cancelled_rdv (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, pro_id INT DEFAULT NULL, start DATETIME NOT NULL, end DATETIME NOT NULL, reason VARCHAR(255) NOT NULL, viewed TINYINT(1) NOT NULL, INDEX IDX_A20913B2A76ED395 (user_id), INDEX IDX_A20913B2C3B7E4BA (pro_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE extra_page (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE logs (id INT AUTO_INCREMENT NOT NULL, message VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE overtime (id INT AUTO_INCREMENT NOT NULL, pro_id INT NOT NULL, start DATETIME NOT NULL, end DATETIME NOT NULL, calendar_id INT NOT NULL, INDEX IDX_76AAE270C3B7E4BA (pro_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE page (id INT AUTO_INCREMENT NOT NULL, keyword VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE professional (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, job VARCHAR(255) NOT NULL, citation VARCHAR(255) NOT NULL, description VARCHAR(4096) NOT NULL, use_agenda TINYINT(1) NOT NULL, can_manage_atelier TINYINT(1) NOT NULL, page_content LONGTEXT NOT NULL, image_file VARCHAR(255) NOT NULL, can_manage_supervisors TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_B3B573AAA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rdv (id INT AUTO_INCREMENT NOT NULL, pro_id INT NOT NULL, client_id INT NOT NULL, start DATETIME NOT NULL, end DATETIME NOT NULL, validate TINYINT(1) NOT NULL, viewed TINYINT(1) NOT NULL, INDEX IDX_10C31F86C3B7E4BA (pro_id), INDEX IDX_10C31F8619EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE supervisor (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, picture_file VARCHAR(255) NOT NULL, profession VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE temoignage (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, content LONGTEXT DEFAULT NULL, audio_filename VARCHAR(255) DEFAULT NULL, author VARCHAR(255) NOT NULL, creation DATETIME NOT NULL, image_filename VARCHAR(255) NOT NULL, publication DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, tel VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, regular TINYINT(1) NOT NULL, activation_id VARCHAR(255) NOT NULL, activated TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE work_time (id INT AUTO_INCREMENT NOT NULL, pro_id INT NOT NULL, day INT NOT NULL, start TIME NOT NULL, end TIME NOT NULL, calendar_id INT NOT NULL, INDEX IDX_9657297DC3B7E4BA (pro_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE answer_user_session ADD CONSTRAINT FK_2AEFB43CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE answer_user_session ADD CONSTRAINT FK_2AEFB43C613FECDF FOREIGN KEY (session_id) REFERENCES atelier_session (id)');
        $this->addSql('ALTER TABLE ask_cancel ADD CONSTRAINT FK_AB49B94A4CCE3F86 FOREIGN KEY (rdv_id) REFERENCES rdv (id)');
        $this->addSql('ALTER TABLE atelier_user ADD CONSTRAINT FK_4D145FAA82E2CF35 FOREIGN KEY (atelier_id) REFERENCES atelier (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE atelier_user ADD CONSTRAINT FK_4D145FAAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE atelier_supervisor ADD CONSTRAINT FK_809631F582E2CF35 FOREIGN KEY (atelier_id) REFERENCES atelier (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE atelier_supervisor ADD CONSTRAINT FK_809631F519E9AC5F FOREIGN KEY (supervisor_id) REFERENCES supervisor (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE atelier_session ADD CONSTRAINT FK_E27D8E6C82E2CF35 FOREIGN KEY (atelier_id) REFERENCES atelier (id)');
        $this->addSql('ALTER TABLE calendar_exception ADD CONSTRAINT FK_3CA50527C3B7E4BA FOREIGN KEY (pro_id) REFERENCES professional (id)');
        $this->addSql('ALTER TABLE cancelled_rdv ADD CONSTRAINT FK_A20913B2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE cancelled_rdv ADD CONSTRAINT FK_A20913B2C3B7E4BA FOREIGN KEY (pro_id) REFERENCES professional (id)');
        $this->addSql('ALTER TABLE overtime ADD CONSTRAINT FK_76AAE270C3B7E4BA FOREIGN KEY (pro_id) REFERENCES professional (id)');
        $this->addSql('ALTER TABLE professional ADD CONSTRAINT FK_B3B573AAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE rdv ADD CONSTRAINT FK_10C31F86C3B7E4BA FOREIGN KEY (pro_id) REFERENCES professional (id)');
        $this->addSql('ALTER TABLE rdv ADD CONSTRAINT FK_10C31F8619EB6921 FOREIGN KEY (client_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE work_time ADD CONSTRAINT FK_9657297DC3B7E4BA FOREIGN KEY (pro_id) REFERENCES professional (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE atelier_user DROP FOREIGN KEY FK_4D145FAA82E2CF35');
        $this->addSql('ALTER TABLE atelier_supervisor DROP FOREIGN KEY FK_809631F582E2CF35');
        $this->addSql('ALTER TABLE atelier_session DROP FOREIGN KEY FK_E27D8E6C82E2CF35');
        $this->addSql('ALTER TABLE answer_user_session DROP FOREIGN KEY FK_2AEFB43C613FECDF');
        $this->addSql('ALTER TABLE calendar_exception DROP FOREIGN KEY FK_3CA50527C3B7E4BA');
        $this->addSql('ALTER TABLE cancelled_rdv DROP FOREIGN KEY FK_A20913B2C3B7E4BA');
        $this->addSql('ALTER TABLE overtime DROP FOREIGN KEY FK_76AAE270C3B7E4BA');
        $this->addSql('ALTER TABLE rdv DROP FOREIGN KEY FK_10C31F86C3B7E4BA');
        $this->addSql('ALTER TABLE work_time DROP FOREIGN KEY FK_9657297DC3B7E4BA');
        $this->addSql('ALTER TABLE ask_cancel DROP FOREIGN KEY FK_AB49B94A4CCE3F86');
        $this->addSql('ALTER TABLE atelier_supervisor DROP FOREIGN KEY FK_809631F519E9AC5F');
        $this->addSql('ALTER TABLE answer_user_session DROP FOREIGN KEY FK_2AEFB43CA76ED395');
        $this->addSql('ALTER TABLE atelier_user DROP FOREIGN KEY FK_4D145FAAA76ED395');
        $this->addSql('ALTER TABLE cancelled_rdv DROP FOREIGN KEY FK_A20913B2A76ED395');
        $this->addSql('ALTER TABLE professional DROP FOREIGN KEY FK_B3B573AAA76ED395');
        $this->addSql('ALTER TABLE rdv DROP FOREIGN KEY FK_10C31F8619EB6921');
        $this->addSql('DROP TABLE action');
        $this->addSql('DROP TABLE answer_user_session');
        $this->addSql('DROP TABLE ask_cancel');
        $this->addSql('DROP TABLE atelier');
        $this->addSql('DROP TABLE atelier_user');
        $this->addSql('DROP TABLE atelier_supervisor');
        $this->addSql('DROP TABLE atelier_session');
        $this->addSql('DROP TABLE calendar_exception');
        $this->addSql('DROP TABLE cancelled_rdv');
        $this->addSql('DROP TABLE extra_page');
        $this->addSql('DROP TABLE logs');
        $this->addSql('DROP TABLE overtime');
        $this->addSql('DROP TABLE page');
        $this->addSql('DROP TABLE professional');
        $this->addSql('DROP TABLE rdv');
        $this->addSql('DROP TABLE supervisor');
        $this->addSql('DROP TABLE temoignage');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE work_time');
    }
}
