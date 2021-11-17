<?php
declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure\MySql;

use Assert\Assert;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Statement;
use Doctrine\DBAL\Query\QueryBuilder;
use MeetupOrganizing\Application\ListMeetupsRepositoryInterface;
use MeetupOrganizing\Application\MeetupForList;
use MeetupOrganizing\Domain\Entity\ScheduledDate;
use MeetupOrganizing\Infrastructure\SystemClock;
use PDO;

final class ListMeetupsRepository implements ListMeetupsRepositoryInterface
{
    private Connection $connection;
    private SystemClock $clock;

    public function __construct(Connection $connection, SystemClock $clock)
    {
        $this->connection = $connection;
        $this->clock = $clock;
    }

    public function listUpcomingMeetups(): array
    {
        $statement = $this->createListMeetupsQueryBuilder()
            ->andWhere('scheduledFor >= :now')
            ->execute();
        Assert::that($statement)->isInstanceOf(Statement::class);

        return array_map(
            fn (array $record) => MeetupForList::fromMySqlRecord($record),
            $statement->fetchAll(PDO::FETCH_ASSOC)
        );
    }

    public function listPastMeetups(): array
    {
        $statement = $this->createListMeetupsQueryBuilder()
            ->andWhere('scheduledFor < :now')
            ->execute();
        Assert::that($statement)->isInstanceOf(Statement::class);

        return array_map(
            fn (array $record) => MeetupForList::fromMySqlRecord($record),
            $statement->fetchAll(PDO::FETCH_ASSOC)
        );
    }

    private function createListMeetupsQueryBuilder(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select([
                'meetupId',
                'name',
                'scheduledFor',
            ])
            ->from('meetups')
            ->andWhere('wasCancelled = :wasNotCancelled')
            ->setParameter('wasNotCancelled', 0)
            ->setParameter('now', $this->clock->currentTime()->format(ScheduledDate::DATE_TIME_FORMAT));
    }
}
