<?php
declare(strict_types=1);

namespace MeetupOrganizing\Domain;

interface RsvpRepository
{
    public function save(Rsvp $rsvp): void;

    /**
     * @return array<Rsvp>
     */
    public function getByMeetupId(string $meetupId): array;
}
