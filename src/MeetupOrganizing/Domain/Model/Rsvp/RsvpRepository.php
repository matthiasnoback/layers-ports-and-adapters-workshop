<?php

namespace MeetupOrganizing\Domain\Model\Rsvp;

use MeetupOrganizing\Domain\Model\Rsvp\Rsvp;

interface RsvpRepository
{
    public function save(Rsvp $rsvp);

    /**
     * @param int $meetupId
     * @return array&Rsvp[]
     */
    public function getByMeetupId(int $meetupId): array;
}
