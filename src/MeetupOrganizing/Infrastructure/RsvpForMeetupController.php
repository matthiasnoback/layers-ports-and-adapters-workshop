<?php
declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure;

use Assert\Assert;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Statement;
use MeetupOrganizing\Domain\Rsvp;
use MeetupOrganizing\Domain\RsvpRepository;
use MeetupOrganizing\Domain\UserHasRsvpd;
use MeetupOrganizing\Application\EventDispatcher;
use MeetupOrganizing\Domain\UserRepository;
use PDO;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use RuntimeException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Expressive\Router\RouterInterface;

final class RsvpForMeetupController
{
    private Connection $connection;

    private Session $session;

    private RsvpRepository $rsvpRepository;

    private RouterInterface $router;
    private EventDispatcher $eventDispatcher;
    private UserRepository $userRepository;
    private MailerInterface $mailer;

    public function __construct(
        Connection $connection,
        Session $session,
        RsvpRepository $rsvpRepository,
        RouterInterface $router,
        EventDispatcher $eventDispatcher,
        UserRepository $userRepository,
        MailerInterface $mailer
    ) {
        $this->connection = $connection;
        $this->session = $session;
        $this->rsvpRepository = $rsvpRepository;
        $this->router = $router;
        $this->eventDispatcher = $eventDispatcher;
        $this->userRepository = $userRepository;
        $this->mailer = $mailer;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next
    ): ResponseInterface {
        $postData = $request->getParsedBody();
        Assert::that($postData)->isArray();

        if (!isset($postData['meetupId'])) {
            throw new RuntimeException('Bad request');
        }

        $statement = $this->connection
            ->createQueryBuilder()
            ->select('*')
            ->from('meetups')
            ->where('meetupId = :meetupId')
            ->setParameter('meetupId', $postData['meetupId'])
            ->execute();
        Assert::that($statement)->isInstanceOf(Statement::class);

        $record = $statement->fetch(PDO::FETCH_ASSOC);

        if ($record === false) {
            throw new RuntimeException('Meetup not found');
        }

        $rsvp = Rsvp::create(
            $postData['meetupId'],
            $this->session->getLoggedInUser()->userId()
        );
        $this->rsvpRepository->save($rsvp);

        $this->eventDispatcher->dispatch(
            new UserHasRsvpd($postData['meetupId'], $this->session->getLoggedInUser()->userId(), $rsvp->rsvpId())
        );

        $user = $this->userRepository->getById($this->session->getLoggedInUser()->userId());
        $this->mailer->send(
            (new Email())->subject('You are attending')
            ->to($user->emailAddress())
            ->from('noreply@example.com')
            ->text('You are attending')
        );

        return new RedirectResponse(
            $this->router->generateUri(
                'meetup_details',
                [
                    'id' => $postData['meetupId']
                ]
            )
        );
    }
}
