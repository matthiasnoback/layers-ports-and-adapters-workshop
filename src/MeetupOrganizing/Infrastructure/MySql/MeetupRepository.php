<?php
declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure\MySql;

use Doctrine\DBAL\Connection;
use MeetupOrganizing\Domain\Entity\Meetup;

final class MeetupRepository
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function save(Meetup $meetup): int
    {
        $this->connection->insert('meetups', $meetup->asMySqlRecord());

        return (int)$this->connection->lastInsertId();
    }
}