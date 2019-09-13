<?php
declare(strict_types=1);

namespace MeetupOrganizing\Command;

use MeetupOrganizing\Entity\Meetup;
use MeetupOrganizing\Entity\MeetupRepository;
use MeetupOrganizing\Entity\ScheduledDate;
use MeetupOrganizing\Entity\UserId;
use Webmozart\Console\Api\Args\Args;
use Webmozart\Console\Api\IO\IO;

final class ScheduleMeetupConsoleHandler
{
    /**
     * @var MeetupRepository
     */
    private $meetupRepository;

    public function __construct(MeetupRepository $meetupRepository)
    {
        $this->meetupRepository = $meetupRepository;
    }

    public function handle(Args $args, IO $io): int
    {
        $meetup = new Meetup(
            UserId::fromInt((int)$args->getArgument('organizerId')),
            $args->getArgument('name'),
            $args->getArgument('description'),
            ScheduledDate::fromPhpDateString($args->getArgument('scheduledFor'))
        );

        $this->meetupRepository->add($meetup);

        $io->writeLine('<success>Scheduled the meetup successfully</success>');

        return 0;
    }
}
