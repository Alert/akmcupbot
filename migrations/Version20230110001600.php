<?php
declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230110001600 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<SQL
create table dynamic_param
(
    id        int unsigned auto_increment comment 'identifier' primary key,
    name      varchar(50)                              not null comment 'Param name',
    value     varchar(500) default ''                  not null comment 'Param value',
    create_ts timestamp    default current_timestamp() not null comment 'Create timestamp',
    update_ts timestamp                                null on update current_timestamp() comment 'Update timestamp',
    constraint uq_dynamic_config_name unique (name)
) comment 'Dynamic params';
SQL
        );

        $this->addSql(<<<SQL
INSERT INTO dynamic_param (id, name, value, create_ts, update_ts) VALUES (1, 'contacts.phone', '+0 (000) 000-00-00', '2023-01-09 21:15:06', '2023-01-09 21:15:22');
SQL
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE dynamic_param');
    }
}
