<?php
declare(strict_types=1);

namespace MeetupOrganizing\Controller;

use Doctrine\DBAL\Connection;
use MeetupOrganizing\Session;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Expressive\Router\RouterInterface;

final class CancelMeetupController
{
    private Connection $connection;

    private Session $session;

    private RouterInterface $router;

    public function __construct(
        Connection $connection,
        Session $session,
        RouterInterface $router
    ) {
        $this->connection = $connection;
        $this->session = $session;
        $this->router = $router;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next
    ): ResponseInterface {
        $meetupId = $request->getParsedBody()['meetupId'];

        $numberOfAffectedRows = $this->connection->update(
            'meetups',
            [
                'wasCancelled' => 1
            ],
            [
                'meetupId' => $meetupId,
                'organizerId' => $this->session->getLoggedInUser()->userId()->asInt()
            ]
        );

        if ($numberOfAffectedRows > 0) {
            $this->session->addSuccessFlash('You have cancelled the meetup');
        }

        return new RedirectResponse(
            $this->router->generateUri('list_meetups')
        );
    }
}
