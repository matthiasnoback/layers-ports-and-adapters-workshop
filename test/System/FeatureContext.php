<?php

namespace Tests\System;

use Behat\Behat\Context\Context;
use Meetup\Domain\Model\MeetupRepository;

final class FeatureContext implements Context
{
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @BeforeFeature
     */
    public static function purgeDatabase(): void
    {
        $container = require __DIR__ . '/../../app/container.php';

        /** @var \Meetup\Domain\Model\MeetupRepository $meetupRepository */
        $meetupRepository = $container[MeetupRepository::class];
        $meetupRepository->deleteAll();
    }
}
