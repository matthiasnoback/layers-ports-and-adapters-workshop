<?php
declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure;

use Assert\Assert;
use DateTimeImmutable;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Statement;
use MeetupOrganizing\Application\ListMeetupsRepository;
use MeetupOrganizing\Application\MeetupForList;
use MeetupOrganizing\Domain\Meetup;
use MeetupOrganizing\Domain\ScheduledDate;
use PDO;

final class MeetupRepository implements ListMeetupsRepository
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
        $this->connection->insert('meetups', $meetup->getData());

        $meetupId = (int)$this->connection->lastInsertId();
        $meetup->setId($meetupId);
    }

    public function upcomingMeetups(DateTimeImmutable $now): array
    {
        $statement = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('meetups')
            ->where('scheduledFor >= :now')
            ->setParameter('now', $now->format(ScheduledDate::DATE_TIME_FORMAT))
            ->andWhere('wasCancelled = :wasNotCancelled')
            ->setParameter('wasNotCancelled', 0)
            ->execute();
        Assert::that($statement)->isInstanceOf(Statement::class);

        $upcomingMeetups = $statement->fetchAll(PDO::FETCH_ASSOC);

        return array_map([MeetupForList::class, 'fromDatabaseRecord'], $upcomingMeetups);
    }

    public function pastMeetups(DateTimeImmutable $now): array
    {
        $statement = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('meetups')
            ->where('scheduledFor < :now')
            ->setParameter('now', $now->format(ScheduledDate::DATE_TIME_FORMAT))
            ->andWhere('wasCancelled = :wasNotCancelled')
            ->setParameter('wasNotCancelled', 0)
            ->execute();
        Assert::that($statement)->isInstanceOf(Statement::class);

        $pastMeetups = $statement->fetchAll(PDO::FETCH_ASSOC);

        return array_map([MeetupForList::class, 'fromDatabaseRecord'], $pastMeetups);
    }
}
