<?php
declare(strict_types=1);

namespace MeetupOrganizing\Application;

use MeetupOrganizing\Domain\Model\Description;
use MeetupOrganizing\Domain\Model\Meetup;
use MeetupOrganizing\Domain\Model\MeetupId;
use MeetupOrganizing\Domain\Model\Name;
use MeetupOrganizing\Domain\Model\ScheduledDate;
use MeetupOrganizing\Domain\Model\MeetupRepository;

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

    public function handle(ScheduleMeetup $scheduleMeetup): MeetupId
    {
        $meetupId = $this->meetupRepository->nextIdentity();
        $meetup = Meetup::schedule(
            $meetupId,
            Name::fromString($scheduleMeetup->name),
            Description::fromString($scheduleMeetup->description),
            ScheduledDate::fromPhpDateString($scheduleMeetup->scheduledFor)
        );

        $this->meetupRepository->add($meetup);

        return $meetupId;
    }
}
