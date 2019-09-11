<?php
declare(strict_types=1);

namespace MeetupOrganizing\Command;

use Doctrine\DBAL\Connection;
use Webmozart\Console\Api\Args\Args;
use Webmozart\Console\Api\IO\IO;

final class ScheduleMeetupConsoleHandler
{
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function handle(Args $args, IO $io): int
    {
        $record = [
            'organizerId' => (int)$args->getArgument('organizerId'),
            'name' => $args->getArgument('name'),
            'description' => $args->getArgument('description'),
            'scheduledFor' => $args->getArgument('scheduledFor')
        ];
        $this->connection->insert('meetups', $record);

        $io->writeLine('<success>Scheduled the meetup successfully</success>');

        return 0;
    }
}
