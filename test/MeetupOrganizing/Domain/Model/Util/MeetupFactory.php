<?php
declare(strict_types = 1);

namespace Tests\MeetupOrganizing\Domain\Model\Util;

use MeetupOrganizing\Domain\Model\Description;
use MeetupOrganizing\Domain\Model\Meetup;
use MeetupOrganizing\Domain\Model\MeetupId;
use MeetupOrganizing\Domain\Model\Name;
use MeetupOrganizing\Domain\Model\ScheduledDate;

class MeetupFactory
{
    public static function pastMeetup(): Meetup
    {
        return Meetup::schedule(
            MeetupId::fromString('2e9e389b-516e-4578-b087-7e4948cfe57b'),
            Name::fromString('Name'),
            Description::fromString('Description'),
            ScheduledDate::fromPhpDateString('-5 days')
        );
    }

    public static function upcomingMeetup(): Meetup
    {
        return Meetup::schedule(
            MeetupId::fromString('3fed0527-98bd-4cc0-9b29-98140509a1de'),
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
