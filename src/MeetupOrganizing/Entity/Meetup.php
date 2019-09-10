<?php
declare(strict_types=1);

namespace MeetupOrganizing\Entity;

use DateTimeImmutable;

final class Meetup
{
    /**
     * @var int
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
     * @var ScheduledDate
     */
    private $scheduledFor;

    private function __construct()
    {
    }

    public static function schedule(Name $name, Description $description, ScheduledDate $scheduledFor): Meetup
    {
        $meetup = new self();
        $meetup->name = $name;
        $meetup->description = $description;
        $meetup->scheduledFor = $scheduledFor;

        return $meetup;
    }

    public function id(): int
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

    public function scheduledFor(): ScheduledDate
    {
        return $this->scheduledFor;
    }

    public function isUpcoming(DateTimeImmutable $now): bool
    {
        return $this->scheduledFor()->isInTheFuture($now);
    }

    /**
     * @param int $id
     * @internal Only to be used by MeetupRepository
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public static function fromDatabaseRecord(array $data): Meetup
    {
        $meetup = new self();
        $meetup->id = (int)$data['id'];
        $meetup->name = Name::fromString($data['name']);
        $meetup->description = Description::fromString($data['description']);
        $meetup->scheduledFor = ScheduledDate::fromPhpDateString($data['scheduled_for']);

        return $meetup;
    }
}
