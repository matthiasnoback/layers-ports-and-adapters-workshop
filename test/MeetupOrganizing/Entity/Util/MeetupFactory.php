<?php
declare(strict_types = 1);

namespace MeetupOrganizing\Entity\Util;

use MeetupOrganizing\Entity\Description;
use MeetupOrganizing\Entity\Meetup;
use MeetupOrganizing\Entity\Name;
use MeetupOrganizing\Entity\ScheduledDate;
use MeetupOrganizing\Entity\UserId;

class MeetupFactory
{
    public static function pastMeetup(): Meetup
    {
        return Meetup::schedule(
            UserId::fromInt(1),
            Name::fromString('Name'),
            Description::fromString('Description'),
            ScheduledDate::fromPhpDateString('-5 days')
        );
    }

    public static function upcomingMeetup(): Meetup
    {
        return Meetup::schedule(
            UserId::fromInt(1),
            Name::fromString('Name'),
            Description::fromString('Description'),
            ScheduledDate::fromPhpDateString('+5 days')
        );
    }

    public static function someMeetup(): Meetup
    {
        return self::upcomingMeetup();
    }
}
