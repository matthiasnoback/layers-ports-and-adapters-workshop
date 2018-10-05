<?php
declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure\UserInterface\Cli;

use MeetupOrganizing\Domain\Model\Description;
use MeetupOrganizing\Domain\Model\Meetup;
use MeetupOrganizing\Infrastructure\Persistence\FileSystem\MeetupRepository;
use MeetupOrganizing\Domain\Model\Name;
use MeetupOrganizing\Domain\Model\ScheduledDate;
use Webmozart\Console\Api\Args\Args;
use Webmozart\Console\Api\IO\IO;

final class ScheduleMeetupConsoleHandler
{
    /**
     * @var \MeetupOrganizing\Infrastructure\Persistence\FileSystem\MeetupRepository
     */
    private $repository;

    public function __construct(MeetupRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(Args $args, IO $io): int
    {
        $meetup = Meetup::schedule(
            Name::fromString($args->getArgument('name')),
            Description::fromString($args->getArgument('description')),
            ScheduledDate::fromPhpDateString($args->getArgument('scheduledFor'))
        );
        $this->repository->add($meetup);

        $io->writeLine('<success>Scheduled the meetup successfully</success>');

        return 0;
    }
}
