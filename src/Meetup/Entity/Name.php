<?php
declare(strict_types = 1);

namespace Meetup\Entity;

use Assert\Assertion;

final class Name
{
    private $text;

    public static function fromString($text): Name
    {
        $name = new self();

        Assertion::notEmpty($text);
        $name->text = $text;

        return $name;
    }

    public function __toString(): string
    {
        return $this->text;
    }
}
