<?php
declare(strict_types=1);

namespace MeetupOrganizing\Entity;

use DateTimeImmutable;
use InvalidArgumentException;
use Throwable;

final class ScheduledDate
{
    const DATE_TIME_FORMAT = 'Y-m-d H:i:s';

    /**
     * @var string
     */
    private $dateTime;

    private function __construct(string $dateTime)
    {
        $this->dateTime = $dateTime;
    }

    public static function fromPhpDateString(string $phpDateString): ScheduledDate
    {
        try {
            $dateTimeImmutable = new DateTimeImmutable($phpDateString);
        } catch (Throwable $throwable) {
            throw new InvalidArgumentException(
                'Invalid PHP date time format',
                0,
                $throwable
            );
        }

        return self::fromDateTime($dateTimeImmutable);
    }

    public static function fromDateTime(DateTimeImmutable $dateTime): ScheduledDate
    {
        return new self($dateTime->format(self::DATE_TIME_FORMAT));
    }

    public function asString(): string
    {
        return $this->dateTime;
    }

    public function isInTheFuture(DateTimeImmutable $now): bool
    {
        return $now < $this->toDateTimeImmutable();
    }

    public function toDateTimeImmutable(): DateTimeImmutable
    {
        return DateTimeImmutable::createFromFormat(self::DATE_TIME_FORMAT, $this->dateTime);
    }
}
