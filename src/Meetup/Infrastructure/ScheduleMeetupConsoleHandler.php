<?php
declare(strict_types = 1);

namespace Meetup\Infrastructure;

use Meetup\Application\ScheduleMeetup;
use Meetup\Application\ScheduleMeetupHandler;
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
        $command = new ScheduleMeetup();
        $command->name = $args->getArgument('name');
        $command->description = $args->getArgument('description');
        $command->scheduledFor = $args->getArgument('scheduledFor');

        $this->scheduleMeetupHandler->handle($command);

        $io->writeLine('<success>Scheduled the meetup successfully</success>');

        return 0;
    }
}
