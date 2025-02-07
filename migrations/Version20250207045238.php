<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250207045238 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(sql: 'CREATE TABLE coupons (id SERIAL NOT NULL, code VARCHAR(50) NOT NULL, type VARCHAR(255) NOT NULL, value DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql(sql: 'CREATE TABLE products (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, price DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        $this->addSql(sql: 'CREATE SCHEMA public');
        $this->addSql(sql: 'DROP TABLE coupons');
        $this->addSql(sql: 'DROP TABLE products');
    }
}
