<?php
declare(strict_types=1);

namespace Meetup\Infrastructure\UserInterface\Cli;

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
    private $handler;
    /**
     * @var MeetupRepository
     */
    private $repository;

    public function __construct(ScheduleMeetupHandler $scheduleMeetupHandler, MeetupRepository $meetupRepository)
    {
        $this->handler = $scheduleMeetupHandler;
        $this->repository = $meetupRepository;
    }

    public function handle(Args $args, IO $io): int
    {
        $command = new ScheduleMeetup();
        $command->id = (string)$this->repository->nextIdentity();
        $command->name = $args->getArgument('name');
        $command->description = $args->getArgument('description');
        $command->scheduledFor = $args->getArgument('scheduledFor');

        $this->handler->handle($command);

        $io->writeLine('<success>Scheduled the meetup successfully</success>');

        return 0;
    }
}
