<?php
declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230102151839 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<SQL
create table webhook_log
(
    ts         timestamp(3)           not null comment 'timestamp',
    username   varchar(50) default '' not null comment 'tg username',
    first_name varchar(50) default '' not null comment 'First name',
    last_name  varchar(50) default '' not null comment 'Last name',
    raw        text        default '' not null comment 'Raw data',
    constraint pk_webhook_log_ts
        primary key (ts)
)
    comment 'Log incoming data to webhook';
SQL
        );

        $this->addSql('create index ix_webhook_log_username_index on webhook_log (username);');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE webhook_log');
    }
}
