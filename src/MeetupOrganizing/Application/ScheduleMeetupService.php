<?php
declare(strict_types=1);

namespace MeetupOrganizing\Application;

use MeetupOrganizing\Domain\Model\Description;
use MeetupOrganizing\Domain\Model\Meetup;
use MeetupOrganizing\Domain\Model\Name;
use MeetupOrganizing\Domain\Model\ScheduledDate;
use MeetupOrganizing\Infrastructure\Persistence\Filesystem\MeetupRepository;

final class ScheduleMeetupService
{
    /**
     * @var MeetupRepository
     */
    private $meetupRepository;

    public function __construct(MeetupRepository $meetupRepository)
    {
        $this->meetupRepository = $meetupRepository;
    }

    public function handle(ScheduleMeetup $command): Meetup
    {
        $meetup = Meetup::schedule(
            Name::fromString($command->name),
            Description::fromString($command->description),
            ScheduledDate::fromPhpDateString($command->scheduledFor)
        );

        $this->meetupRepository->add($meetup);

        return $meetup;
    }
}
