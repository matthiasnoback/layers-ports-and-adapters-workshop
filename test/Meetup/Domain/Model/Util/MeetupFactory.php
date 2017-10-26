<?php
declare(strict_types = 1);

namespace Tests\Meetup\Domain\Model\Util;

use Meetup\Domain\Model\Description;
use Meetup\Domain\Model\Meetup;
use Meetup\Domain\Model\MeetupId;
use Meetup\Domain\Model\Name;
use Meetup\Domain\Model\ScheduledDate;

class MeetupFactory
{
    public static function pastMeetup()
    {
        return Meetup::schedule(
            MeetupId::fromString('bdf17823-86c1-4c6f-a81d-19f15664798f'),
            Name::fromString('Name'),
            Description::fromString('Description'),
            ScheduledDate::fromPhpDateString('-5 days')
        );
    }

    public static function upcomingMeetup()
    {
        return Meetup::schedule(
            MeetupId::fromString('6eaaa006-6034-4f4a-946e-8fe81ed73196'),
            Name::fromString('Name'),
            Description::fromString('Description'),
            ScheduledDate::fromPhpDateString('+5 days')
        );
    }

    public static function someMeetup()
    {
        return self::upcomingMeetup();
    }
}
