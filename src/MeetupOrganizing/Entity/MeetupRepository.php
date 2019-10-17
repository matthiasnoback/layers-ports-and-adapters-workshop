<?php
declare(strict_types=1);

namespace MeetupOrganizing\Entity;

use Doctrine\DBAL\Connection;

final class MeetupRepository
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Watch out: saving for now means inserting!
     */
    public function save(Meetup $meetup): void
    {
        $this->connection->insert('meetups', $meetup->asDatabaseRecord());

        $meetupId = (int)$this->connection->lastInsertId();
        $meetup->setId($meetupId);
    }
}
