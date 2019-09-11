<?php
declare(strict_types=1);

namespace MeetupOrganizing\Entity;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Synchronizer\SingleDatabaseSynchronizer;
use PDO;

final class RsvpRepository
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

    public function save(Rsvp $rsvp)
    {
        $this->connection->insert(
            'rsvps',
            [
                'id' => $rsvp->rsvpId()->toString(),
                'meetup_id' => $rsvp->meetupId(),
                'user_id' => $rsvp->userId()->asInt()
            ]
        );
    }

    private function prepareSchema(): void
    {
        /*
         * Not exactly a best practice. But for the scope of this project: make sure the table we need exists.
         */

        $schema = new Schema();

        $table = $schema->createTable('rsvps');
        $table->addColumn('id', 'string');
        $table->addColumn('meetup_id', 'integer');
        $table->addColumn('user_id', 'integer');
        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['meetup_id', 'user_id']);

        $synchronizer = new SingleDatabaseSynchronizer($this->connection);
        $synchronizer->updateSchema($schema, true);
    }

    /**
     * @param int $meetupId
     * @return array&Rsvp[]
     */
    public function getByMeetupId(int $meetupId): array
    {
        $records = $this->connection
            ->createQueryBuilder()
            ->select('*')
            ->from('rsvps')
            ->where('meetup_id = :meetupId')
            ->setParameter('meetupId', $meetupId)
            ->execute()
            ->fetchAll(PDO::FETCH_ASSOC);

        return array_map(
            function (array $record) {
                return Rsvp::fromDatabaseRecord($record);
            },
            $records
        );
    }
}
