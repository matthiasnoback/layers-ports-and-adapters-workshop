<?php
declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure\Resources\Views;

use MeetupOrganizing\Infrastructure\Session;
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
