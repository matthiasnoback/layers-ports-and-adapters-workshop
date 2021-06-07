<?php
declare(strict_types=1);

namespace MeetupOrganizing\Application;

interface Container
{
    public function meetupOrganizing(): MeetupOrganizingInterface;
}
