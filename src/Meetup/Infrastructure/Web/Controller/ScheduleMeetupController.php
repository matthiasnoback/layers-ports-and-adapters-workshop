<?php
declare(strict_types = 1);

namespace Meetup\Infrastructure\Web\Controller;

use Meetup\Application\ScheduleMeetup;
use Meetup\Application\ScheduleMeetupHandler;
use Meetup\Domain\Repository\MeetupRepository;
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
    /**
     * @var MeetupRepository
     */
    private $meetupRepository;

    public function __construct(TemplateRendererInterface $renderer, RouterInterface $router, ScheduleMeetupHandler $scheduleMeetupHandler, MeetupRepository $meetupRepository)
    {
        $this->renderer = $renderer;
        $this->router = $router;
        $this->scheduleMeetupHandler = $scheduleMeetupHandler;
        $this->meetupRepository = $meetupRepository;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next): ResponseInterface
    {
        $formErrors = [];
        $submittedData = [];

        if ($request->getMethod() === 'POST') {
            $submittedData = $request->getParsedBody();

            $formErrors = ScheduleMeetup::validate($submittedData);

            if (empty($formErrors)) {
                $scheduleMeetup = new ScheduleMeetup();
                $scheduleMeetup->id = (string)$this->meetupRepository->nextIdentity();
                $scheduleMeetup->name = $submittedData['name'];
                $scheduleMeetup->description = $submittedData['description'];
                $scheduleMeetup->scheduledFor = $submittedData['scheduledFor'];

                $this->scheduleMeetupHandler->handle($scheduleMeetup);

                return new RedirectResponse(
                    $this->router->generateUri(
                        'meetup_details',
                        [
                            'id' => $scheduleMeetup->id
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
