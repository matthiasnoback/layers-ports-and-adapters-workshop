<?php
declare(strict_types=1);

namespace MeetupOrganizing\Entity;

final class ScheduledDateTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_normalizes_the_date_to_atom_format(): void
    {
        $scheduledDate = ScheduledDate::fromPhpDateString('2017-01-01 20:00');

        $this->assertEquals(
            new \DateTimeImmutable('2017-01-01 20:00'),
            $scheduledDate->toDateTimeImmutable()
        );
    }

    /**
     * @test
     */
    public function it_can_be_created_from_a_php_date_time_string(): void
    {
        $scheduledDate = ScheduledDate::fromPhpDateString('+1 day');

        $this->assertTrue($scheduledDate->isInTheFuture(new \DateTimeImmutable('now')));
    }

    /**
     * @test
     */
    public function it_knows_when_a_date_is_in_the_past(): void
    {
        $scheduledDate = ScheduledDate::fromPhpDateString('-1 day');

        $this->assertFalse($scheduledDate->isInTheFuture(new \DateTimeImmutable('now')));
    }

    /**
     * @test
     */
    public function it_can_be_created_from_a_php_date_time_immutable(): void
    {
        $scheduledDate = ScheduledDate::fromDateTime(new \DateTimeImmutable('2017-01-01 20:00'));

        $this->assertEquals(
            new \DateTimeImmutable('2017-01-01 20:00'),
            $scheduledDate->toDateTimeImmutable()
        );
    }
}
