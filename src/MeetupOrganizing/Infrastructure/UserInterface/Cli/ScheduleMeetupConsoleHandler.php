<?php
declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure\UserInterface\Cli;

use MeetupOrganizing\Application\ScheduleMeetup;
use MeetupOrganizing\Application\ScheduleMeetupHandler;
use Webmozart\Console\Api\Args\Args;
use Webmozart\Console\Api\IO\IO;

final class ScheduleMeetupConsoleHandler
{
    /**
     * @var ScheduleMeetupHandler
     */
    private $scheduleMeetupHandler;

    public function __construct(ScheduleMeetupHandler $scheduleMeetupHandler)
    {
        $this->scheduleMeetupHandler = $scheduleMeetupHandler;
    }

    public function handle(Args $args, IO $io): int
    {
        $scheduleMeetup = new ScheduleMeetup();
        $scheduleMeetup->name = $args->getArgument('name');
        $scheduleMeetup->description = $args->getArgument('description');
        $scheduleMeetup->scheduledFor = $args->getArgument('scheduledFor');

        $this->scheduleMeetupHandler->handle($scheduleMeetup);

        $io->writeLine('<success>Scheduled the meetup successfully</success>');

        return 0;
    }
}
