<?php
declare(strict_types=1);

namespace MeetupOrganizing;

use MeetupOrganizing\Entity\User;
use MeetupOrganizing\Entity\UserId;
use MeetupOrganizing\Entity\UserRepository;

final class Session
{
    private const DEFAULT_USER_ID = 1;
    private const LOGGED_IN_USER_ID = 'logged_in_user_id';
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var array
     */
    private $session;

    public function __construct(UserRepository $userRepository, array &$session)
    {
        $this->session = $session;
        $this->userRepository = $userRepository;
    }

    public function start()
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }

        if (php_sapi_name() === 'cli') {
            return;
        }

        session_start();
    }

    public function getLoggedInUser(): User
    {
        return $this->userRepository->getById(
            UserId::fromInt(
                (int)$this->get(self::LOGGED_IN_USER_ID, self::DEFAULT_USER_ID)
            )
        );
    }

    public function setLoggedInUserId(UserId $id): void
    {
        $this->set(self::LOGGED_IN_USER_ID, $id->asInt());
    }

    public function get(string $key, $defaultValue = null)
    {
        $this->start();

        if (isset($this->session[$key])) {
            return $this->session[$key];
        }

        return $defaultValue;
    }

    public function set(string $key, $value): void
    {
        $this->start();

        $this->session[$key] = $value;
    }
}
