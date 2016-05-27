<?php

namespace Meetup\Domain\Model;

use Assert\Assertion;

final class Name
{
    private $text;

    public static function fromString($text)
    {
        $name = new self();

        Assertion::notEmpty($text);
        $name->text = $text;

        return $name;
    }

    public function __toString()
    {
        return $this->text;
    }
}
