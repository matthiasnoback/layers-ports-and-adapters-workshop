<?php
declare(strict_types=1);

namespace MeetupOrganizing\Entity;

final class User
{
    /**
     * @var int
     */
    private $id;

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

        $user->id = (int)$record['id'];
        $user->name = $record['name'];

        return $user;
    }

    public function id(): UserId
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }
}
