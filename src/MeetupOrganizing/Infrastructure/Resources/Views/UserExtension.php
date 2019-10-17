<?php
declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure\Resources\Views;

use MeetupOrganizing\Infrastructure\UserRepository;
use MeetupOrganizing\Infrastructure\Session;
use Twig_Extension;
use Twig_Extension_GlobalsInterface;

final class UserExtension extends Twig_Extension implements Twig_Extension_GlobalsInterface
{
    /**
     * @var Session
     */
    private $session;

    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(
        Session $session,
        UserRepository $userRepository
    ) {
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
