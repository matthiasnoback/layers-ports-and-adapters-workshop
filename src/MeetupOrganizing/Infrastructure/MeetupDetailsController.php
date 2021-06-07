<?php
declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure;

use Assert\Assert;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Statement;
use MeetupOrganizing\Domain\UserId;
use MeetupOrganizing\Domain\UserRepository;
use PDO;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use RuntimeException;
use Zend\Expressive\Template\TemplateRendererInterface;

final class MeetupDetailsController
{
    private Connection $connection;

    private UserRepository $userRepository;

    private TemplateRendererInterface $renderer;

    public function __construct(
        Connection $connection,
        UserRepository $userRepository,
        TemplateRendererInterface $renderer
    ) {
        $this->connection = $connection;
        $this->renderer = $renderer;
        $this->userRepository = $userRepository;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $out = null
    ): ResponseInterface {

        $statement = $this->connection
            ->createQueryBuilder()
            ->select('*')
            ->from('meetups')
            ->where('meetupId = :meetupId')
            ->setParameter('meetupId', $request->getAttribute('id'))
            ->execute();
        Assert::that($statement)->isInstanceOf(Statement::class);

        $meetup = $statement->fetch(PDO::FETCH_ASSOC);

        if ($meetup === false) {
            throw new RuntimeException('Meetup not found');
        }

        $organizer = $this->userRepository->getById(UserId::fromInt((int)$meetup['organizerId']));

        $statement = $this->connection
            ->createQueryBuilder()
            ->select('*')
            ->from('rsvps')
            ->where('meetupId = :meetupId')
            ->setParameter('meetupId', $request->getAttribute('id'))
            ->execute();

        Assert::that($statement)->isInstanceOf(Statement::class);
        $rsvpRecords = $statement->fetchAll(PDO::FETCH_ASSOC);

        $users = array_map(
            function (array $rsvpRecord) {
                return $this->userRepository->getById(UserId::fromInt((int)$rsvpRecord['userId']));
            },
            $rsvpRecords
        );

        $response->getBody()->write(
            $this->renderer->render(
                'meetup-details.html.twig',
                [
                    'meetup' => $meetup,
                    'organizer' => $organizer,
                    'attendees' => $users
                ]));

        return $response;
    }
}
