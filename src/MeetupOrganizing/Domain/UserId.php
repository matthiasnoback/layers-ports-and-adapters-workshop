<?php
declare(strict_types=1);

namespace MeetupOrganizing\Domain;

final class UserId
{
    private int $id;

    private function __construct(int $id)
    {
        $this->id = $id;
    }

    public static function fromInt(int $id): self
    {
        return new self($id);
    }

    public function asInt(): int
    {
        return $this->id;
    }
}
