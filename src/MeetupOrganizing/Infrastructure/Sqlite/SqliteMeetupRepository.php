<?php
declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure\Sqlite;

use DateTimeImmutable;
use Doctrine\DBAL\Connection;
use MeetupOrganizing\Application\ListMeetups\ListMeetupsRepository;
use MeetupOrganizing\Domain\Model\Meetup\Meetup;
use MeetupOrganizing\Domain\Model\Meetup\MeetupRepository;
use MeetupOrganizing\Domain\Model\Meetup\ScheduledDate;
use PDO;
use MeetupOrganizing\Application\ListMeetups\Meetup as MeetupForList;

final class SqliteMeetupRepository implements MeetupRepository, ListMeetupsRepository
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
        $data = $meetup->getData();
        unset($data['meetupId']);

        $this->connection->insert('meetups', $data);

        $meetupId = (int)$this->connection->lastInsertId();
        $meetup->setId($meetupId);
    }

    public function upcomingMeetups(DateTimeImmutable $now): array
    {
        $upcomingMeetups = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('meetups')
            ->where('scheduledFor >= :now')
            ->andWhere('wasCancelled = 0')
            ->setParameter('now', $now->format(ScheduledDate::DATE_TIME_FORMAT))
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
            ->andWhere('wasCancelled = 0')
            ->setParameter('now', $now->format(ScheduledDate::DATE_TIME_FORMAT))
            ->execute()
            ->fetchAll(PDO::FETCH_ASSOC);;

        return array_map([MeetupForList::class, 'fromDatabaseRecord'], $pastMeetups);
    }
}
