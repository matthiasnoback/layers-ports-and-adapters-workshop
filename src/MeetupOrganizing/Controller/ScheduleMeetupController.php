<?php
declare(strict_types=1);

namespace MeetupOrganizing\Controller;

use InvalidArgumentException;
use MeetupOrganizing\Entity\ScheduledDate;
use MeetupOrganizing\MeetupService;
use MeetupOrganizing\ScheduleMeetup;
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
     * @var MeetupService
     */
    private $meetupService;

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

            $command = new ScheduleMeetup(
                $this->session->getLoggedInUser()->userId()->asInt(),
                $formData['name'],
                $formData['description'],
                $formData['scheduleForDate'] . ' ' . $formData['scheduleForTime']
            );

            $formErrors = $command->validate();

            if (empty($formErrors)) {
                $meetupId = $this->meetupService->scheduleMeetup(
                    $command
                );

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
