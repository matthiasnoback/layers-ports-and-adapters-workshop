<?php
declare(strict_types=1);

namespace MeetupOrganizing\Application;

use MeetupOrganizing\Domain\Model\Description;
use MeetupOrganizing\Domain\Model\Meetup;
use MeetupOrganizing\Domain\Model\Name;
use MeetupOrganizing\Domain\Model\ScheduledDate;
use MeetupOrganizing\Infrastructure\Persistence\FileSystem\MeetupRepository;

final class ScheduleMeetupHandler
{
    /**
     * @var MeetupRepository
     */
    private $meetupRepository;

    public function __construct(MeetupRepository $meetupRepository)
    {
        $this->meetupRepository = $meetupRepository;
    }

    public function handle(ScheduleMeetup $scheduleMeetup): Meetup
    {
        $meetup = Meetup::schedule(
            Name::fromString($scheduleMeetup->name),
            Description::fromString($scheduleMeetup->description),
            ScheduledDate::fromPhpDateString($scheduleMeetup->scheduledFor)
        );

        $this->meetupRepository->add($meetup);

        return $meetup;
    }
}
