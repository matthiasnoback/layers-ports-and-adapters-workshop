<?php

namespace Tests\Unit\Meetup\Domain\Model;

use Meetup\Domain\Model\Name;

class NameTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_wraps_a_string()
    {
        $nameText = 'Non-empty string';
        $name = Name::fromString($nameText);
        $this->assertEquals($nameText, (string)$name);
    }

    /**
     * @test
     */
    public function it_should_be_a_non_empty_string()
    {
        $this->expectException(\InvalidArgumentException::class);
        Name::fromString('');
    }
}
