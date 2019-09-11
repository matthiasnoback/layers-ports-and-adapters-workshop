<?php
declare(strict_types=1);

namespace MeetupOrganizing\Entity;

use Doctrine\DBAL\Connection;
use PDO;
use DateTimeImmutable;
use RuntimeException;

final class MeetupRepository
{
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function add(Meetup $meetup): void
    {
        $this->connection->insert(
            'meetups',
            [
                'organizer_id' => $meetup->organizerId()->asInt(),
                'name' => $meetup->name()->asString(),
                'description' => $meetup->description()->asString(),
                'scheduled_for' => $meetup->scheduledFor()->asString()
            ]
        );
        $id = (int)$this->connection->lastInsertId();

        $meetup->setMeetupId($id);
    }

    public function getById(int $id): Meetup
    {
        $record = $this->connection
            ->createQueryBuilder()
            ->select('*')
            ->from('meetups')
            ->where('meetup_id = :meetup_id')
            ->setParameter('meetup_id', $id)
            ->execute()
            ->fetch(PDO::FETCH_ASSOC);

        if ($record === false) {
            throw new RuntimeException('Meetup not found');
        }

        return Meetup::fromDatabaseRecord($record);
    }

    /**
     * @param DateTimeImmutable $now
     * @return Meetup[]
     */
    public function upcomingMeetups(DateTimeImmutable $now): array
    {
        $records = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('meetups')
            ->where('scheduled_for >= :now')
            ->setParameter('now', $now->format(ScheduledDate::DATE_TIME_FORMAT))
            ->execute()
            ->fetchAll(PDO::FETCH_ASSOC);

        return array_map(
            function (array $record) {
                return Meetup::fromDatabaseRecord($record);
            },
            $records
        );
    }

    /**
     * @param DateTimeImmutable $now
     * @return Meetup[]
     */
    public function pastMeetups(DateTimeImmutable $now): array
    {
        $records = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('meetups')
            ->where('scheduled_for < :now')
            ->setParameter('now', $now->format(ScheduledDate::DATE_TIME_FORMAT))
            ->execute()
            ->fetchAll(PDO::FETCH_ASSOC);

        return array_map(
            function (array $record) {
                return Meetup::fromDatabaseRecord($record);
            },
            $records
        );
    }
}
