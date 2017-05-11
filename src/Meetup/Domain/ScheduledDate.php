<?php
declare(strict_types=1);

namespace Meetup\Domain;

final class ScheduledDate
{
    private const DATE_TIME_FORMAT = \DateTime::ATOM;

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
            $dateTimeImmutable = new \DateTimeImmutable($phpDateString);
        } catch (\Throwable $throwable) {
            throw new \InvalidArgumentException(
                'Invalid PHP date time format',
                null,
                $throwable
            );
        }

        return self::fromDateTime($dateTimeImmutable);
    }

    public static function fromDateTime(\DateTimeImmutable $dateTime): ScheduledDate
    {
        return new self($dateTime->format(self::DATE_TIME_FORMAT));
    }

    public function __toString()
    {
        return $this->dateTime;
    }
}
