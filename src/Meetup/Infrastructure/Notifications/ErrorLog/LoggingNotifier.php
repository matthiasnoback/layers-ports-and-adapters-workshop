<?php
declare(strict_types=1);

namespace Meetup\Infrastructure\Notifications\ErrorLog;

use Meetup\Application\Notify;
use Meetup\Domain\Model\Description;
use Meetup\Domain\Model\MeetupId;
use Meetup\Domain\Model\Name;

final class LoggingNotifier implements Notify
{
    public function meetupScheduled(
        MeetupId $meetupId,
        Name $name,
        Description $description,
        \DateTimeImmutable $scheduledFor
    ): void {
        error_log(
            sprintf(
                'Notification: meetup scheduled for %s: %s',
                $name,
                $scheduledFor->format(\DateTime::ATOM)
            )
        );
    }
}
