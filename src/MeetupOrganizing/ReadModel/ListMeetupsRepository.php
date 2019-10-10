<?php
declare(strict_types=1);

namespace MeetupOrganizing\ReadModel;

use DateTimeImmutable;
use Doctrine\DBAL\Connection;
use MeetupOrganizing\Entity\ScheduledDate;
use PDO;

final class ListMeetupsRepository
{
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param DateTimeImmutable $now
     * @return array&Meetup[]
     */
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

        return array_map([Meetup::class, 'fromDatabaseRecord'], $upcomingMeetups);
    }

    /**
     * @param DateTimeImmutable $now
     * @return array&Meetup[]
     */
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

        return array_map([Meetup::class, 'fromDatabaseRecord'], $pastMeetups);
    }
}
