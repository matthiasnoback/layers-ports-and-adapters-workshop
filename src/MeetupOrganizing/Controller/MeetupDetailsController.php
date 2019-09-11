<?php
declare(strict_types=1);

namespace MeetupOrganizing\Controller;

use MeetupOrganizing\Entity\MeetupRepository;
use MeetupOrganizing\Entity\Rsvp;
use MeetupOrganizing\Entity\RsvpRepository;
use MeetupOrganizing\Entity\UserRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

final class MeetupDetailsController
{
    /**
     * @var MeetupRepository
     */
    private $meetupRepository;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var TemplateRendererInterface
     */
    private $renderer;

    /**
     * @var RsvpRepository
     */
    private $rsvpRepository;

    public function __construct(
        MeetupRepository $meetupRepository,
        UserRepository $userRepository,
        RsvpRepository $rsvpRepository,
        TemplateRendererInterface $renderer
    ) {
        $this->meetupRepository = $meetupRepository;
        $this->renderer = $renderer;
        $this->userRepository = $userRepository;
        $this->rsvpRepository = $rsvpRepository;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $out = null
    ): ResponseInterface {
        $meetup = $this->meetupRepository->byId((int)$request->getAttribute('id'));
        $organizer = $this->userRepository->getById($meetup->organizerId());
        $rsvps = $this->rsvpRepository->getByMeetupId($meetup->id());
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
