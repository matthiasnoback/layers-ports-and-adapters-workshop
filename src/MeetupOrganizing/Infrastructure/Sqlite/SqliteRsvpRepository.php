<?php
declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure\Sqlite;

use Doctrine\DBAL\Connection;
use MeetupOrganizing\Domain\Model\Rsvp\Rsvp;
use MeetupOrganizing\Domain\Model\Rsvp\RsvpRepository;
use PDO;

final class SqliteRsvpRepository implements RsvpRepository
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
                'rsvpId' => $rsvp->rsvpId()->toString(),
                'meetupId' => $rsvp->meetupId(),
                'userId' => $rsvp->userId()->asInt()
            ]
        );
    }

    public function getByMeetupId(int $meetupId): array
    {
        $records = $this->connection
            ->createQueryBuilder()
            ->select('*')
            ->from('rsvps')
            ->where('meetupId = :meetupId')
            ->setParameter('meetupId', $meetupId)
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
