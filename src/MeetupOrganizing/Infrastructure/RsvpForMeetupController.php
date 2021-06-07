<?php
declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure;

use Assert\Assert;
use Doctrine\DBAL\Connection;
use MeetupOrganizing\Application\MeetupOrganizing;
use MeetupOrganizing\Application\RsvpForMeetup;
use MeetupOrganizing\Domain\RsvpRepository;
use MeetupOrganizing\Application\EventDispatcher;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use RuntimeException;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Expressive\Router\RouterInterface;

final class RsvpForMeetupController
{
    private Session $session;

    private RouterInterface $router;

    private MeetupOrganizing $meetupOrganizing;

    public function __construct(
        Session $session,
        RouterInterface $router,
        MeetupOrganizing $meetupOrganizing
    ) {
        $this->session = $session;
        $this->router = $router;
        $this->meetupOrganizing = $meetupOrganizing;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next
    ): ResponseInterface {
        $postData = $request->getParsedBody();
        Assert::that($postData)->isArray();

        if (!isset($postData['meetupId'])) {
            throw new RuntimeException('Bad request');
        }

        $this->meetupOrganizing->rsvpForMeetup(
            new RsvpForMeetup($this->session->getLoggedInUser()->userId()->asInt(), $postData['meetupId'])
        );

        return new RedirectResponse(
            $this->router->generateUri(
                'meetup_details',
                [
                    'id' => $postData['meetupId']
                ]
            )
        );
    }
}
