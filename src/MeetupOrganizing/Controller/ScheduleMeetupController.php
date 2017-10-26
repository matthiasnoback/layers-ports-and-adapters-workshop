<?php
declare(strict_types = 1);

namespace MeetupOrganizing\Controller;

use MeetupOrganizing\Entity\Description;
use MeetupOrganizing\Entity\Meetup;
use MeetupOrganizing\Entity\MeetupRepository;
use MeetupOrganizing\Entity\Name;
use MeetupOrganizing\Entity\ScheduledDate;
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
        $formErrors = [];
        $submittedData = [];

        if ($request->getMethod() === 'POST') {
            $submittedData = $request->getParsedBody();

            if (empty($submittedData['name'])) {
                $formErrors['name'][] = 'Provide a name';
            }
            if (empty($submittedData['description'])) {
                $formErrors['description'][] = 'Provide a description';
            }
            if (empty($submittedData['scheduledFor'])) {
                $formErrors['scheduledFor'][] = 'Provide a scheduled for date';
            }

            if (empty($formErrors)) {
                $meetup = Meetup::schedule(
                    Name::fromString($submittedData['name']),
                    Description::fromString($submittedData['description']),
                    ScheduledDate::fromPhpDateString($submittedData['scheduledFor'])
                );
                $this->repository->add($meetup);

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

        $response->getBody()->write(
            $this->renderer->render(
                'schedule-meetup.html.twig',
                [
                    'submittedData' => $submittedData,
                    'formErrors' => $formErrors
                ]
            )
        );

        return $response;
    }
}
