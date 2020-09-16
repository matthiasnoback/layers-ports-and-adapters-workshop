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

        new Meetup(
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

        new Meetup(
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
    public function meetup_should_be_in_the_future(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('future');

        new Meetup(
            $this->aUserId(),
            'A name',
            'A description',
            $this->aScheduledDate(),
            $inThePast = $this->aScheduledDate()->toDateTimeImmutable()->modify('+2 days')
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
}
