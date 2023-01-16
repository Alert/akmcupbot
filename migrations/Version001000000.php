<?php
declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version001000000 extends AbstractMigration
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
    id        int unsigned auto_increment comment 'identifier'
        primary key,
    name      varchar(50)                               not null comment 'Param name',
    value     varchar(1000) default ''                  not null comment 'Param value',
    create_ts timestamp     default current_timestamp() not null comment 'Create timestamp',
    update_ts timestamp                                 null on update current_timestamp() comment 'Update timestamp',
    constraint uq_dynamic_config_name
        unique (name)
)
    comment 'Dynamic params';

create table season
(
    id    int auto_increment comment 'identifier'
        primary key,
    title varchar(50) null comment 'Title'
)
    comment 'List of seasons';

create table event
(
    id             int(11) unsigned auto_increment comment 'Identifier'
        primary key,
    season_id      int                    not null comment 'Season id',
    num            tinyint unsigned       not null comment 'Number in season',
    date_start     date                   not null comment 'Event date start',
    date_end       date                   not null comment 'Event date end',
    readable_dates varchar(50) default '' null comment 'Human readable dates range',
    constraint uq_event_session_id_num
        unique (season_id, num),
    constraint fk_event_season_id_season_id
        foreign key (season_id) references season (id)
)
    comment 'List of events';

create table event_detail
(
    id         int unsigned auto_increment comment 'identifier'
        primary key,
    event_id   int unsigned                                                  not null comment 'Link to event',
    type       enum ('media', 'text', 'link')                                not null comment 'Detail type',
    btn_text   varchar(50)      default ''                                   not null comment 'Button text',
    btn_action enum ('field', 'schedule', 'broadcast', 'result', 'register') not null comment 'Button action',
    value      text             default ''                                   not null comment 'Value of param',
    sort       tinyint unsigned default 0                                    not null comment 'sort in event',
    is_enabled tinyint(1)       default 1                                    not null comment 'Is enabled',
    create_ts  timestamp        default current_timestamp()                  not null comment 'Create timestamp',
    update_ts  timestamp                                                     null on update current_timestamp() comment 'Update timestamp',
    constraint uq_event_detail_event_id_btn_action
        unique (event_id, btn_action),
    constraint fk_event_detail_event_id_event_id
        foreign key (event_id) references event (id)
)
    comment 'Additional info about events';

create table webhook_log
(
    ts         timestamp(3) default current_timestamp(3) not null on update current_timestamp(3) comment 'timestamp'
        primary key,
    username   varchar(50)  default ''                   not null comment 'tg username',
    first_name varchar(50)  default ''                   not null comment 'First name',
    last_name  varchar(50)  default ''                   not null comment 'Last name',
    raw        text         default ''                   not null comment 'Raw data'
)
    comment 'Log incoming data to webhook';

create index ix_webhook_log_username_index
    on webhook_log (username);


SQL
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE dynamic_param,event,event_detail,season,webhook_log');
    }
}
