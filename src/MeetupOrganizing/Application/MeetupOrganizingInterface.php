<?php
declare(strict_types=1);

namespace MeetupOrganizing\Application;

use MeetupOrganizing\Domain\MeetupId;

interface MeetupOrganizingInterface
{
    public function scheduleMeetup(ScheduleMeetup $command): MeetupId;

    /**
     * @return array<MeetupForList>
     */
    public function listUpcomingMeetups(): array;

    public function rsvpForMeetup(RsvpForMeetup $command): void;

    public function cancelMeetup(CancelMeetup $command): void;

    /**
     * @return array<MeetupForList>
     */
    public function listPastMeetups(): array;
}
