<?php
declare(strict_types = 1);

namespace Tests\Unit\MeetupManagement\Domain\Model\Meetup;

use Meetup\Domain\Model\Meetup;
use Meetup\Domain\Model\MeetupId;
use Meetup\Domain\Model\Name;
use Meetup\Domain\Model\Description;

final class MeetupTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_can_be_scheduled_with_just_a_name_description_and_date()
    {
        $meetupId = MeetupId::fromString('eab821b5-55bf-4ea9-9206-a0d768cf5133');
        $name = Name::fromString('Name');
        $description = Description::fromString('Description');
        $scheduledFor = new \DateTimeImmutable('now');

        $meetup = Meetup::schedule($meetupId, $name, $description, $scheduledFor);

        $this->assertInstanceOf(Meetup::class, $meetup);
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
            MeetupId::fromString('c52f6127-3664-4ad8-8adb-151c532cb7cf'),
            Name::fromString('Name'),
            Description::fromString('Description'),
            new \DateTimeImmutable('-5 days')
        );
        $this->assertFalse($pastMeetup->isUpcoming($now));

        $upcomingMeetup = Meetup::schedule(
            MeetupId::fromString('75008ae0-19d4-4fd9-a854-d3f875bf4ae4'),
            Name::fromString('Name'),
            Description::fromString('Description'),
            new \DateTimeImmutable('+5 days')
        );
        $this->assertTrue($upcomingMeetup->isUpcoming($now));
    }
}
