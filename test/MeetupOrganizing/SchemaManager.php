<?php
declare(strict_types=1);

namespace MeetupOrganizing;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Synchronizer\SingleDatabaseSynchronizer;

final class SchemaManager
{
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function updateSchema(): void
    {
        $synchronizer = new SingleDatabaseSynchronizer($this->connection);
        $synchronizer->updateSchema($this->provideSchema(), true);
    }

    public function truncateTables(): void
    {
        foreach ($this->provideSchema()->getTables() as $table) {
            $this->connection->exec(
                $this->connection->getDatabasePlatform()->getTruncateTableSQL($table->getName())
            );
        }
    }

    private function provideSchema(): Schema
    {
        $schema = new Schema();

        $meetupsTable = $schema->createTable('meetups');
        $meetupsTable->addColumn('meetup_id', 'integer', ['autoincrement' => true]);
        $meetupsTable->addColumn('organizer_id', 'integer');
        $meetupsTable->addColumn('name', 'string');
        $meetupsTable->addColumn('description', 'string');
        $meetupsTable->addColumn('scheduled_for', 'string');
        $meetupsTable->setPrimaryKey(['meetup_id']);

        $rsvpsTable = $schema->createTable('rsvps');
        $rsvpsTable->addColumn('rsvp_id', 'string');
        $rsvpsTable->addColumn('meetup_id', 'integer');
        $rsvpsTable->addColumn('user_id', 'integer');
        $rsvpsTable->setPrimaryKey(['rsvp_id']);
        $rsvpsTable->addUniqueIndex(['meetup_id', 'user_id']);

        return $schema;
    }
}
