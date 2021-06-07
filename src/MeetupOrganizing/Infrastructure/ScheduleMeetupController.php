<?php
declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure;

use Assert\Assert;
use Exception;
use MeetupOrganizing\Application\MeetupOrganizingInterface;
use MeetupOrganizing\Application\ScheduleMeetup;
use MeetupOrganizing\Domain\ScheduledDate;
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

    private MeetupOrganizingInterface $meetupOrganizing;

    public function __construct(
        Session $session,
        TemplateRendererInterface $renderer,
        RouterInterface $router,
        MeetupOrganizingInterface $meetupOrganizing
    ) {
        $this->session = $session;
        $this->renderer = $renderer;
        $this->router = $router;
        $this->meetupOrganizing = $meetupOrganizing;
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

                $meetupId = $this->meetupOrganizing->scheduleMeetup(
                    new ScheduleMeetup(
                        $this->session->getLoggedInUser()->userId()->asInt(),
                        $formData['name'],
                        $formData['description'],
                        $formData['scheduleForDate'] . ' ' .
                        $formData['scheduleForTime']
                    )
                );

                return new RedirectResponse(
                    $this->router->generateUri(
                        'meetup_details',
                        [
                            'id' => $meetupId->asString()
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
