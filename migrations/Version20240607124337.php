<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240607124337 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book_user DROP CONSTRAINT fk_940e9d4116a2b381');
        $this->addSql('ALTER TABLE book_user DROP CONSTRAINT fk_940e9d41a76ed395');
        $this->addSql('DROP TABLE book_user');
        $this->addSql('ALTER TABLE book ADD user_id INT NOT NULL');
        // Если будут проблемы с миграцией
        //$this->addSql('ALTER TABLE book ADD user_id INT');
        //$this->addSql('UPDATE book SET user_id = 1 WHERE user_id IS NULL');
        //$this->addSql('ALTER TABLE book ALTER COLUMN user_id DROP NOT NULL');
        $this->addSql('ALTER TABLE book ADD CONSTRAINT FK_CBE5A331A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_CBE5A331A76ED395 ON book (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE book_user (book_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(book_id, user_id))');
        $this->addSql('CREATE INDEX idx_940e9d41a76ed395 ON book_user (user_id)');
        $this->addSql('CREATE INDEX idx_940e9d4116a2b381 ON book_user (book_id)');
        $this->addSql('ALTER TABLE book_user ADD CONSTRAINT fk_940e9d4116a2b381 FOREIGN KEY (book_id) REFERENCES book (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE book_user ADD CONSTRAINT fk_940e9d41a76ed395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE book DROP CONSTRAINT FK_CBE5A331A76ED395');
        $this->addSql('DROP INDEX IDX_CBE5A331A76ED395');
        $this->addSql('ALTER TABLE book DROP user_id');
    }
}
