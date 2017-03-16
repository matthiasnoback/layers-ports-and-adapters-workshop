<?php
declare(strict_types = 1);

namespace Meetup\Controller;

use Meetup\Entity\Description;
use Meetup\Entity\Meetup;
use Meetup\Entity\MeetupRepository;
use Meetup\Entity\Name;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

final class ScheduleMeetupController
{
    /**
     * @var TemplateRendererInterface
     */
    private $renderer;

    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var MeetupRepository
     */
    private $repository;

    public function __construct(TemplateRendererInterface $renderer, RouterInterface $router, MeetupRepository $repository)
    {
        $this->renderer = $renderer;
        $this->router = $router;
        $this->repository = $repository;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next): ResponseInterface
    {
        if ($request->getMethod() === 'POST') {
            $submittedData = $request->getParsedBody();

            $meetup = Meetup::schedule(
                Name::fromString($submittedData['name']),
                Description::fromString($submittedData['description']),
                new \DateTimeImmutable($submittedData['scheduledFor'])
            );
            $this->repository->add($meetup);

            return new RedirectResponse($this->router->generateUri('list_meetups'));
        } else {
            $submittedData = [];
        }

        $response->getBody()->write(
            $this->renderer->render(
                'schedule-meetup.html.twig',
                ['submittedData' => $submittedData]
            )
        );

        return $response;
    }
}
