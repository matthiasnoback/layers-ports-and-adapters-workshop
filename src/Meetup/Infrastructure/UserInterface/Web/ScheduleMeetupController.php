<?php
declare(strict_types=1);

namespace Meetup\Infrastructure\UserInterface\Web;

use Meetup\Application\ScheduleMeetup;
use Meetup\Application\ScheduleMeetupHandler;
use Meetup\Domain\MeetupRepository;
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

    public function __construct(
        TemplateRendererInterface $renderer,
        RouterInterface $router,
        ScheduleMeetupHandler $scheduleMeetupHandler,
        MeetupRepository $meetupRepository
    ) {
        $this->renderer = $renderer;
        $this->router = $router;
        $this->scheduleMeetupHandler = $scheduleMeetupHandler;
        $this->meetupRepository = $meetupRepository;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next): ResponseInterface
    {
        $submittedData = [];
        $formErrors = [];

        if ($request->getMethod() === 'POST') {
            $submittedData = $request->getParsedBody();

            $command = new ScheduleMeetup();
            $command->id = (string)$this->meetupRepository->nextIdentity();
            $command->name = $submittedData['name'];
            $command->description = $submittedData['description'];
            $command->scheduledFor = $submittedData['scheduledFor'];

            $formErrors = $command->validate();

            if (empty($formErrors)) {
                $meetup = $this->scheduleMeetupHandler->handle($command);

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
                    'formErrors' => $this->translateFormErrors($formErrors)
                ]
            )
        );

        return $response;
    }

    private function translateFormErrors(array $formErrors): array
    {
        $translatedErrors = $formErrors;
        foreach ($translatedErrors as $field => $errors) {
            foreach ($errors as $index => $error) {
                $translatedErrors[$field][$index] = $this->translateFormError($error);
            }
        }

        return $translatedErrors;
    }

    private function translateFormError(string $error): string
    {
        switch ($error) {
            case ScheduleMeetup::NAME_SHOULD_NOT_BE_EMPTY:
                return 'Provide a name';
            case ScheduleMeetup::DESCRIPTION_SHOULD_NOT_BE_EMPTY:
                return 'Provide a description';
            case ScheduleMeetup::SCHEDULED_FOR_SHOULD_NOT_BE_EMPTY:
                return 'Provide a date';
            case ScheduleMeetup::INVALID_SCHEDULED_FOR_DATE:
                return 'Invalid date format';
        }

        return 'Unknown error';
    }
}
