<?php
declare(strict_types=1);

namespace Meetup\Infrastructure\Notifications;

use Meetup\Application\Notify;
use Meetup\Domain\Model\MeetupScheduled;

final class NotifyMany implements Notify
{
    /**
     * @var array|Notify[]
     */
    private $notifiers;

    public function __construct(array $notifiers)
    {
        $this->notifiers = $notifiers;
    }

    public function meetupScheduled(MeetupScheduled $event): void
    {
        foreach ($this->notifiers as $notify) {
            $notify->meetupScheduled($event);
        }
    }
}
