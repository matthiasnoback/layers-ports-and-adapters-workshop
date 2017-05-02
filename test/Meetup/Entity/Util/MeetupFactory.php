<?php
declare(strict_types = 1);

namespace Tests\Meetup\Entity\Util;

use Meetup\Domain\Model\Description;
use Meetup\Domain\Model\Meetup;
use Meetup\Domain\Model\MeetupId;
use Meetup\Domain\Model\Name;
use Ramsey\Uuid\Uuid;

class MeetupFactory
{
    public static function pastMeetup()
    {
        return Meetup::schedule(
            MeetupId::fromString((string)Uuid::uuid4()),
            Name::fromString('Name'),
            Description::fromString('Description'),
            new \DateTimeImmutable('-5 days')
        );
    }

    public static function upcomingMeetup()
    {
        return Meetup::schedule(
            MeetupId::fromString((string)Uuid::uuid4()),
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
