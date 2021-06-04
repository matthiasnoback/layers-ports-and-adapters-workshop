<?php
declare(strict_types=1);

namespace MeetupOrganizing\Domain;

use DateTimeImmutable;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class MeetupTest extends TestCase
{
    /**
     * @test
     */
    public function name_should_not_be_empty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('name');

        Meetup::schedule(
            $this->aMeetupId(),
            $this->aUserId(),
            $emptyName = '',
            'A description',
            $this->aScheduledDate(),
            $this->currentTime()
        );
    }

    /**
     * @test
     */
    public function description_should_not_be_empty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('description');

        Meetup::schedule(
            $this->aMeetupId(),
            $this->aUserId(),
            'A name',
            $emptyDescription = '',
            $this->aScheduledDate(),
            $this->currentTime()
        );
    }

    /**
     * @test
     */
    public function the_scheduled_date_should_be_in_the_future(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('future');

        $scheduledDate = $this->aScheduledDate();

        // Today is two days after the scheduled date:
        $currentTime = $scheduledDate->toDateTimeImmutable()->modify('+2 days');

        Meetup::schedule(
            $this->aMeetupId(),
            $this->aUserId(),
            'A name',
            'A description',
            $scheduledDate,
            $currentTime
        );
    }

    private function aScheduledDate(): ScheduledDate
    {
        return ScheduledDate::fromString('2020-09-16 20:00');
    }

    private function aUserId(): UserId
    {
        return UserId::fromInt(1);
    }

    private function currentTime(): DateTimeImmutable
    {
        return $this->aScheduledDate()->toDateTimeImmutable()->modify('-2 days');
    }

    private function aMeetupId(): MeetupId
    {
        return MeetupId::fromString('0b6f0257-012d-4ca9-bc2c-7b488559dddb');
    }
}
