<?php
declare(strict_types=1);

namespace MeetupOrganizing\Domain;

final class MeetupWasScheduled
{
    private MeetupId $meetupId;

    public function __construct(MeetupId $meetupId)
    {
        $this->meetupId = $meetupId;
    }

    public function meetupId(): MeetupId
    {
        return $this->meetupId;
    }
}
