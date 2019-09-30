<?php
declare(strict_types=1);

namespace MeetupOrganizing\Controller;

use DateTimeImmutable;
use Doctrine\DBAL\Connection;
use MeetupOrganizing\Entity\ScheduledDate;
use PDO;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\Stratigility\MiddlewareInterface;

final class ListMeetupsController implements MiddlewareInterface
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var TemplateRendererInterface
     */
    private $renderer;

    public function __construct(
        Connection $connection,
        TemplateRendererInterface $renderer
    ) {
        $this->connection = $connection;
        $this->renderer = $renderer;
    }

    public function __invoke(Request $request, Response $response, callable $out = null): ResponseInterface
    {
        $now = new DateTimeImmutable();

        $upcomingMeetups = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('meetups')
            ->where('scheduledFor >= :now')
            ->setParameter('now', $now->format(ScheduledDate::DATE_TIME_FORMAT))
            ->andWhere('wasCancelled = :wasNotCancelled')
            ->setParameter('wasNotCancelled', 0)
            ->execute()
            ->fetchAll(PDO::FETCH_ASSOC);

        $pastMeetups = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('meetups')
            ->where('scheduledFor < :now')
            ->setParameter('now', $now->format(ScheduledDate::DATE_TIME_FORMAT))
            ->andWhere('wasCancelled = :wasNotCancelled')
            ->setParameter('wasNotCancelled', 0)
            ->execute()
            ->fetchAll(PDO::FETCH_ASSOC);;

        $response->getBody()->write(
            $this->renderer->render(
                'list-meetups.html.twig',
                [
                    'upcomingMeetups' => $upcomingMeetups,
                    'pastMeetups' => $pastMeetups
                ]));

        return $response;
    }
}
