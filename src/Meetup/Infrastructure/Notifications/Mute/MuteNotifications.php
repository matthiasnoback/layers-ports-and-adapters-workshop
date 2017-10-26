<?php
declare(strict_types=1);

namespace Meetup\Infrastructure\Notifications\Mute;

use Meetup\Application\Notify;
use Meetup\Domain\Model\MeetupScheduled;

final class MuteNotifications implements Notify
{
    public function meetupScheduled(MeetupScheduled $event): void
    {
    }
}
