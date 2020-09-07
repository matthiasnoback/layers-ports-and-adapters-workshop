<?php
declare(strict_types=1);

namespace MeetupOrganizing\Controller;

use Doctrine\DBAL\Connection;
use MeetupOrganizing\Entity\Rsvp;
use MeetupOrganizing\Entity\RsvpRepository;
use MeetupOrganizing\Entity\UserId;
use MeetupOrganizing\Entity\UserRepository;
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

        $meetup = $this->connection
            ->createQueryBuilder()
            ->select('*')
            ->from('meetups')
            ->where('meetupId = :meetupId')
            ->setParameter('meetupId', (int)$request->getAttribute('id'))
            ->execute()
            ->fetch(PDO::FETCH_ASSOC);

        if ($meetup === false) {
            throw new RuntimeException('Meetup not found');
        }

        $organizer = $this->userRepository->getById(UserId::fromInt((int)$meetup['organizerId']));
        $rsvps = $this->rsvpRepository->getByMeetupId((int)$meetup['meetupId']);
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
