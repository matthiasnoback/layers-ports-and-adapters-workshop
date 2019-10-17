<?php
declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure;

use DateTimeImmutable;
use Doctrine\DBAL\Connection;
use MeetupOrganizing\Application\ListMeetupsRepository;
use MeetupOrganizing\Application\MeetupForList;
use MeetupOrganizing\Domain\Meetup;
use MeetupOrganizing\Domain\ScheduledDate;
use PDO;

final class MeetupRepository implements ListMeetupsRepository
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
        $this->connection->insert('meetups', $meetup->getData());

        $meetupId = (int)$this->connection->lastInsertId();
        $meetup->setId($meetupId);
    }

    public function upcomingMeetups(DateTimeImmutable $now): array
    {
        $upcomingMeetups = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('meetups')
            ->where('scheduledFor >= :now')
            ->setParameter('now', $now->format(ScheduledDate::DATE_TIME_FORMAT))
            ->andWhere('wasCancelled = :wasNotCancelled')
            ->setParameter('wasNotCancelled', 0)
            ->execute()
            ->fetchAll(PDO::FETCH_ASSOC);

        return array_map([MeetupForList::class, 'fromDatabaseRecord'], $upcomingMeetups);
    }

    public function pastMeetups(DateTimeImmutable $now): array
    {
        $pastMeetups = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('meetups')
            ->where('scheduledFor < :now')
            ->setParameter('now', $now->format(ScheduledDate::DATE_TIME_FORMAT))
            ->andWhere('wasCancelled = :wasNotCancelled')
            ->setParameter('wasNotCancelled', 0)
            ->execute()
            ->fetchAll(PDO::FETCH_ASSOC);;

        return array_map([MeetupForList::class, 'fromDatabaseRecord'], $pastMeetups);
    }
}
