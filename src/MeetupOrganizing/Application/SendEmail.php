<?php
declare(strict_types=1);

namespace MeetupOrganizing\Application;

use MeetupOrganizing\Domain\MeetupWasCancelled;
use MeetupOrganizing\Domain\RsvpRepository;
use MeetupOrganizing\Domain\UserHasRsvpd;
use MeetupOrganizing\Domain\UserRepository;

final class SendEmail
{
    private UserRepository $userRepository;
    private Notifications $notifications;
    private RsvpRepository $rsvpRepository;

    public function __construct(UserRepository $userRepository, Notifications $notifications, RsvpRepository $rsvpRepository)
    {
        $this->userRepository = $userRepository;
        $this->notifications = $notifications;
        $this->rsvpRepository = $rsvpRepository;
    }

    public function whenUserHasRsvpd(UserHasRsvpd $event): void
    {
        $user = $this->userRepository->getById($event->userId());

        $this->notifications->sendRsvpConfirmationMail($user);
    }

    public function whenMeetupWasCancelled(MeetupWasCancelled $event): void
    {
        $rsvps = $this->rsvpRepository->getByMeetupId($event->meetupId()->asString());
        foreach ($rsvps as $rsvp) {
            $user = $this->userRepository->getById($rsvp->userId());

            $this->notifications->sendMeetupCancellationMail($user);
        }
    }
}
