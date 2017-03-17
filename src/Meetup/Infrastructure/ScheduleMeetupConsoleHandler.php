<?php
declare(strict_types = 1);

namespace Meetup\Infrastructure;

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
        $this->scheduleMeetupHandler->handle(
            $args->getArgument('name'),
            $args->getArgument('description'),
            $args->getArgument('scheduledFor')
        );

        $io->writeLine('<success>Scheduled the meetup successfully</success>');

        return 0;
    }
}
