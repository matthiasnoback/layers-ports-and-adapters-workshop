<?php
declare(strict_types=1);

namespace MeetupOrganizing\Entity;

use RuntimeException;

final class UserRepository
{
    private $records = [
        1 => [
            'id' => 1,
            'name' => 'Organizer'
        ],
        2 => [
            'id' => 2,
            'name' => 'Regular user'
        ]
    ];

    public function getById(UserId $id): User
    {
        if (!isset($this->records[$id->asInt()])) {
            throw new RuntimeException('User not found');
        }

        return User::fromDatabaseRecord($this->records[$id->asInt()]);
    }
}
