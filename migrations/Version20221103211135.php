<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221103211135 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_B6F7494EB6F7494EE8FF75CC9F3329DB ON question');
        $this->addSql('CREATE INDEX IDX_B6F7494EE8FF75CC9F3329DB ON question (faq, solution)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_B6F7494EE8FF75CC9F3329DB ON question');
        $this->addSql('CREATE FULLTEXT INDEX IDX_B6F7494EB6F7494EE8FF75CC9F3329DB ON question (question, faq, solution)');
    }
}
