<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181222190019 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE candidacy (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, establishment_id INT DEFAULT NULL, status VARCHAR(255) NOT NULL, note INT DEFAULT NULL, INDEX IDX_D930569DA76ED395 (user_id), INDEX IDX_D930569D8565851 (establishment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE content (id INT AUTO_INCREMENT NOT NULL, content1 LONGTEXT NOT NULL, content2 LONGTEXT NOT NULL, content3 LONGTEXT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE establishement (id INT AUTO_INCREMENT NOT NULL, groupe_id INT DEFAULT NULL, gir_id INT DEFAULT NULL, notation INT NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, telephon VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, address VARCHAR(255) DEFAULT NULL, zip_code VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, region VARCHAR(255) DEFAULT NULL, department VARCHAR(255) DEFAULT NULL, INDEX IDX_8BBF0C727A45358C (groupe_id), INDEX IDX_8BBF0C726827FF48 (gir_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE file (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, document VARCHAR(255) NOT NULL, INDEX IDX_8C9F3610A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gir (id INT AUTO_INCREMENT NOT NULL, one INT NOT NULL, two INT NOT NULL, three INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupe (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE home (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, head_file VARCHAR(255) NOT NULL, info1 LONGTEXT NOT NULL, info2 LONGTEXT NOT NULL, info3 LONGTEXT NOT NULL, picture1 VARCHAR(255) NOT NULL, picture2 VARCHAR(255) NOT NULL, picture3 VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE note (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, place VARCHAR(255) NOT NULL, notation INT NOT NULL, commentary LONGTEXT NOT NULL, is_visible TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE price (id INT AUTO_INCREMENT NOT NULL, gir1 DOUBLE PRECISION NOT NULL, gir2 DOUBLE PRECISION NOT NULL, gir3 DOUBLE PRECISION NOT NULL, gir4 DOUBLE PRECISION NOT NULL, gir5 DOUBLE PRECISION NOT NULL, gir6 DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service (id INT AUTO_INCREMENT NOT NULL, establishement_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_E19D9AD2C65F9894 (establishement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, groupe_id INT DEFAULT NULL, username VARCHAR(100) NOT NULL, birthday DATE NOT NULL, gender VARCHAR(64) NOT NULL, password VARCHAR(64) NOT NULL, email VARCHAR(254) NOT NULL, lastname VARCHAR(128) DEFAULT NULL, firstname VARCHAR(128) DEFAULT NULL, phone VARCHAR(128) DEFAULT NULL, is_active TINYINT(1) NOT NULL, created INT NOT NULL, last_access INT NOT NULL, token VARCHAR(254) DEFAULT NULL, roles VARCHAR(255) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, zip_code VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, region VARCHAR(255) DEFAULT NULL, department VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D6497A45358C (groupe_id), PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE candidacy ADD CONSTRAINT FK_D930569DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE candidacy ADD CONSTRAINT FK_D930569D8565851 FOREIGN KEY (establishment_id) REFERENCES establishement (id)');
        $this->addSql('ALTER TABLE establishement ADD CONSTRAINT FK_8BBF0C727A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id)');
        $this->addSql('ALTER TABLE establishement ADD CONSTRAINT FK_8BBF0C726827FF48 FOREIGN KEY (gir_id) REFERENCES gir (id)');
        $this->addSql('ALTER TABLE file ADD CONSTRAINT FK_8C9F3610A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD2C65F9894 FOREIGN KEY (establishement_id) REFERENCES establishement (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6497A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE candidacy DROP FOREIGN KEY FK_D930569D8565851');
        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD2C65F9894');
        $this->addSql('ALTER TABLE establishement DROP FOREIGN KEY FK_8BBF0C726827FF48');
        $this->addSql('ALTER TABLE establishement DROP FOREIGN KEY FK_8BBF0C727A45358C');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6497A45358C');
        $this->addSql('ALTER TABLE candidacy DROP FOREIGN KEY FK_D930569DA76ED395');
        $this->addSql('ALTER TABLE file DROP FOREIGN KEY FK_8C9F3610A76ED395');
        $this->addSql('DROP TABLE candidacy');
        $this->addSql('DROP TABLE content');
        $this->addSql('DROP TABLE establishement');
        $this->addSql('DROP TABLE file');
        $this->addSql('DROP TABLE gir');
        $this->addSql('DROP TABLE groupe');
        $this->addSql('DROP TABLE home');
        $this->addSql('DROP TABLE note');
        $this->addSql('DROP TABLE price');
        $this->addSql('DROP TABLE service');
        $this->addSql('DROP TABLE user');
    }
}
