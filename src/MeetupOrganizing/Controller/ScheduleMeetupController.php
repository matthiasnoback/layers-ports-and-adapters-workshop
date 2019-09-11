<?php
declare(strict_types = 1);

namespace MeetupOrganizing\Controller;

use MeetupOrganizing\Entity\Description;
use MeetupOrganizing\Entity\Meetup;
use MeetupOrganizing\Entity\MeetupRepository;
use MeetupOrganizing\Entity\Name;
use MeetupOrganizing\Entity\ScheduledDate;
use MeetupOrganizing\Session;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

final class ScheduleMeetupController
{
    /**
     * @var Session
     */
    private $session;

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

    public function __construct(
        Session $session,
        TemplateRendererInterface $renderer,
        RouterInterface $router,
        MeetupRepository $repository
    ) {
        $this->session = $session;
        $this->renderer = $renderer;
        $this->router = $router;
        $this->repository = $repository;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next): ResponseInterface
    {
        $formErrors = [];
        $formData = [
            'scheduleForTime' => '20:00'
        ];

        if ($request->getMethod() === 'POST') {
            $formData = $request->getParsedBody();

            if (empty($formData['name'])) {
                $formErrors['name'][] = 'Provide a name';
            }
            if (empty($formData['description'])) {
                $formErrors['description'][] = 'Provide a description';
            }
            if (empty($formData['scheduleForDate'])) {
                $formErrors['scheduleForDate'][] = 'Provide a date';
            }
            if (empty($formData['scheduleForTime'])) {
                $formErrors['scheduleForTime'][] = 'Provide a time';
            }

            if (empty($formErrors)) {
                $meetup = Meetup::schedule(
                    $this->session->getLoggedInUser()->userId(),
                    Name::fromString($formData['name']),
                    Description::fromString($formData['description']),
                    ScheduledDate::fromPhpDateString(
                        $formData['scheduleForDate'] . ' ' . $formData['scheduleForTime']
                    )
                );
                $this->repository->add($meetup);

                $this->session->addSuccessFlash('Your meetup was scheduled successfully');

                return new RedirectResponse(
                    $this->router->generateUri(
                        'meetup_details',
                        [
                            'id' => $meetup->meetupId()
                        ]
                    )
                );
            }
        }

        $response->getBody()->write(
            $this->renderer->render(
                'schedule-meetup.html.twig',
                [
                    'submittedData' => $formData,
                    'formErrors' => $formErrors
                ]
            )
        );

        return $response;
    }
}
