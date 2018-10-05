<?php
declare(strict_types = 1);

namespace MeetupOrganizing\Entity;

final class MeetupIdTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_can_be_constructed_from_a_string_and_reverted_back_to_it(): void
    {
        $id = '7d7fd0b2-0cb5-42ac-b697-3f7bfce24df9';
        $meetupId = MeetupId::fromString($id);
        $this->assertSame($id, (string)$meetupId);
    }

    /**
     * @test
     */
    public function it_can_be_compared_to_another_meetup_id(): void
    {
        $meetupId1 = MeetupId::fromString('3a021c08-ad15-43aa-aba3-8626fecd39a7');
        $meetupId2 = MeetupId::fromString('3a021c08-ad15-43aa-aba3-8626fecd39a7');
        $this->assertTrue($meetupId1->equals($meetupId2));
    }
}
