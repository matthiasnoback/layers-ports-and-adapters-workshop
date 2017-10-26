<?php
declare(strict_types = 1);

namespace Tests\Unit\Meetup\Domain\Model;

use Meetup\Domain\Model\Meetup;
use Meetup\Domain\Model\MeetupId;
use Meetup\Domain\Model\Name;
use Meetup\Domain\Model\Description;
use Meetup\Domain\Model\ScheduledDate;
use Ramsey\Uuid\Uuid;

final class MeetupTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_can_be_scheduled_with_just_a_name_description_and_date()
    {
        $meetupId = MeetupId::fromString('7d7fd0b2-0cb5-42ac-b697-3f7bfce24df9');
        $name = Name::fromString('Name');
        $description = Description::fromString('Description');
        $scheduledFor = ScheduledDate::fromPhpDateString('now');

        $meetup = Meetup::schedule($meetupId, $name, $description, $scheduledFor);

        $this->assertInstanceOf(Meetup::class, $meetup);
        $this->assertEquals($meetupId, $meetup->meetupId());
        $this->assertEquals($name, $meetup->name());
        $this->assertEquals($description, $meetup->description());
        $this->assertEquals($scheduledFor, $meetup->scheduledFor());
    }

    /**
     * @test
     */
    public function can_determine_whether_or_not_it_is_upcoming()
    {
        $now = new \DateTimeImmutable();

        $pastMeetup = Meetup::schedule(
            MeetupId::fromString('3a021c08-ad15-43aa-aba3-8626fecd39a7'),
            Name::fromString('Name'),
            Description::fromString('Description'),
            ScheduledDate::fromPhpDateString('-5 days')
        );
        $this->assertFalse($pastMeetup->isUpcoming($now));

        $upcomingMeetup = Meetup::schedule(
            MeetupId::fromString('7d7fd0b2-0cb5-42ac-b697-3f7bfce24df9'),
            Name::fromString('Name'),
            Description::fromString('Description'),
            ScheduledDate::fromPhpDateString('+5 days')
        );
        $this->assertTrue($upcomingMeetup->isUpcoming($now));
    }
}
