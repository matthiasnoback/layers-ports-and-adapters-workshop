<?php

namespace MeetupOrganizing;

use MeetupOrganizing\Entity\UserId;
use MeetupOrganizing\Entity\UserRepository;
use PHPUnit_Framework_TestCase;

final class SessionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_will_return_user_1_as_logged_in_user_if_the_session_is_empty(): void
    {
        $userRepository = new UserRepository();
        $sessionData = [];
        $session = new Session($userRepository, $sessionData);

        self::assertEquals(
            $session->getLoggedInUser(),
            $userRepository->getById(UserId::fromInt(1))
        );
    }

    /**
     * @test
     */
    public function you_can_set_the_logged_in_user(): void
    {
        $userRepository = new UserRepository();
        $sessionData = [];
        $session = new Session($userRepository, $sessionData);

        $session->setLoggedInUserId(UserId::fromInt(2));

        self::assertEquals(
            $session->getLoggedInUser(),
            $userRepository->getById(UserId::fromInt(2))
        );
    }
}
