<?php
declare(strict_types = 1);

namespace MeetupOrganizing\Controller;

use MeetupOrganizing\Entity\MeetupRepository;
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

    public function __construct(
        MeetupRepository $meetupRepository,
        UserRepository $userRepository,
        TemplateRendererInterface $renderer
    ) {
        $this->meetupRepository = $meetupRepository;
        $this->renderer = $renderer;
        $this->userRepository = $userRepository;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $out = null): ResponseInterface
    {
        $meetup = $this->meetupRepository->byId((int)$request->getAttribute('id'));
        $organizer = $this->userRepository->getById($meetup->organizerId());

        $response->getBody()->write($this->renderer->render('meetup-details.html.twig', [
            'meetup' => $meetup,
            'organizer' => $organizer
        ]));

        return $response;
    }
}
