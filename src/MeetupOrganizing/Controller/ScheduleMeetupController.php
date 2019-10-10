<?php
declare(strict_types=1);

namespace MeetupOrganizing\Controller;

use Doctrine\DBAL\Connection;
use Exception;
use MeetupOrganizing\Entity\Meetup;
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
     * @var Connection
     */
    private $connection;

    public function __construct(
        Session $session,
        TemplateRendererInterface $renderer,
        RouterInterface $router,
        Connection $connection
    ) {
        $this->session = $session;
        $this->renderer = $renderer;
        $this->router = $router;
        $this->connection = $connection;
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
                $meetup = new Meetup(
                    $this->session->getLoggedInUser()->userId(),
                    $formData['name'],
                    $formData['description'],
                    ScheduledDate::fromString(
                        $formData['scheduleForDate'] . ' ' . $formData['scheduleForTime']
                    )
                );

                $this->connection->insert('meetups', $meetup->getData());

                $meetupId = (int)$this->connection->lastInsertId();

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
