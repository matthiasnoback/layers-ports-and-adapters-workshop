<?php
declare(strict_types = 1);

namespace MeetupOrganizing\Controller;

use DateTimeImmutable;
use MeetupOrganizing\Entity\Meetup;
use MeetupOrganizing\Entity\MeetupRepository;
use MeetupOrganizing\Entity\UserRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\Stratigility\MiddlewareInterface;

final class ListMeetupsController implements MiddlewareInterface
{
    /**
     * @var MeetupRepository
     */
    private $meetupRepository;

    /**
     * @var TemplateRendererInterface
     */
    private $renderer;

    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(
        MeetupRepository $meetupRepository,
        UserRepository $userRepository,
        TemplateRendererInterface $renderer
    ) {
        $this->meetupRepository = $meetupRepository;
        $this->renderer = $renderer;
        $this->userRepository = $userRepository;
    }

    public function __invoke(Request $request, Response $response, callable $out = null): ResponseInterface
    {
        $now = new DateTimeImmutable();
        $upcomingMeetups = $this->meetupRepository->upcomingMeetups($now);
        $pastMeetups = $this->meetupRepository->pastMeetups($now);

        $organizers = [];
        foreach (array_merge($upcomingMeetups, $pastMeetups) as $meetup) {
            /** @var Meetup $meetup */
            $organizers[$meetup->organizerId()->asInt()] = $this->userRepository->getById($meetup->organizerId());
        }

        $response->getBody()->write($this->renderer->render('list-meetups.html.twig', [
            'upcomingMeetups' => $upcomingMeetups,
            'pastMeetups' => $pastMeetups,
            'organizers' => $organizers
        ]));

        return $response;
    }
}
