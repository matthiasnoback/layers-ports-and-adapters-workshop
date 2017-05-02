<?php
declare(strict_types=1);

namespace Meetup\Infrastructure\UI\CLI;

use Meetup\Application\ScheduleMeetup;
use Meetup\Application\ScheduleMeetupHandler;
use Meetup\Domain\Model\MeetupRepository;
use Webmozart\Console\Api\Args\Args;
use Webmozart\Console\Api\IO\IO;

final class ScheduleMeetupConsoleHandler
{
    /**
     * @var ScheduleMeetupHandler
     */
    private $scheduleMeetupHandler;

    /**
     * @var MeetupRepository
     */
    private $meetupRepository;

    public function __construct(ScheduleMeetupHandler $scheduleMeetupHandler, MeetupRepository $meetupRepository)
    {
        $this->scheduleMeetupHandler = $scheduleMeetupHandler;
        $this->meetupRepository = $meetupRepository;
    }

    public function handle(Args $args, IO $io): int
    {
        $command = new ScheduleMeetup();
        $command->id = (string)$this->meetupRepository->nextIdentity();
        $command->name = $args->getArgument('name');
        $command->description = $args->getArgument('description');
        $command->scheduledFor = $args->getArgument('scheduledFor');

        $this->scheduleMeetupHandler->handle($command);

        $io->writeLine('<success>Scheduled the meetup successfully</success>');

        return 0;
    }
}
