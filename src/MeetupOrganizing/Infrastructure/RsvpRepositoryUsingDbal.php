<?php
declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure;

use Assert\Assert;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Statement;
use MeetupOrganizing\Domain\Rsvp;
use MeetupOrganizing\Domain\RsvpRepository;
use PDO;

final class RsvpRepositoryUsingDbal implements RsvpRepository
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function save(Rsvp $rsvp): void
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
        $statement = $this->connection
            ->createQueryBuilder()
            ->select('*')
            ->from('rsvps')
            ->where('meetupId = :meetupId')
            ->setParameter('meetupId', $meetupId)
            ->execute();

        Assert::that($statement)->isInstanceOf(Statement::class);
        $records = $statement->fetchAll(PDO::FETCH_ASSOC);

        return array_map(
            function (array $record) {
                return Rsvp::fromDatabaseRecord($record);
            },
            $records
        );
    }
}
