<?php
declare(strict_types=1);

namespace MeetupOrganizing\Entity;

use DateTimeImmutable;

final class Meetup
{
    /**
     * @var int
     */
    private $meetupId;

    /**
     * @var UserId
     */
    private $organizerId;

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

    private function __construct()
    {
    }

    public static function schedule(UserId $organizerId, Name $name, Description $description, ScheduledDate $scheduledFor): Meetup
    {
        $meetup = new self();
        $meetup->organizerId = $organizerId;
        $meetup->name = $name;
        $meetup->description = $description;
        $meetup->scheduledFor = $scheduledFor;

        return $meetup;
    }

    public function meetupId(): int
    {
        return $this->meetupId;
    }

    public function organizerId(): UserId
    {
        return $this->organizerId;
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

    public function isUpcoming(DateTimeImmutable $now): bool
    {
        return $this->scheduledFor()->isInTheFuture($now);
    }

    /**
     * @param int $meetupId
     * @internal Only to be used by MeetupRepository
     */
    public function setMeetupId(int $meetupId): void
    {
        $this->meetupId = $meetupId;
    }

    public static function fromDatabaseRecord(array $data): Meetup
    {
        $meetup = new self();
        $meetup->meetupId = (int)$data['meetup_id'];
        $meetup->organizerId = UserId::fromInt((int)$data['organizer_id']);
        $meetup->name = Name::fromString($data['name']);
        $meetup->description = Description::fromString($data['description']);
        $meetup->scheduledFor = ScheduledDate::fromPhpDateString($data['scheduled_for']);

        return $meetup;
    }
}
