<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200816133431 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `order` (id INT UNSIGNED AUTO_INCREMENT NOT NULL, voucher_id INT UNSIGNED DEFAULT NULL, order_id INT UNSIGNED NOT NULL, customer_id INT UNSIGNED NOT NULL, status VARCHAR(45) NOT NULL, amount NUMERIC(20, 4) NOT NULL, UNIQUE INDEX UNIQ_F529939828AA1B6F (voucher_id), UNIQUE INDEX unique_order_customer_id (order_id, customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `voucher` (id INT UNSIGNED AUTO_INCREMENT NOT NULL, status VARCHAR(45) NOT NULL, code VARCHAR(45) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F529939828AA1B6F FOREIGN KEY (voucher_id) REFERENCES `voucher` (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F529939828AA1B6F');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE `voucher`');
    }
}
