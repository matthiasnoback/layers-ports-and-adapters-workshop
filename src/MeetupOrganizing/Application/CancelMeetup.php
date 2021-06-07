<?php
declare(strict_types=1);

namespace MeetupOrganizing\Application;

use MeetupOrganizing\Domain\MeetupId;
use MeetupOrganizing\Domain\UserId;

final class CancelMeetup
{
    private int $loggedInUserId;
    private string $meetupId;

    public function __construct(int $loggedInUserId, string $meetupId)
    {
        $this->loggedInUserId = $loggedInUserId;
        $this->meetupId = $meetupId;
    }

    public function loggedInUserId(): UserId
    {
        return UserId::fromInt($this->loggedInUserId);
    }

    public function meetupId(): MeetupId
    {
        return MeetupId::fromString($this->meetupId);
    }
}
