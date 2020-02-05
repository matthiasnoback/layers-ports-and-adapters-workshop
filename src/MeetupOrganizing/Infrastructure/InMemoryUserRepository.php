<?php
declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure;

use MeetupOrganizing\Domain\Model\User\User;
use MeetupOrganizing\Domain\Model\User\UserId;
use MeetupOrganizing\Domain\Model\User\UserRepository;
use RuntimeException;

final class InMemoryUserRepository implements UserRepository
{
    const ORGANIZER_ID = 1;
    const REGULAR_USER_ID = 2;

    private $records = [
        self::ORGANIZER_ID => [
            'userId' => self::ORGANIZER_ID,
            'name' => 'Organizer'
        ],
        self::REGULAR_USER_ID => [
            'userId' => self::REGULAR_USER_ID,
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

    public function findAll(): array
    {
        return array_map(
            function (array $record) {
                return User::fromDatabaseRecord($record);
            },
            $this->records);
    }

    public function getOrganizerId(): UserId
    {
        return UserId::fromInt(self::ORGANIZER_ID);
    }
}
