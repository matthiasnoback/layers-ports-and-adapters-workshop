<?php

namespace MeetupOrganizing\Domain\Model\User;

use MeetupOrganizing\Domain\Model\User\User;
use MeetupOrganizing\Domain\Model\User\UserId;

interface UserRepository
{
    public function getById(UserId $id): User;

    /**
     * @return array&User[]
     */
    public function findAll(): array;

    public function getOrganizerId(): UserId;
}
