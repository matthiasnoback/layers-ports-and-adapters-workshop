<?php
declare(strict_types = 1);

namespace Meetup\Domain\Model;

final class Meetup
{
    const DATE_FORMAT = 'Y-m-d\TH:i:s,uO';

    /**
     * @var MeetupId
     */
    private $meetupId;

    /**
     * @var Name
     */
    private $name;

    /**
     * @var Description
     */
    private $description;

    /**
     * @var string
     */
    private $scheduledFor;

    public static function schedule(MeetupId $meetupId, Name $name, Description $description, \DateTimeImmutable $scheduledFor)
    {
        $meetup = new self();
        $meetup->meetupId = $meetupId;
        $meetup->name = $name;
        $meetup->description = $description;
        $meetup->scheduledFor = $scheduledFor->format(self::DATE_FORMAT);

        return $meetup;
    }

    public function meetupId(): MeetupId
    {
        return $this->meetupId;
    }

    public function name(): Name
    {
        return $this->name;
    }

    public function description(): Description
    {
        return $this->description;
    }

    public function scheduledFor(): \DateTimeImmutable
    {
        return \DateTimeImmutable::createFromFormat(self::DATE_FORMAT, $this->scheduledFor);
    }

    public function isUpcoming(\DateTimeImmutable $now): bool
    {
        return $now < $this->scheduledFor();
    }
}
