<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250317213359 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create employee table with necessary fields and constraints';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE employee (
                id SERIAL NOT NULL,
                first_name VARCHAR(255) NOT NULL,
                last_name VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL,
                hired_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
                salary DOUBLE PRECISION NOT NULL,
                created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
                updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
                PRIMARY KEY(id)
            )
        ');

        // Create a unique index on the email column
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5D9F75A1E7927C74 ON employee (email)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE employee');
    }
}
