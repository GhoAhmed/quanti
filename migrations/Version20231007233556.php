<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231007233556 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Relations';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commande_article (commande_id INT NOT NULL, article_id INT NOT NULL, INDEX IDX_F4817CC682EA2E54 (commande_id), INDEX IDX_F4817CC67294869C (article_id), PRIMARY KEY(commande_id, article_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commande_article ADD CONSTRAINT FK_F4817CC682EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commande_article ADD CONSTRAINT FK_F4817CC67294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article ADD article_commande_id INT NOT NULL');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E6618694433 FOREIGN KEY (article_commande_id) REFERENCES article_commande (id)');
        $this->addSql('CREATE INDEX IDX_23A0E6618694433 ON article (article_commande_id)');
        $this->addSql('ALTER TABLE article_commande ADD commade_id INT NOT NULL, DROP commande, DROP article');
        $this->addSql('ALTER TABLE article_commande ADD CONSTRAINT FK_3B025216C1ED175E FOREIGN KEY (commade_id) REFERENCES commande (id)');
        $this->addSql('CREATE INDEX IDX_3B025216C1ED175E ON article_commande (commade_id)');
        $this->addSql('ALTER TABLE commande ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_6EEAA67DA76ED395 ON commande (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande_article DROP FOREIGN KEY FK_F4817CC682EA2E54');
        $this->addSql('ALTER TABLE commande_article DROP FOREIGN KEY FK_F4817CC67294869C');
        $this->addSql('DROP TABLE commande_article');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E6618694433');
        $this->addSql('DROP INDEX IDX_23A0E6618694433 ON article');
        $this->addSql('ALTER TABLE article DROP article_commande_id');
        $this->addSql('ALTER TABLE article_commande DROP FOREIGN KEY FK_3B025216C1ED175E');
        $this->addSql('DROP INDEX IDX_3B025216C1ED175E ON article_commande');
        $this->addSql('ALTER TABLE article_commande ADD commande LONGTEXT NOT NULL COMMENT \'(DC2Type:object)\', ADD article LONGTEXT NOT NULL COMMENT \'(DC2Type:object)\', DROP commade_id');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DA76ED395');
        $this->addSql('DROP INDEX IDX_6EEAA67DA76ED395 ON commande');
        $this->addSql('ALTER TABLE commande DROP user_id');
    }
}