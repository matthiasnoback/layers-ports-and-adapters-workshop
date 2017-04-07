<?php
declare(strict_types = 1);

namespace Meetup\Infrastructure\Web\Controller;

use Meetup\Domain\Model\MeetupId;
use Meetup\Infrastructure\Persistence\Filesystem\FilesystemMeetupRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

final class MeetupDetailsController
{
    /**
     * @var \Meetup\Infrastructure\Persistence\Filesystem\FilesystemMeetupRepository
     */
    private $meetupRepository;

    /**
     * @var TemplateRendererInterface
     */
    private $renderer;

    public function __construct(FilesystemMeetupRepository $meetupRepository, TemplateRendererInterface $renderer)
    {
        $this->meetupRepository = $meetupRepository;
        $this->renderer = $renderer;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $out = null): ResponseInterface
    {
        $meetup = $this->meetupRepository->byId(MeetupId::fromString($request->getAttribute('id')));

        $response->getBody()->write($this->renderer->render('meetup-details.html.twig', [
            'meetup' => $meetup
        ]));

        return $response;
    }
}
