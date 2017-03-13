<?php
declare(strict_types = 1);

namespace Meetup\Entity;

final class Meetup
{
    /**
     * @var MeetupId
     */
    private $id;

    /**
     * @var Name
     */
    private $name;

    /**
     * @var Description string
     */
    private $description;

    /**
     * @var \DateTimeImmutable
     */
    private $scheduledFor;

    public static function schedule(MeetupId $id, Name $name, Description $description, \DateTimeImmutable $scheduledFor)
    {
        $meetup = new self();
        $meetup->id = $id;
        $meetup->name = $name;
        $meetup->description = $description;
        $meetup->scheduledFor = $scheduledFor;

        return $meetup;
    }

    public function rsvpYes(MemberId $memberId): Rsvp
    {
        return new Rsvp($this->id, $memberId, Rsvp::YES);
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
        return $this->scheduledFor;
    }

    public function isUpcoming(\DateTimeImmutable $now): bool
    {
        return $now < $this->scheduledFor;
    }
}
