<?php
declare(strict_types=1);

namespace MeetupOrganizing\Controller;

use DateTimeImmutable;
use Doctrine\DBAL\Connection;
use MeetupOrganizing\ReadModel\ListMeetupsRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\Stratigility\MiddlewareInterface;

final class ListMeetupsController implements MiddlewareInterface
{
    /**
     * @var Connection
     */
    private $listMeetupsRepository;

    /**
     * @var TemplateRendererInterface
     */
    private $renderer;

    public function __construct(
        ListMeetupsRepository $listMeetupsRepository,
        TemplateRendererInterface $renderer
    ) {
        $this->listMeetupsRepository = $listMeetupsRepository;
        $this->renderer = $renderer;
    }

    public function __invoke(Request $request, Response $response, callable $out = null): ResponseInterface
    {
        $now = new DateTimeImmutable();

        $response->getBody()->write(
            $this->renderer->render(
                'list-meetups.html.twig',
                [
                    'upcomingMeetups' => $this->listMeetupsRepository->upcomingMeetups($now),
                    'pastMeetups' => $this->listMeetupsRepository->pastMeetups($now)
                ]));

        return $response;
    }
}
