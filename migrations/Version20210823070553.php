<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210823070553 extends AbstractMigration
{
  public function getDescription(): string {
    return '';
  }

  public function up(Schema $schema): void {
    // this up() migration is auto-generated, please modify it to your needs
    $this->addSql('ALTER TABLE notification ADD ticket_id INT DEFAULT NULL, ADD subject VARCHAR(100) NOT NULL, ADD status VARCHAR(100) NOT NULL');
    $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA700047D2 FOREIGN KEY (ticket_id) REFERENCES ticket (id)');
    $this->addSql('CREATE INDEX IDX_BF5476CA700047D2 ON notification (ticket_id)');
  }

  public function down(Schema $schema): void {
    // this down() migration is auto-generated, please modify it to your needs
    $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CA700047D2');
    $this->addSql('DROP INDEX IDX_BF5476CA700047D2 ON notification');
    $this->addSql('ALTER TABLE notification DROP ticket_id, DROP subject, DROP status');
  }
}