<?php
declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230104151100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<SQL
create table season
(
    id    int auto_increment comment 'identifier' primary key,
    title varchar(50) null comment 'Title'
) comment 'List of seasons';

create table event
(
    id             int auto_increment comment 'Identifier' primary key,
    season_id      int                    not null comment 'Season id',
    num            tinyint unsigned       not null comment 'Number in season',
    date_start     date                   not null comment 'Event date start',
    date_end       date                   not null comment 'Event date end',
    readable_dates varchar(50) default '' null comment 'Human readable dates range',
    constraint uq_event_session_id_num unique (season_id, num),
    constraint fk_event_season_id_season_id foreign key (season_id) references season (id)
) comment 'List of events';
SQL
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE season');
    }
}
