<?php
declare(strict_types=1);

namespace MeetupOrganizing\Application;

use MeetupOrganizing\Application\MeetupForList;

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
