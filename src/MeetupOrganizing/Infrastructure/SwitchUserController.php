<?php
declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure;

use Assert\Assert;
use MeetupOrganizing\Domain\UserId;
use MeetupOrganizing\Domain\UserRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use RuntimeException;
use Zend\Diactoros\Response\RedirectResponse;

final class SwitchUserController
{
    private UserRepository $userRepository;

    private Session $session;

    public function __construct(
        UserRepository $userRepository,
        Session $session
    ) {
        $this->session = $session;
        $this->userRepository = $userRepository;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next
    ): ResponseInterface {
        $postData = $request->getParsedBody();
        Assert::that($postData)->isArray();

        if (!isset($postData['userId'])) {
            throw new RuntimeException('Bad request');
        }

        $user = $this->userRepository->getById(
            UserId::fromInt((int)$postData['userId'])
        );
        $this->session->setLoggedInUserId($user->userId());

        return new RedirectResponse('/');
    }
}
