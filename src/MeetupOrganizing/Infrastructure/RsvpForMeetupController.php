<?php
declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure;

use Assert\Assert;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Statement;
use MeetupOrganizing\Domain\Model\Rsvp\Rsvp;
use MeetupOrganizing\Domain\Model\Rsvp\RsvpRepository;
use PDO;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use RuntimeException;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Expressive\Router\RouterInterface;

final class RsvpForMeetupController
{
    private Connection $connection;

    private Session $session;

    private RsvpRepository $rsvpRepository;

    private RouterInterface $router;

    public function __construct(
        Connection $connection,
        Session $session,
        RsvpRepository $rsvpRepository,
        RouterInterface $router
    ) {
        $this->connection = $connection;
        $this->session = $session;
        $this->rsvpRepository = $rsvpRepository;
        $this->router = $router;
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

        $statement = $this->connection
            ->createQueryBuilder()
            ->select('*')
            ->from('meetups')
            ->where('meetupId = :meetupId')
            ->setParameter('meetupId', (int)$postData['meetupId'])
            ->execute();
        Assert::that($statement)->isInstanceOf(Statement::class);

        $record = $statement->fetch(PDO::FETCH_ASSOC);

        if ($record === false) {
            throw new RuntimeException('Meetup not found');
        }

        $rsvp = Rsvp::create(
            (int)$postData['meetupId'],
            $this->session->getLoggedInUser()->userId()
        );
        $this->rsvpRepository->save($rsvp);

        $this->session->addSuccessFlash('You have successfully RSVP-ed to this meetup');

        return new RedirectResponse(
            $this->router->generateUri(
                'meetup_details',
                [
                    'id' => (int)$postData['meetupId']
                ]
            )
        );
    }
}
