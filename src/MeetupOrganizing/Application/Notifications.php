<?php
declare(strict_types=1);

namespace MeetupOrganizing\Application;

use MeetupOrganizing\Domain\MeetupId;
use MeetupOrganizing\Domain\User;
use MeetupOrganizing\Domain\UserId;

interface Notifications
{
    public function sendRsvpConfirmationMail(User $user): void;

    public function sendMeetupCancellationMail(User $user): void;
}
