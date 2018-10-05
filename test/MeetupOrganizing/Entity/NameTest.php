<?php
declare(strict_types = 1);

namespace MeetupOrganizing\Entity;

final class NameTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_wraps_a_string(): void
    {
        $nameText = 'Non-empty string';
        $name = Name::fromString($nameText);
        $this->assertEquals($nameText, (string)$name);
    }

    /**
     * @test
     */
    public function it_should_be_a_non_empty_string(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        Name::fromString('');
    }
}
