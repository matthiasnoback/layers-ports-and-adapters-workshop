<?php
declare(strict_types=1);

namespace Meetup\Domain\Model;

final class MeetupScheduled
{
    /**
     * @var MeetupId
     */
    private $meetupId;

    public function __construct(MeetupId $meetupId)
    {
        $this->meetupId = $meetupId;
    }

    public function meetupId()
    {
        return $this->meetupId;
    }
}
