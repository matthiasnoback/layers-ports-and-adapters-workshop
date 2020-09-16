<?php
declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure\Resources\Views;

use MeetupOrganizing\Domain\UserRepository;
use MeetupOrganizing\Infrastructure\Session;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

final class UserExtension extends AbstractExtension implements GlobalsInterface
{
    private Session $session;

    private UserRepository $userRepository;

    public function __construct(Session $session, UserRepository $userRepository)
    {
        $this->session = $session;
        $this->userRepository = $userRepository;
    }

    public function getGlobals(): array
    {
        return [
            'loggedInUser' => $this->session->getLoggedInUser(),
            'allUsers' => $this->userRepository->findAll()
        ];
    }
}
