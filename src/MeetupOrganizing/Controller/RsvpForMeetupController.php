<?php
declare(strict_types=1);

namespace MeetupOrganizing\Controller;

use MeetupOrganizing\Entity\MeetupRepository;
use MeetupOrganizing\Entity\Rsvp;
use MeetupOrganizing\Entity\RsvpRepository;
use MeetupOrganizing\Session;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use RuntimeException;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Expressive\Router\RouterInterface;

final class RsvpForMeetupController
{
    /**
     * @var Session
     */
    private $session;

    /**
     * @var MeetupRepository
     */
    private $meetupRepository;

    /**
     * @var RsvpRepository
     */
    private $rsvpRepository;

    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(
        Session $session,
        MeetupRepository $meetupRepository,
        RsvpRepository $rsvpRepository,
        RouterInterface $router
    ) {
        $this->session = $session;
        $this->meetupRepository = $meetupRepository;
        $this->rsvpRepository = $rsvpRepository;
        $this->router = $router;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next
    ): ResponseInterface {
        $postData = $request->getParsedBody();
        if (!isset($postData['meetup_id'])) {
            throw new RuntimeException('Bad request');
        }

        $meetup = $this->meetupRepository->byId((int)$postData['meetup_id']);
        $meetupId = $meetup->id();
        $rsvp = new Rsvp(
            Uuid::uuid4(),
            $meetupId,
            $this->session->getLoggedInUser()->id()
        );
        $this->rsvpRepository->save($rsvp);

        return new RedirectResponse(
            $this->router->generateUri(
                'meetup_details',
                [
                    'id' => $meetup->id()
                ]
            )
        );
    }
}
