<?php
declare(strict_types=1);

namespace MeetupOrganizing\Application\ListMeetups;

use DateTimeImmutable;

interface ListMeetupsRepository
{
    /**
     * @param DateTimeImmutable $now
     * @return array&MeetupForList[]
     */
    public function upcomingMeetups(DateTimeImmutable $now): array;

    /**
     * @param DateTimeImmutable $now
     * @return array&MeetupForList[]
     */
    public function pastMeetups(DateTimeImmutable $now): array;
}
