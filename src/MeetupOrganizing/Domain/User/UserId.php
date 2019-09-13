<?php
declare(strict_types=1);

namespace MeetupOrganizing\Domain\User;

final class UserId
{
    /**
     * @var int
     */
    private $id;

    private function __construct(int $id)
    {
        $this->id = $id;
    }

    public static function fromInt(int $id)
    {
        return new self($id);
    }

    public function asInt(): int
    {
        return $this->id;
    }
}
