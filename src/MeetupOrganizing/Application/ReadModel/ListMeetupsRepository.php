<?php

namespace MeetupOrganizing\Application\ReadModel;

use DateTimeImmutable;

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
