<?php

namespace Tests\Unit\Meetup\Domain\Model\Util;

use Meetup\Entity\Description;
use Meetup\Entity\Meetup;
use Meetup\Entity\MeetupId;
use Meetup\Entity\Name;

class MeetupFactory
{
    public static function pastMeetup()
    {
        return Meetup::schedule(
            MeetupId::fromString('some id'),
            Name::fromString('Name'),
            Description::fromString('Description'),
            new \DateTimeImmutable('-5 days')
        );
    }

    public static function upcomingMeetup(MeetupId $meetupId = null)
    {
        return Meetup::schedule(
            $meetupId ?: MeetupId::fromString('some id'),
            Name::fromString('Name'),
            Description::fromString('Description'),
            new \DateTimeImmutable('+5 days')
        );
    }

    public static function someMeetup(MeetupId $meetupId = null)
    {
        return self::upcomingMeetup($meetupId);
    }

    public static function someMeetupWithId(MeetupId $meetupId)
    {
        return self::someMeetup($meetupId);
    }
}
