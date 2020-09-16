<?php
declare(strict_types = 1);

namespace MeetupOrganizing\Domain;

use Assert\Assertion;

trait AggregateId
{
    private string $id;

    private function __construct(string $id)
    {
        Assertion::notEmpty($id);
        Assertion::uuid($id);

        $this->id = $id;
    }

    /**
     * @return static
     */
    public static function fromString(string $id): self
    {
        return new self($id);
    }

    public function asString(): string
    {
        return $this->id;
    }
}
