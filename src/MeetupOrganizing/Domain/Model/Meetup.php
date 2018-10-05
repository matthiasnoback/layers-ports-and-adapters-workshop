<?php
declare(strict_types=1);

namespace MeetupOrganizing\Domain\Model;

final class Meetup
{
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
     * @var ScheduledDate
     */
    private $scheduledFor;

    public static function schedule(
        MeetupId $meetupId,
        Name $name,
        Description $description,
        ScheduledDate $scheduledFor
    ): Meetup {
        $meetup = new self();
        $meetup->meetupId = $meetupId;
        $meetup->name = $name;
        $meetup->description = $description;
        $meetup->scheduledFor = $scheduledFor;

        return $meetup;
    }

    public function id(): string
    {
        return (string)$this->meetupId;
    }

    public function name(): Name
    {
        return $this->name;
    }

    public function description(): Description
    {
        return $this->description;
    }

    public function scheduledFor(): ScheduledDate
    {
        return $this->scheduledFor;
    }

    public function isUpcoming(\DateTimeImmutable $now): bool
    {
        return $this->scheduledFor()->isInTheFuture($now);
    }
}
