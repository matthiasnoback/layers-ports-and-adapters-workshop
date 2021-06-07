<?php
declare(strict_types=1);

namespace MeetupOrganizing\Application;

use MeetupOrganizing\Domain\MeetupId;
use MeetupOrganizing\Domain\UserId;

final class RsvpForMeetup
{
    private int $userId;
    private string $meetupId;

    public function __construct(int $userId, string $meetupId)
    {
        $this->userId = $userId;
        $this->meetupId = $meetupId;
    }

    public function userId(): UserId
    {
        return UserId::fromInt($this->userId);
    }

    public function meetupId(): MeetupId
    {
        return MeetupId::fromString($this->meetupId);
    }
}
