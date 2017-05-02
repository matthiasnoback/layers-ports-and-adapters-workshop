<?php
declare(strict_types = 1);

namespace Tests\Unit\MeetupManagement\Domain\Model\Meetup\Util;

use Meetup\Domain\Model\Description;
use Meetup\Domain\Model\Meetup;
use Meetup\Domain\Model\MeetupId;
use Meetup\Domain\Model\Name;

class MeetupFactory
{
    public static function pastMeetup()
    {
        return Meetup::schedule(
            MeetupId::fromString('4ab4d7e0-c6c9-4d51-8c64-2883bac1e6c7'),
            Name::fromString('Name'),
            Description::fromString('Description'),
            new \DateTimeImmutable('-5 days')
        );
    }

    public static function upcomingMeetup()
    {
        return Meetup::schedule(
            MeetupId::fromString('5a79191c-ff82-4ccb-b292-2728f26bb553'),
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
