<?php
declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure;

use DateTimeImmutable;
use Doctrine\DBAL\Connection;
use MeetupOrganizing\Application\ReadModel\ListMeetupsRepository;
use MeetupOrganizing\Domain\Meetup;
use MeetupOrganizing\Domain\MeetupRepository;
use MeetupOrganizing\Domain\ScheduledDate;
use PDO;
use MeetupOrganizing\Application\ReadModel\Meetup as MeetupForList;

final class MeetupRepositorySql implements MeetupRepository, ListMeetupsRepository
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
            ->execute()
            ->fetchAll(PDO::FETCH_ASSOC);;

        return array_map([MeetupForList::class, 'fromDatabaseRecord'], $pastMeetups);
    }
}
