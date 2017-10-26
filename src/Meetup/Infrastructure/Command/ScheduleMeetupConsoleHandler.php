<?php
declare(strict_types=1);

namespace Meetup\Infrastructure\Command;

use Meetup\Domain\Model\Description;
use Meetup\Domain\Model\Meetup;
use Meetup\Infrastructure\Persistence\MeetupRepository;
use Meetup\Domain\Model\Name;
use Meetup\Domain\Model\ScheduledDate;
use Webmozart\Console\Api\Args\Args;
use Webmozart\Console\Api\IO\IO;

final class ScheduleMeetupConsoleHandler
{
    /**
     * @var \Meetup\Infrastructure\Persistence\MeetupRepository
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
