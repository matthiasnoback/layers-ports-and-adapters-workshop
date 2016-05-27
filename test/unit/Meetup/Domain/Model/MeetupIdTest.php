<?php

namespace Tests\Unit\Meetup\Domain\Model;

use Meetup\Domain\Model\MeetupId;

class MeetupIdTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_can_be_constructed_from_a_string_and_reverted_back_to_it()
    {
        $id = 'the ID';
        $meetupId = MeetupId::fromString($id);
        $this->assertSame($id, (string)$meetupId);
    }

    /**
     * @test
     */
    public function it_can_be_compared_to_another_meetup_id()
    {
        $meetupId1 = MeetupId::fromString('id');
        $meetupId2 = MeetupId::fromString('id');
        $this->assertTrue($meetupId1->equals($meetupId2));
    }
}
