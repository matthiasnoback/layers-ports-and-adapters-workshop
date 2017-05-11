<?php
declare(strict_types = 1);

namespace Tests\Meetup\Domain\Util;

use Meetup\Domain\Description;
use Meetup\Domain\Meetup;
use Meetup\Domain\MeetupId;
use Meetup\Domain\Name;

class MeetupFactory
{
    public static function pastMeetup()
    {
        return Meetup::schedule(
            MeetupId::fromString('03e5efff-12e8-4903-ba24-f2e80e118c90'),
            Name::fromString('Name'),
            Description::fromString('Description'),
            new \DateTimeImmutable('-5 days')
        );
    }

    public static function upcomingMeetup()
    {
        return Meetup::schedule(
            MeetupId::fromString('4f4a3f3e-eecb-4dc1-bc73-49f64ca7d0b1'),
            Name::fromString('Name'),
            Description::fromString('Description'),
            new \DateTimeImmutable('+5 days')
        );
    }

    public static function someMeetup()
    {
        return self::upcomingMeetup();
    }
}
