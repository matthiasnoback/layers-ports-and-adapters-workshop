<?php
declare(strict_types=1);

namespace MeetupOrganizing\ReadModel;

use DateTimeImmutable;

interface ListMeetupsRepository
{
    /**
     * @return array<MeetupForList> &MeetupForList[]
     */
    public function upcomingMeetups(DateTimeImmutable $now): array;

    /**
     * @return array<MeetupForList> & MeetupForList[]
     */
    public function pastMeetups(DateTimeImmutable $now): array;
}
