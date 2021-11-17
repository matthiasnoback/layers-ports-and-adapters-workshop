<?php
declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure\MySql;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Synchronizer\SingleDatabaseSynchronizer;

final class SchemaManager
{
    private Connection $connection;

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
        $meetupsTable->addColumn('meetupId', 'integer', ['autoincrement' => true]);
        $meetupsTable->addColumn('organizerId', 'integer');
        $meetupsTable->addColumn('name', 'string');
        $meetupsTable->addColumn('description', 'string');
        $meetupsTable->addColumn('scheduledFor', 'string');
        $meetupsTable->addColumn('wasCancelled', 'integer', ['default' => 0]);
        $meetupsTable->setPrimaryKey(['meetupId']);

        $rsvpsTable = $schema->createTable('rsvps');
        $rsvpsTable->addColumn('rsvpId', 'string');
        $rsvpsTable->addColumn('meetupId', 'string');
        $rsvpsTable->addColumn('userId', 'integer');
        $rsvpsTable->setPrimaryKey(['rsvpId']);
        $rsvpsTable->addUniqueIndex(['meetupId', 'userId']);

        return $schema;
    }
}
