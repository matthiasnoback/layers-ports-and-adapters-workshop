<?php
declare(strict_types=1);

namespace MeetupOrganizing;

use MeetupOrganizing\Entity\User;
use MeetupOrganizing\Entity\UserId;
use MeetupOrganizing\Entity\UserRepository;

final class Session
{
    private const DEFAULT_userId = 1;
    private const LOGGED_IN_userId = 'logged_in_userId';

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var array
     */
    private $sessionData;

    public function __construct(UserRepository $userRepository)
    {
        if (php_sapi_name() === 'cli') {
            $this->sessionData = [];
        } else {
            session_start();
            $this->sessionData = &$_SESSION;
        }

        $this->userRepository = $userRepository;
    }

    public function getLoggedInUser(): User
    {
        return $this->userRepository->getById(
            UserId::fromInt(
                (int)$this->get(self::LOGGED_IN_userId, self::DEFAULT_userId)
            )
        );
    }

    public function setLoggedInUserId(UserId $id): void
    {
        $this->set(self::LOGGED_IN_userId, $id->asInt());
    }

    public function get(string $key, $defaultValue = null)
    {
        if (isset($this->sessionData[$key])) {
            return $this->sessionData[$key];
        }

        return $defaultValue;
    }

    public function set(string $key, $value): void
    {
        $this->sessionData[$key] = $value;
    }

    public function addErrorFlash(string $message): void
    {
        $this->addFlash('danger', $message);
    }

    public function addSuccessFlash(string $message): void
    {
        $this->addFlash('success', $message);
    }

    private function addFlash(string $type, string $message): void
    {
        $this->sessionData['flashes'][$type][] = $message;
    }

    public function getFlashes(): array
    {
        $flashes = $this->sessionData['flashes'] ?? [];

        $this->sessionData['flashes'] = [];

        return $flashes;
    }
}
