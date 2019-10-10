<?php

namespace MeetupOrganizing\Domain;

use DateTimeImmutable;
use MeetupOrganizing\Application\Meetup;

interface ListMeetupsRepository
{
    /**
     * @param DateTimeImmutable $now
     * @return array&Meetup[]
     */
    public function upcomingMeetups(DateTimeImmutable $now): array;

    /**
     * @param DateTimeImmutable $now
     * @return array&Meetup[]
     */
    public function pastMeetups(DateTimeImmutable $now): array;
}
