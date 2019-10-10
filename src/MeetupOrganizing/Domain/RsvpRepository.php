<?php

namespace MeetupOrganizing\Domain;

interface RsvpRepository
{
    public function save(Rsvp $rsvp);

    /**
     * @param int $meetupId
     * @return array&Rsvp[]
     */
    public function getByMeetupId(int $meetupId): array;
}
