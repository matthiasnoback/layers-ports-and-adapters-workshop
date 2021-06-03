<?php
declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure;

use MeetupOrganizing\Domain\UserHasRsvpd;
use MeetupOrganizing\Domain\UserRepository;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

final class SendEmail
{
    private UserRepository $userRepository;
    private MailerInterface $mailer;

    public function __construct(UserRepository $userRepository, MailerInterface $mailer)
    {
        $this->userRepository = $userRepository;
        $this->mailer = $mailer;
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
}
