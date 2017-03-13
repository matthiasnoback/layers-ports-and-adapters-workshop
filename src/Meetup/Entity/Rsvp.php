<?php
declare(strict_types = 1);

namespace Meetup\Entity;

use Assert\Assertion;

class Rsvp
{
    const YES = 'yes';
    const NO = 'no';

    /**
     * @var MeetupId
     */
    private $meetupId;

    /**
     * @var MemberId
     */
    private $memberId;

    /**
     * @var string
     */
    private $answer;

    /**
     * @param MeetupId $meetupId
     * @param MemberId $memberId
     * @param string $answer
     */
    public function __construct(MeetupId $meetupId, MemberId $memberId, $answer)
    {
        $this->meetupId = $meetupId;
        $this->memberId = $memberId;

        Assertion::inArray($answer, [self::YES, self::NO]);
        $this->answer = $answer;
    }

    public function meetupId(): MeetupId
    {
        return $this->meetupId;
    }

    public function memberId(): MemberId
    {
        return $this->memberId;
    }

    public function answer(): string
    {
        return $this->answer;
    }
}
