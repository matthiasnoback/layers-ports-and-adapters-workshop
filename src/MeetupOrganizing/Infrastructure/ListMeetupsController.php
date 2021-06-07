<?php
declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure;

use MeetupOrganizing\Application\MeetupOrganizingInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\Stratigility\MiddlewareInterface;

final class ListMeetupsController implements MiddlewareInterface
{
    private MeetupOrganizingInterface $meetupOrganizing;

    private TemplateRendererInterface $renderer;

    public function __construct(
        MeetupOrganizingInterface $meetupOrganizing,
        TemplateRendererInterface $renderer
    ) {
        $this->meetupOrganizing = $meetupOrganizing;
        $this->renderer = $renderer;
    }

    public function __invoke(Request $request, Response $response, callable $out = null): ResponseInterface
    {
        $response->getBody()->write(
            $this->renderer->render(
                'list-meetups.html.twig',
                [
                    'upcomingMeetups' => $this->meetupOrganizing->listUpcomingMeetups(),
                    'pastMeetups' => $this->meetupOrganizing->listPastMeetups()
                ]));

        return $response;
    }
}
