<?php
declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure;

use MeetupOrganizing\Application\Notifications;
use MeetupOrganizing\Domain\User;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

final class NotificationsUsingSymfonyMailer implements Notifications
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendRsvpConfirmationMail(User $user): void
    {
        $this->mailer->send(
            (new Email())->subject('You are attending')
                ->to($user->emailAddress())
                ->from('noreply@example.com')
                ->text('You are attending')
        );
    }

    public function sendMeetupCancellationMail(User $user): void
    {
        $this->mailer->send(
            (new Email())->subject('The meetup was cancelled')
                ->to($user->emailAddress())
                ->from('noreply@example.com')
                ->text('You have RSVP-ed to a meetup that was just cancelled')
        );
    }
}
