<?php
declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure;

use MeetupOrganizing\Domain\MeetupWasCancelled;
use MeetupOrganizing\Domain\RsvpRepository;
use MeetupOrganizing\Domain\UserHasRsvpd;
use MeetupOrganizing\Domain\UserRepository;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

final class SendEmail
{
    private UserRepository $userRepository;
    private MailerInterface $mailer;
    private RsvpRepository $rsvpRepository;

    public function __construct(UserRepository $userRepository, MailerInterface $mailer, RsvpRepository $rsvpRepository)
    {
        $this->userRepository = $userRepository;
        $this->mailer = $mailer;
        $this->rsvpRepository = $rsvpRepository;
    }

    public function whenUserHasRsvpd(UserHasRsvpd $event): void
    {
        $user = $this->userRepository->getById($event->userId());

        $this->mailer->send(
            (new Email())->subject('You are attending')
                ->to($user->emailAddress())
                ->from('noreply@example.com')
                ->text('You are attending')
        );
    }

    public function whenMeetupWasCancelled(MeetupWasCancelled $event): void
    {
        $rsvps = $this->rsvpRepository->getByMeetupId($event->meetupId()->asString());
        foreach ($rsvps as $rsvp) {
            $user = $this->userRepository->getById($rsvp->userId());
            $this->mailer->send(
                (new Email())->subject('The meetup was cancelled')
                    ->to($user->emailAddress())
                    ->from('noreply@example.com')
                    ->text('You have RSVP-ed to a meetup that was just cancelled')
            );
        }
    }
}
