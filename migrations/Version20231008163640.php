<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231008163640 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande_article DROP FOREIGN KEY FK_F4817CC67294869C');
        $this->addSql('ALTER TABLE commande_article DROP FOREIGN KEY FK_F4817CC682EA2E54');
        $this->addSql('DROP TABLE commande_article');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E6618694433');
        $this->addSql('DROP INDEX IDX_23A0E6618694433 ON article');
        $this->addSql('ALTER TABLE article DROP article_commande_id');
        $this->addSql('ALTER TABLE article_commande DROP FOREIGN KEY FK_3B025216C1ED175E');
        $this->addSql('DROP INDEX IDX_3B025216C1ED175E ON article_commande');
        $this->addSql('ALTER TABLE article_commande ADD article_id INT NOT NULL, CHANGE commade_id commande_id INT NOT NULL');
        $this->addSql('ALTER TABLE article_commande ADD CONSTRAINT FK_3B02521682EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE article_commande ADD CONSTRAINT FK_3B0252167294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('CREATE INDEX IDX_3B02521682EA2E54 ON article_commande (commande_id)');
        $this->addSql('CREATE INDEX IDX_3B0252167294869C ON article_commande (article_id)');
        $this->addSql('ALTER TABLE contact ADD zip VARCHAR(255) NOT NULL, DROP country, DROP zip_code');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commande_article (commande_id INT NOT NULL, article_id INT NOT NULL, INDEX IDX_F4817CC682EA2E54 (commande_id), INDEX IDX_F4817CC67294869C (article_id), PRIMARY KEY(commande_id, article_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE commande_article ADD CONSTRAINT FK_F4817CC67294869C FOREIGN KEY (article_id) REFERENCES article (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commande_article ADD CONSTRAINT FK_F4817CC682EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article ADD article_commande_id INT NOT NULL');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E6618694433 FOREIGN KEY (article_commande_id) REFERENCES article_commande (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_23A0E6618694433 ON article (article_commande_id)');
        $this->addSql('ALTER TABLE article_commande DROP FOREIGN KEY FK_3B02521682EA2E54');
        $this->addSql('ALTER TABLE article_commande DROP FOREIGN KEY FK_3B0252167294869C');
        $this->addSql('DROP INDEX IDX_3B02521682EA2E54 ON article_commande');
        $this->addSql('DROP INDEX IDX_3B0252167294869C ON article_commande');
        $this->addSql('ALTER TABLE article_commande ADD commade_id INT NOT NULL, DROP commande_id, DROP article_id');
        $this->addSql('ALTER TABLE article_commande ADD CONSTRAINT FK_3B025216C1ED175E FOREIGN KEY (commade_id) REFERENCES commande (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_3B025216C1ED175E ON article_commande (commade_id)');
        $this->addSql('ALTER TABLE contact ADD zip_code VARCHAR(255) NOT NULL, CHANGE zip country VARCHAR(255) NOT NULL');
    }
}
