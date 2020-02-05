<?php
declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure;

use MeetupOrganizing\Application\ScheduleMeetup\MeetupService;
use MeetupOrganizing\Application\ScheduleMeetup\ScheduleMeetup;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validation;
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
                $formData['name'] ?? '',
                $formData['description'] ?? '',
                ($formData['scheduleForDate'] ?? '') . ' ' . ($formData['scheduleForTime'] ?? '')
            );

            $validator = Validation::createValidatorBuilder()
                ->addMethodMapping('loadValidatorMetadata')
                ->getValidator();
            $violations = $validator->validate($command);
            if (count($violations) === 0) {
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
            } else {
                foreach ($violations as $violation) {
                    /** @var ConstraintViolation $violation */
                    $formErrors[$violation->getPropertyPath()][] = $violation->getMessage();
                }
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
