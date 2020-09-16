<?php
declare(strict_types=1);

namespace MeetupOrganizing\Domain;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class ScheduledDateTest extends TestCase
{
    /**
     * @test
     */
    public function it_normalizes_the_date_to_atom_format(): void
    {
        $scheduledDate = ScheduledDate::fromString('2017-01-01 20:00');

        $this->assertEquals(
            new DateTimeImmutable('2017-01-01 20:00'),
            $scheduledDate->toDateTimeImmutable()
        );
    }

    /**
     * @test
     */
    public function it_can_be_created_from_a_php_date_time_immutable(): void
    {
        $scheduledDate = ScheduledDate::fromDateTime(new DateTimeImmutable('2017-01-01 20:00'));

        $this->assertEquals(
            new DateTimeImmutable('2017-01-01 20:00'),
            $scheduledDate->toDateTimeImmutable()
        );
    }
}
