<?php
declare(strict_types = 1);

namespace Tests\Integration\Meetup\Infrastructure;

use Meetup\Domain\MeetupRepository;
use Meetup\Infrastructure\Persistence\Filesystem\FilesystemBasedMeetupRepository;
use Meetup\Infrastructure\Persistence\InMemory\InMemoryMeetupRepository;
use Tests\Unit\Meetup\Domain\Util\MeetupFactory;

final class MeetupRepositoryTest extends \PHPUnit_Framework_TestCase
{
    public function meetupRepositories()
    {
        $filePath = tempnam(sys_get_temp_dir(), 'meetups');
        @unlink($filePath);

        return [
            [new InMemoryMeetupRepository()],
            [new FilesystemBasedMeetupRepository($filePath)]
        ];
    }

    /**
     * @dataProvider meetupRepositories
     * @test
     */
    public function it_persists_and_retrieves_meetups(MeetupRepository $repository)
    {
        $originalMeetup = MeetupFactory::someMeetup();
        $repository->add($originalMeetup);

        $restoredMeetup = $repository->byId($originalMeetup->id());

        $this->assertEquals($originalMeetup, $restoredMeetup);
    }

    /**
     * @dataProvider meetupRepositories
     * @test
     */
    public function its_initial_state_is_valid(MeetupRepository $repository)
    {
        $this->assertSame(
            [],
            $repository->upcomingMeetups(new \DateTimeImmutable())
        );
    }

    /**
     * @dataProvider meetupRepositories
     * @test
     */
    public function it_lists_upcoming_meetups(MeetupRepository $repository)
    {
        $now = new \DateTimeImmutable();
        $pastMeetup = MeetupFactory::pastMeetup();
        $repository->add($pastMeetup);
        $upcomingMeetup = MeetupFactory::upcomingMeetup();
        $repository->add($upcomingMeetup);

        $this->assertEquals(
            [
                $upcomingMeetup
            ],
            $repository->upcomingMeetups($now)
        );
    }

    /**
     * @dataProvider meetupRepositories
     * @test
     */
    public function it_lists_past_meetups(MeetupRepository $repository)
    {
        $now = new \DateTimeImmutable();
        $pastMeetup = MeetupFactory::pastMeetup();
        $repository->add($pastMeetup);
        $upcomingMeetup = MeetupFactory::upcomingMeetup();
        $repository->add($upcomingMeetup);

        $this->assertEquals(
            [
                $pastMeetup
            ],
            $repository->pastMeetups($now)
        );
    }
}
