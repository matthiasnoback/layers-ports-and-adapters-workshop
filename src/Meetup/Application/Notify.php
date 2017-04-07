<?php
declare(strict_types=1);

namespace Meetup\Application;

use Meetup\Domain\Model\Description;
use Meetup\Domain\Model\MeetupId;
use Meetup\Domain\Model\Name;

interface Notify
{
    public function meetupScheduled(
        MeetupId $meetupId,
        Name $name,
        Description $description,
        \DateTimeImmutable $scheduledFor
    ): void;
}
