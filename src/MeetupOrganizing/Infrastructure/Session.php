<?php
declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure;

use MeetupOrganizing\Domain\User;
use MeetupOrganizing\Domain\UserId;
use MeetupOrganizing\Domain\UserRepository;

final class Session
{
    private const DEFAULT_USER_ID = 1;
    private const LOGGED_IN_USER_ID = 'logged_in_userId';

    private UserRepository $userRepository;

    /**
     * @var array<string,mixed>
     */
    private array $sessionData;

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
                (int)$this->get(self::LOGGED_IN_USER_ID, self::DEFAULT_USER_ID)
            )
        );
    }

    public function setLoggedInUserId(UserId $id): void
    {
        $this->set(self::LOGGED_IN_USER_ID, $id->asInt());
    }

    /**
     * @return string|bool|int|null
     * @param string|bool|int|null $defaultValue
     */
    public function get(string $key, $defaultValue = null)
    {
        if (isset($this->sessionData[$key])) {
            return $this->sessionData[$key];
        }

        return $defaultValue;
    }

    /**
     * @param string|bool|int|null $value
     */
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
