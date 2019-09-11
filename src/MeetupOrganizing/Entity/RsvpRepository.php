<?php
declare(strict_types=1);

namespace MeetupOrganizing\Entity;

use Doctrine\DBAL\Connection;
use PDO;

final class RsvpRepository
{
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function save(Rsvp $rsvp)
    {
        $this->connection->insert(
            'rsvps',
            [
                'rsvp_id' => $rsvp->rsvpId()->toString(),
                'meetup_id' => $rsvp->meetupId(),
                'user_id' => $rsvp->userId()->asInt()
            ]
        );
    }

    /**
     * @param int $meetupId
     * @return array&Rsvp[]
     */
    public function getByMeetupId(int $meetupId): array
    {
        $records = $this->connection
            ->createQueryBuilder()
            ->select('*')
            ->from('rsvps')
            ->where('meetup_id = :meetup_id')
            ->setParameter('meetup_id', $meetupId)
            ->execute()
            ->fetchAll(PDO::FETCH_ASSOC);

        return array_map(
            function (array $record) {
                return Rsvp::fromDatabaseRecord($record);
            },
            $records
        );
    }
}
