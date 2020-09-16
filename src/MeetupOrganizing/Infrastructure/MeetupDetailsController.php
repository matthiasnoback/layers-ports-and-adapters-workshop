<?php
declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure;

use Assert\Assert;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Statement;
use MeetupOrganizing\Domain\Rsvp;
use MeetupOrganizing\Domain\RsvpRepository;
use MeetupOrganizing\Domain\UserId;
use MeetupOrganizing\Domain\UserRepository;
use PDO;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use RuntimeException;
use Zend\Expressive\Template\TemplateRendererInterface;

final class MeetupDetailsController
{
    private Connection $connection;

    private UserRepository $userRepository;

    private TemplateRendererInterface $renderer;

    private RsvpRepository $rsvpRepository;

    public function __construct(
        Connection $connection,
        UserRepository $userRepository,
        RsvpRepository $rsvpRepository,
        TemplateRendererInterface $renderer
    ) {
        $this->connection = $connection;
        $this->renderer = $renderer;
        $this->userRepository = $userRepository;
        $this->rsvpRepository = $rsvpRepository;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $out = null
    ): ResponseInterface {

        $statement = $this->connection
            ->createQueryBuilder()
            ->select('*')
            ->from('meetups')
            ->where('meetupId = :meetupId')
            ->setParameter('meetupId', $request->getAttribute('id'))
            ->execute();
        Assert::that($statement)->isInstanceOf(Statement::class);

        $meetup = $statement->fetch(PDO::FETCH_ASSOC);

        if ($meetup === false) {
            throw new RuntimeException('Meetup not found');
        }

        $organizer = $this->userRepository->getById(UserId::fromInt((int)$meetup['organizerId']));
        $rsvps = $this->rsvpRepository->getByMeetupId($meetup['meetupId']);
        $users = array_map(
            function (Rsvp $rsvp) {
                return $this->userRepository->getById($rsvp->userId());
            },
            $rsvps
        );

        $response->getBody()->write(
            $this->renderer->render(
                'meetup-details.html.twig',
                [
                    'meetup' => $meetup,
                    'organizer' => $organizer,
                    'attendees' => $users
                ]));

        return $response;
    }
}
