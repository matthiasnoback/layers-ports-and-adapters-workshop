<?php
declare(strict_types=1);

namespace MeetupOrganizing\Controller;

use Assert\Assert;
use Doctrine\DBAL\Connection;
use Exception;
use MeetupOrganizing\Command\ScheduleMeetup;
use MeetupOrganizing\Entity\Meetup;
use MeetupOrganizing\Entity\ScheduledDate;
use MeetupOrganizing\Repository\MeetupRepository;
use MeetupOrganizing\Service\MeetupService;
use MeetupOrganizing\Session;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

final class ScheduleMeetupController
{
    private Session $session;

    private TemplateRendererInterface $renderer;

    private RouterInterface $router;

    private MeetupService $meetupService;

    public function __construct(
        Session $session,
        TemplateRendererInterface $renderer,
        RouterInterface $router,
        MeetupService $meetupService
    ) {
        $this->session = $session;
        $this->renderer = $renderer;
        $this->router = $router;
        $this->meetupService = $meetupService;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next
    ): ResponseInterface {
        $formErrors = [];
        $formData = [
            // This is a nice place to set some defaults
            'scheduleForTime' => '20:00'
        ];

        if ($request->getMethod() === 'POST') {
            $formData = $request->getParsedBody();
            Assert::that($formData)->isArray();

            if (empty($formData['name'])) {
                $formErrors['name'][] = 'Provide a name';
            }
            if (empty($formData['description'])) {
                $formErrors['description'][] = 'Provide a description';
            }
            try {
                ScheduledDate::fromString(
                    $formData['scheduleForDate'] . ' ' . $formData['scheduleForTime']
                );
            } catch (Exception $exception) {
                $formErrors['scheduleFor'][] = 'Invalid date/time';
            }

            if (empty($formErrors)) {
                $meetupId = $this->meetupService->scheduleMeetup(new ScheduleMeetup(
                    $this->session->getLoggedInUser()->userId()->asInt(),
                    $formData['name'],
                    $formData['description'],
                    $formData['scheduleForDate'] . ' ' . $formData['scheduleForTime']
                ));

                $this->session->addSuccessFlash('Your meetup was scheduled successfully');

                return new RedirectResponse(
                    $this->router->generateUri(
                        'meetup_details',
                        [
                            'id' => $meetupId
                        ]
                    )
                );
            }
        }

        $response->getBody()->write(
            $this->renderer->render(
                'schedule-meetup.html.twig',
                [
                    'formData' => $formData,
                    'formErrors' => $formErrors
                ]
            )
        );

        return $response;
    }
}
