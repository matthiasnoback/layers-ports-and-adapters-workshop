<?php
declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure;

use Assert\Assert;
use MeetupOrganizing\Domain\MeetupId;
use MeetupOrganizing\Domain\MeetupRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use RuntimeException;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Expressive\Router\RouterInterface;

final class CancelMeetupController
{
    private Session $session;

    private RouterInterface $router;

    private MeetupRepository $meetupRepository;

    public function __construct(
        Session $session,
        RouterInterface $router,
        MeetupRepository $meetupRepository
    ) {
        $this->session = $session;
        $this->router = $router;
        $this->meetupRepository = $meetupRepository;
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
        $meetupId = $parsedBody['meetupId'];

        $meetup = $this->meetupRepository->getById(MeetupId::fromString($meetupId));
        $meetup->cancel();

        $this->meetupRepository->update($meetup);

        $this->session->addSuccessFlash('You have cancelled the meetup');

        return new RedirectResponse(
            $this->router->generateUri('list_meetups')
        );
    }
}
