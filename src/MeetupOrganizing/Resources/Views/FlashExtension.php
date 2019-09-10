<?php
declare(strict_types=1);

namespace MeetupOrganizing\Resources\Views;

use MeetupOrganizing\Session;
use Twig_Extension;
use Twig_Extension_GlobalsInterface;

final class FlashExtension extends Twig_Extension implements Twig_Extension_GlobalsInterface
{
    /**
     * @var Session
     */
    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function getGlobals()
    {
        return [
            'allFlashes' => $this->session->getFlashes()
        ];
    }
}
