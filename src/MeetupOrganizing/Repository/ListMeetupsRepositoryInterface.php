<?php
declare(strict_types=1);

namespace MeetupOrganizing\Repository;

interface ListMeetupsRepositoryInterface
{
    /**
     * @return array<int,MeetupForList>
     */
    public function listUpcomingMeetups(): array;

    /**
     * @return array<int,MeetupForList>
     */
    public function listPastMeetups(): array;
}
