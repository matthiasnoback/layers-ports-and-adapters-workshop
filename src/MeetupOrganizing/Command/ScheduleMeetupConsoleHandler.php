<?php
declare(strict_types=1);

namespace MeetupOrganizing\Command;

use Doctrine\DBAL\Connection;
use MeetupOrganizing\Entity\Meetup;
use MeetupOrganizing\Entity\ScheduledDate;
use MeetupOrganizing\Entity\UserId;
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
        $meetup = new Meetup(
            UserId::fromInt((int)$args->getArgument('organizerId')),
            $args->getArgument('name'),
            $args->getArgument('description'),
            ScheduledDate::fromString($args->getArgument('scheduledFor'))
        );

        $this->connection->insert('meetups', $meetup->getData());

        $io->writeLine('<success>Scheduled the meetup successfully</success>');

        return 0;
    }
}
