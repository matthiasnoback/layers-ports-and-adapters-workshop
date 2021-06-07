<?php
declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure;

use Assert\Assert;
use MeetupOrganizing\Application\CancelMeetup;
use MeetupOrganizing\Application\MeetupOrganizingInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use RuntimeException;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Expressive\Router\RouterInterface;

final class CancelMeetupController
{
    private Session $session;

    private RouterInterface $router;
    private MeetupOrganizingInterface $meetupOrganizing;

    public function __construct(
        Session $session,
        RouterInterface $router,
        MeetupOrganizingInterface $meetupService
    ) {
        $this->session = $session;
        $this->router = $router;
        $this->meetupOrganizing = $meetupService;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next
    ): ResponseInterface {
        $parsedBody = $request->getParsedBody();
        Assert::that($parsedBody)->isArray();

        if (!isset($parsedBody['meetupId'])) {
            throw new RuntimeException('Bad request');
        }

        $this->meetupOrganizing->cancelMeetup(
            new CancelMeetup(
                $this->session->getLoggedInUser()->userId()->asInt(),
                $parsedBody['meetupId']
            )
        );

        $this->session->addSuccessFlash('You have cancelled the meetup');

        return new RedirectResponse(
            $this->router->generateUri('list_meetups')
        );
    }
}
