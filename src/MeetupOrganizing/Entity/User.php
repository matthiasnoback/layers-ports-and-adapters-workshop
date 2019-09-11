<?php
declare(strict_types=1);

namespace MeetupOrganizing\Entity;

final class User
{
    /**
     * @var int
     */
    private $userId;

    /**
     * @var string
     */
    private $name;

    private function __construct()
    {
    }

    public static function fromDatabaseRecord(array $record): User
    {
        $user = new self();

        $user->userId = (int)$record['user_id'];
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
