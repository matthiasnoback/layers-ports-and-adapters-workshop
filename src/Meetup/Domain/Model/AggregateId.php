<?php

namespace Meetup\Domain\Model;

use Assert\Assertion;

abstract class AggregateId
{
    private $id;

    private function __construct()
    {
    }

    public static function fromString($id)
    {
        Assertion::string($id);
        Assertion::notEmpty($id);

        $meetupId = new static();
        $meetupId->id = $id;

        return $meetupId;
    }

    public function __toString()
    {
        return $this->id;
    }

    public function equals(MeetupId $otherMeetupId)
    {
        return (string)$this === (string)$otherMeetupId;
    }
}
