<?php
declare(strict_types=1);

namespace MeetupOrganizing\Repository;

use Doctrine\DBAL\Connection;
use MeetupOrganizing\Entity\Meetup;

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
