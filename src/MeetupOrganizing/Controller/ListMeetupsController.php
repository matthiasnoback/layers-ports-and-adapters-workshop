<?php
declare(strict_types = 1);

namespace MeetupOrganizing\Controller;

use MeetupOrganizing\Entity\MeetupRepository;
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

    public function __construct(MeetupRepository $meetupRepository, TemplateRendererInterface $renderer)
    {
        $this->meetupRepository = $meetupRepository;
        $this->renderer = $renderer;
    }

    public function __invoke(Request $request, Response $response, callable $out = null): ResponseInterface
    {
        $now = new \DateTimeImmutable();
        $upcomingMeetups = $this->meetupRepository->upcomingMeetups($now);
        $pastMeetups = $this->meetupRepository->pastMeetups($now);

        $response->getBody()->write($this->renderer->render('list-meetups.html.twig', [
            'upcomingMeetups' => $upcomingMeetups,
            'pastMeetups' => $pastMeetups
        ]));

        return $response;
    }
}
