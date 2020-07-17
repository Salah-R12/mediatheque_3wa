<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200712202313 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE media_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE borrow_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE digital_book_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE digital_film_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE digital_music_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE media_type_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE member_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE role_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE staff_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE stockable_book_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE stockable_film_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE stockable_music_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE book (id INT NOT NULL, edition VARCHAR(255) DEFAULT NULL, page_nb INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE media (id INT NOT NULL, media_type_id_id INT NOT NULL, name VARCHAR(500) NOT NULL, author VARCHAR(500) DEFAULT NULL, description TEXT DEFAULT NULL, media_type_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6A2CA10C7B6DD734 ON media (media_type_id_id)');
        $this->addSql('CREATE TABLE borrow (id INT NOT NULL, member_id_id INT NOT NULL, media_id_id INT NOT NULL, borrow_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, return_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, expected_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_55DBA8B01D650BA4 ON borrow (member_id_id)');
        $this->addSql('CREATE INDEX IDX_55DBA8B0605D5AE6 ON borrow (media_id_id)');
        $this->addSql('CREATE TABLE digital_book (id INT NOT NULL, media_id_id INT NOT NULL, url VARCHAR(500) DEFAULT NULL, path VARCHAR(500) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_92EC227E605D5AE6 ON digital_book (media_id_id)');
        $this->addSql('CREATE TABLE digital_film (id INT NOT NULL, media_id_id INT NOT NULL, url VARCHAR(500) DEFAULT NULL, path VARCHAR(500) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DB4D3F6D605D5AE6 ON digital_film (media_id_id)');
        $this->addSql('CREATE TABLE digital_music (id INT NOT NULL, media_id_id INT NOT NULL, url VARCHAR(500) DEFAULT NULL, path VARCHAR(500) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2B6877CA605D5AE6 ON digital_music (media_id_id)');
        $this->addSql('CREATE TABLE film (id INT NOT NULL, original_title VARCHAR(500) NOT NULL, synopsis VARCHAR(255) DEFAULT NULL, duration INT DEFAULT NULL, director VARCHAR(255) DEFAULT NULL, actors VARCHAR(500) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE media_type (id INT NOT NULL, name VARCHAR(255) NOT NULL, borrow_duration INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE member (id INT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, address1 VARCHAR(500) NOT NULL, address2 VARCHAR(255) DEFAULT NULL, zipcode VARCHAR(50) NOT NULL, city VARCHAR(255) NOT NULL, phone VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE music (id INT NOT NULL, singer VARCHAR(255) DEFAULT NULL, band VARCHAR(255) DEFAULT NULL, titles VARCHAR(500) DEFAULT NULL, album VARCHAR(255) DEFAULT NULL, compositor VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE role (id INT NOT NULL, name INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE staff (id INT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, address1 VARCHAR(1024) DEFAULT NULL, address2 VARCHAR(1024) DEFAULT NULL, zipcode VARCHAR(50) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, phone VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE staff_role (staff_id INT NOT NULL, role_id INT NOT NULL, PRIMARY KEY(staff_id, role_id))');
        $this->addSql('CREATE INDEX IDX_B55FFCE5D4D57CD ON staff_role (staff_id)');
        $this->addSql('CREATE INDEX IDX_B55FFCE5D60322AC ON staff_role (role_id)');
        $this->addSql('CREATE TABLE stockable_book (id INT NOT NULL, media_id_id INT NOT NULL, stock INT DEFAULT NULL, reception_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1A14ABE2605D5AE6 ON stockable_book (media_id_id)');
        $this->addSql('CREATE TABLE stockable_film (id INT NOT NULL, media_id_id INT NOT NULL, stock INT DEFAULT NULL, reception_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_53B5B6F1605D5AE6 ON stockable_film (media_id_id)');
        $this->addSql('CREATE TABLE stockable_music (id INT NOT NULL, media_id_id INT NOT NULL, stock INT DEFAULT NULL, reception_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D259502C605D5AE6 ON stockable_music (media_id_id)');
        $this->addSql('ALTER TABLE book ADD CONSTRAINT FK_CBE5A331BF396750 FOREIGN KEY (id) REFERENCES media (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10C7B6DD734 FOREIGN KEY (media_type_id_id) REFERENCES media_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE borrow ADD CONSTRAINT FK_55DBA8B01D650BA4 FOREIGN KEY (member_id_id) REFERENCES member (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE borrow ADD CONSTRAINT FK_55DBA8B0605D5AE6 FOREIGN KEY (media_id_id) REFERENCES media (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE digital_book ADD CONSTRAINT FK_92EC227E605D5AE6 FOREIGN KEY (media_id_id) REFERENCES book (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE digital_film ADD CONSTRAINT FK_DB4D3F6D605D5AE6 FOREIGN KEY (media_id_id) REFERENCES film (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE digital_music ADD CONSTRAINT FK_2B6877CA605D5AE6 FOREIGN KEY (media_id_id) REFERENCES music (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE film ADD CONSTRAINT FK_8244BE22BF396750 FOREIGN KEY (id) REFERENCES media (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE music ADD CONSTRAINT FK_CD52224ABF396750 FOREIGN KEY (id) REFERENCES media (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE staff_role ADD CONSTRAINT FK_B55FFCE5D4D57CD FOREIGN KEY (staff_id) REFERENCES staff (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE staff_role ADD CONSTRAINT FK_B55FFCE5D60322AC FOREIGN KEY (role_id) REFERENCES role (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE stockable_book ADD CONSTRAINT FK_1A14ABE2605D5AE6 FOREIGN KEY (media_id_id) REFERENCES book (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE stockable_film ADD CONSTRAINT FK_53B5B6F1605D5AE6 FOREIGN KEY (media_id_id) REFERENCES film (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE stockable_music ADD CONSTRAINT FK_D259502C605D5AE6 FOREIGN KEY (media_id_id) REFERENCES music (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE digital_book DROP CONSTRAINT FK_92EC227E605D5AE6');
        $this->addSql('ALTER TABLE stockable_book DROP CONSTRAINT FK_1A14ABE2605D5AE6');
        $this->addSql('ALTER TABLE book DROP CONSTRAINT FK_CBE5A331BF396750');
        $this->addSql('ALTER TABLE borrow DROP CONSTRAINT FK_55DBA8B0605D5AE6');
        $this->addSql('ALTER TABLE film DROP CONSTRAINT FK_8244BE22BF396750');
        $this->addSql('ALTER TABLE music DROP CONSTRAINT FK_CD52224ABF396750');
        $this->addSql('ALTER TABLE digital_film DROP CONSTRAINT FK_DB4D3F6D605D5AE6');
        $this->addSql('ALTER TABLE stockable_film DROP CONSTRAINT FK_53B5B6F1605D5AE6');
        $this->addSql('ALTER TABLE media DROP CONSTRAINT FK_6A2CA10C7B6DD734');
        $this->addSql('ALTER TABLE borrow DROP CONSTRAINT FK_55DBA8B01D650BA4');
        $this->addSql('ALTER TABLE digital_music DROP CONSTRAINT FK_2B6877CA605D5AE6');
        $this->addSql('ALTER TABLE stockable_music DROP CONSTRAINT FK_D259502C605D5AE6');
        $this->addSql('ALTER TABLE staff_role DROP CONSTRAINT FK_B55FFCE5D60322AC');
        $this->addSql('ALTER TABLE staff_role DROP CONSTRAINT FK_B55FFCE5D4D57CD');
        $this->addSql('DROP SEQUENCE media_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE borrow_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE digital_book_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE digital_film_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE digital_music_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE media_type_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE member_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE role_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE staff_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE stockable_book_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE stockable_film_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE stockable_music_id_seq CASCADE');
        $this->addSql('DROP TABLE book');
        $this->addSql('DROP TABLE media');
        $this->addSql('DROP TABLE borrow');
        $this->addSql('DROP TABLE digital_book');
        $this->addSql('DROP TABLE digital_film');
        $this->addSql('DROP TABLE digital_music');
        $this->addSql('DROP TABLE film');
        $this->addSql('DROP TABLE media_type');
        $this->addSql('DROP TABLE member');
        $this->addSql('DROP TABLE music');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE staff');
        $this->addSql('DROP TABLE staff_role');
        $this->addSql('DROP TABLE stockable_book');
        $this->addSql('DROP TABLE stockable_film');
        $this->addSql('DROP TABLE stockable_music');
    }
}
