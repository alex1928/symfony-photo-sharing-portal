<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190910133536 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE hashtag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hashtag_user (hashtag_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_38AA3ED0FB34EF56 (hashtag_id), INDEX IDX_38AA3ED0A76ED395 (user_id), PRIMARY KEY(hashtag_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hashtag_post (hashtag_id INT NOT NULL, post_id INT NOT NULL, INDEX IDX_EFB38414FB34EF56 (hashtag_id), INDEX IDX_EFB384144B89032C (post_id), PRIMARY KEY(hashtag_id, post_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE hashtag_user ADD CONSTRAINT FK_38AA3ED0FB34EF56 FOREIGN KEY (hashtag_id) REFERENCES hashtag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hashtag_user ADD CONSTRAINT FK_38AA3ED0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hashtag_post ADD CONSTRAINT FK_EFB38414FB34EF56 FOREIGN KEY (hashtag_id) REFERENCES hashtag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hashtag_post ADD CONSTRAINT FK_EFB384144B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE hashtag_user DROP FOREIGN KEY FK_38AA3ED0FB34EF56');
        $this->addSql('ALTER TABLE hashtag_post DROP FOREIGN KEY FK_EFB38414FB34EF56');
        $this->addSql('DROP TABLE hashtag');
        $this->addSql('DROP TABLE hashtag_user');
        $this->addSql('DROP TABLE hashtag_post');
    }
}
