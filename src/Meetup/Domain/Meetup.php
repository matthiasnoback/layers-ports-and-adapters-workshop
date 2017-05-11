<?php
declare(strict_types = 1);

namespace Meetup\Domain;

final class Meetup
{
    const DATE_FORMAT = 'Y-m-d\TH:i:s,uO';

    /**
     * @var MeetupId
     */
    private $id;

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

    public static function schedule(MeetupId $id, Name $name, Description $description, \DateTimeImmutable $scheduledFor)
    {
        $meetup = new self();
        $meetup->id = $id;
        $meetup->name = $name;
        $meetup->description = $description;
        $meetup->scheduledFor = $scheduledFor->format(self::DATE_FORMAT);

        return $meetup;
    }

    public function id(): MeetupId
    {
        return $this->id;
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
