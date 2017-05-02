<?php
declare(strict_types=1);

namespace Meetup\Infrastructure\Notifications;

use Meetup\Application\Notify;
use Meetup\Domain\Model\MeetupScheduled;
use NaiveSerializer\Serializer;

final class LogNotifications implements Notify
{
    public function meetupScheduled(MeetupScheduled $event): void
    {
        error_log('MeetupScheduled notification: ' . Serializer::serialize($event));
    }
}
