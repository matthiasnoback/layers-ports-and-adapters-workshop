<?php
declare(strict_types=1);

namespace MeetupOrganizing\Entity;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Synchronizer\SingleDatabaseSynchronizer;
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
        $this->prepareSchema();
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

    public function byId(int $id): Meetup
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

    public function deleteAll(): void
    {
        $this->connection->executeQuery('DELETE FROM meetups');
    }

    private function prepareSchema(): void
    {
        /*
         * Not exactly a best practice. But for the scope of this project: make sure the table we need exists.
         */

        $schema = new Schema();

        $table = $schema->createTable('meetups');
        $table->addColumn('meetup_id', 'integer', ['autoincrement' => true]);
        $table->addColumn('organizer_id', 'integer');
        $table->addColumn('name', 'string');
        $table->addColumn('description', 'string');
        $table->addColumn('scheduled_for', 'string');
        $table->setPrimaryKey(['meetup_id']);

        $synchronizer = new SingleDatabaseSynchronizer($this->connection);
        $synchronizer->updateSchema($schema, true);
    }
}
