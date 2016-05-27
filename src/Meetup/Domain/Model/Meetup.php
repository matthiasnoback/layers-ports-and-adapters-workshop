<?php

namespace Meetup\Domain\Model;

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

    public function rsvpYes(MemberId $memberId)
    {
        return new Rsvp($this->id, $memberId, Rsvp::YES);
    }

    public function id()
    {
        return $this->id;
    }

    public function name()
    {
        return $this->name;
    }

    public function description()
    {
        return $this->description;
    }

    public function scheduledFor()
    {
        return $this->scheduledFor;
    }

    public function isUpcoming(\DateTimeImmutable $now)
    {
        return $now < $this->scheduledFor;
    }
}
