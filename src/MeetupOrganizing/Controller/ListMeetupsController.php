<?php
declare(strict_types=1);

namespace MeetupOrganizing\Controller;

use Assert\Assert;
use DateTimeImmutable;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Statement;
use MeetupOrganizing\Entity\ScheduledDate;
use MeetupOrganizing\Repository\ListMeetupsRepositoryInterface;
use PDO;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\Stratigility\MiddlewareInterface;

final class ListMeetupsController implements MiddlewareInterface
{
    private TemplateRendererInterface $renderer;
    private ListMeetupsRepositoryInterface $listMeetupsRepository;

    public function __construct(
        ListMeetupsRepositoryInterface $listMeetupsRepository,
        TemplateRendererInterface $renderer
    ) {
        $this->renderer = $renderer;
        $this->listMeetupsRepository = $listMeetupsRepository;
    }

    public function __invoke(Request $request, Response $response, callable $out = null): ResponseInterface
    {
        $response->getBody()->write(
            $this->renderer->render(
                'list-meetups.html.twig',
                [
                    'upcomingMeetups' => $this->listMeetupsRepository->listUpcomingMeetups(),
                    'pastMeetups' => $this->listMeetupsRepository->listPastMeetups()
                ]));

        return $response;
    }
}
