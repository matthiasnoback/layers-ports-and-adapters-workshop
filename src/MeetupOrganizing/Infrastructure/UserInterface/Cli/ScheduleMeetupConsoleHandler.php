<?php
declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure\UserInterface\Cli;

use MeetupOrganizing\Application\ScheduleMeetup;
use MeetupOrganizing\Application\ScheduleMeetupService;
use Webmozart\Console\Api\Args\Args;
use Webmozart\Console\Api\IO\IO;

final class ScheduleMeetupConsoleHandler
{
    /**
     * @var ScheduleMeetupService
     */
    private $scheduleMeetupService;

    public function __construct(ScheduleMeetupService $scheduleMeetupService)
    {
        $this->scheduleMeetupService = $scheduleMeetupService;
    }

    public function handle(Args $args, IO $io): int
    {
        $command = new ScheduleMeetup();
        $command->name = $args->getArgument('name');
        $command->description = $args->getArgument('description');
        $command->scheduledFor = $args->getArgument('scheduledFor');

        $this->scheduleMeetupService->handle($command);

        $io->writeLine('<success>Scheduled the meetup successfully</success>');

        return 0;
    }
}
