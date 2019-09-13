<?php

namespace MeetupOrganizing\Domain\User;

use MeetupOrganizing\Domain\User\User;
use MeetupOrganizing\Domain\User\UserId;

interface UserRepository
{
    public function getById(UserId $id): User;

    /**
     * @return array&User[]
     */
    public function findAll(): array;

    public function getOrganizerId(): UserId;
}
