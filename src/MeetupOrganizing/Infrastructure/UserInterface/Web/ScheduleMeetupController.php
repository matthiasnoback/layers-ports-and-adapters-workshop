<?php
declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure\UserInterface\Web;

use MeetupOrganizing\Application\ScheduleMeetup;
use MeetupOrganizing\Application\ScheduleMeetupHandler;
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
     * @var ScheduleMeetupHandler
     */
    private $scheduleMeetupHandler;

    public function __construct(
        TemplateRendererInterface $renderer,
        RouterInterface $router,
        ScheduleMeetupHandler $scheduleMeetupHandler
    ) {
        $this->renderer = $renderer;
        $this->router = $router;
        $this->scheduleMeetupHandler = $scheduleMeetupHandler;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next
    ): ResponseInterface {
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
                $scheduleMeetup = new ScheduleMeetup();
                $scheduleMeetup->name = $submittedData['name'];
                $scheduleMeetup->description = $submittedData['description'];
                $scheduleMeetup->scheduledFor = $submittedData['scheduledFor'];

                $meetup = $this->scheduleMeetupHandler->handle($scheduleMeetup);

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
