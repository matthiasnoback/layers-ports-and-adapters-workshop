<?php
declare(strict_types=1);

namespace MeetupOrganizing\Domain\Model\User;

interface UserRepository
{
    public function getById(UserId $id): User;

    /**
     * @return array<User> & User[]
     */
    public function findAll(): array;

    public function getOrganizerId(): UserId;
}
