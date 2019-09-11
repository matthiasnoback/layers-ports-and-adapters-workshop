<?php
declare(strict_types=1);

namespace MeetupOrganizing\Entity;

use RuntimeException;

final class UserRepository
{
    private $records = [
        1 => [
            'user_id' => 1,
            'name' => 'Organizer'
        ],
        2 => [
            'user_id' => 2,
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

    /**
     * @return array&User[]
     */
    public function findAll(): array
    {
        return array_map(function (array $record) { return User::fromDatabaseRecord($record); }, $this->records);
    }
}
