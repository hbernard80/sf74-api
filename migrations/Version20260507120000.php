<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260507120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add publication status to posts.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("ALTER TABLE post ADD publication_status VARCHAR(20) DEFAULT 'draft' NOT NULL");
        $this->addSql("UPDATE post SET publication_status = 'draft' WHERE publication_status <> 'draft'");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE post DROP publication_status');
    }
}
