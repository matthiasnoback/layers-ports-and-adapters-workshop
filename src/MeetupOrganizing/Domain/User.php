<?php
declare(strict_types=1);

namespace MeetupOrganizing\Domain;

final class User
{
    private int $userId;

    private string $name;

    private function __construct()
    {
    }

    public static function fromDatabaseRecord(array $record): User
    {
        $user = new self();

        $user->userId = (int)$record['userId'];
        $user->name = $record['name'];

        return $user;
    }

    public function userId(): UserId
    {
        return UserId::fromInt($this->userId);
    }

    public function name(): string
    {
        return $this->name;
    }
}
