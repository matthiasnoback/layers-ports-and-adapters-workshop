<?php
declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure\Database;

use DateTimeImmutable;
use Doctrine\DBAL\Connection;
use MeetupOrganizing\Application\ListMeetups\ListMeetupsRepository;
use MeetupOrganizing\Application\ListMeetups\MeetupForList;
use MeetupOrganizing\Domain\Model\Meetup\Meetup;
use MeetupOrganizing\Domain\Model\Meetup\MeetupRepository;
use MeetupOrganizing\Domain\Model\Meetup\ScheduledDate;
use PDO;

final class DbalMeetupRepository implements ListMeetupsRepository, MeetupRepository
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
