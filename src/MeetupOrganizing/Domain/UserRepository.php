<?php

namespace MeetupOrganizing\Domain;

interface UserRepository
{
    public function getById(UserId $id): User;

    /**
     * @return array&User[]
     */
    public function findAll(): array;

    public function getOrganizerId(): UserId;
}
