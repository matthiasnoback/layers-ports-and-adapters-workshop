<?php
declare(strict_types=1);

namespace MeetupOrganizing\Resources\Views;

use MeetupOrganizing\Session;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

final class FlashExtension extends AbstractExtension implements GlobalsInterface
{
    private Session $session;

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
