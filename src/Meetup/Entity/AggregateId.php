<?php
declare(strict_types = 1);

namespace Meetup\Entity;

use Assert\Assertion;

trait AggregateId
{
    private $id;

    private function __construct()
    {
    }

    public static function fromString($id)
    {
        Assertion::string($id);
        Assertion::notEmpty($id);

        $aggregateId = new static();
        $aggregateId->id = $id;

        return $aggregateId;
    }

    public function __toString(): string
    {
        return $this->id;
    }

    public function equals($otherAggregateId): bool
    {
        return get_class($otherAggregateId) === get_class($this)
            && (string)$this === (string)$otherAggregateId;
    }
}
