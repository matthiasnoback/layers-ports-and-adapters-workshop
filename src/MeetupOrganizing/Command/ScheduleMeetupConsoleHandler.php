<?php
declare(strict_types=1);

namespace MeetupOrganizing\Command;

use MeetupOrganizing\Entity\Description;
use MeetupOrganizing\Entity\Meetup;
use MeetupOrganizing\Entity\MeetupRepository;
use MeetupOrganizing\Entity\Name;
use MeetupOrganizing\Entity\ScheduledDate;
use Webmozart\Console\Api\Args\Args;
use Webmozart\Console\Api\IO\IO;

final class ScheduleMeetupConsoleHandler
{
    /**
     * @var MeetupRepository
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
