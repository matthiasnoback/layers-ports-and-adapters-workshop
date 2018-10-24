<?php
declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure\UserInterface\Web\Controller;

use MeetupOrganizing\Application\ScheduleMeetup;
use MeetupOrganizing\Application\ScheduleMeetupService;
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
     * @var ScheduleMeetupService
     */
    private $scheduleMeetupService;

    public function __construct(
        TemplateRendererInterface $renderer,
        RouterInterface $router,
        ScheduleMeetupService $scheduleMeetupService
    ) {
        $this->renderer = $renderer;
        $this->router = $router;
        $this->scheduleMeetupService = $scheduleMeetupService;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next): ResponseInterface
    {
        $formErrors = [];
        $submittedData = [];

        if ($request->getMethod() === 'POST') {
            $submittedData = $request->getParsedBody();

            $command = new ScheduleMeetup();
            $command->name = $submittedData['name'];
            $command->description = $submittedData['description'];
            $command->scheduledFor = $submittedData['scheduledFor'];

            $formErrors = $command->validate();
            if (empty($formErrors)) {
                $meetup = $this->scheduleMeetupService->handle($command);

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
