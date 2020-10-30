<?php
declare(strict_types=1);

namespace MeetupOrganizing\Domain;

interface RsvpRepository
{
    public function save(Rsvp $rsvp): void;

    /**
     * @return array<Rsvp> & Rsvp[]
     */
    public function getByMeetupId(int $meetupId): array;
}
