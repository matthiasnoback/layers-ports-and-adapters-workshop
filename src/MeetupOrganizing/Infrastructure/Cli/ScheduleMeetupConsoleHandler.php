<?php
declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure\Cli;

use MeetupOrganizing\Application\ScheduleMeetup\MeetupService;
use MeetupOrganizing\Application\ScheduleMeetup\ScheduleMeetup;
use Webmozart\Console\Api\Args\Args;
use Webmozart\Console\Api\IO\IO;

final class ScheduleMeetupConsoleHandler
{
    /**
     * @var MeetupService
     */
    private $meetupService;

    public function __construct(MeetupService $meetupService)
    {
        $this->meetupService = $meetupService;
    }

    public function handle(Args $args, IO $io): int
    {
        $this->meetupService->scheduleMeetup(
            new ScheduleMeetup(
                (int)$args->getArgument('organizerId'),
                $args->getArgument('name'),
                $args->getArgument('description'),
                $args->getArgument('scheduledFor')
            )
        );

        $io->writeLine('<success>Scheduled the meetup successfully</success>');

        return 0;
    }
}
