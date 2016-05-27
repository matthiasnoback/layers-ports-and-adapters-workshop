<?php

use Interop\Container\ContainerInterface;
use Meetup\Domain\Model\MeetupRepository;
use Meetup\Infrastructure\Persistence\Filesystem\FileBasedMeetupRepository;
use Meetup\Infrastructure\Web\ListMeetupsController;
use Meetup\Infrastructure\Web\ScheduleMeetupController;
use Meetup\Infrastructure\Web\View\TwigTemplates;
use Xtreamwayz\Pimple\Container;
use Zend\Expressive\Application;
use Zend\Expressive\Container\ApplicationFactory;
use Zend\Expressive\Helper\ServerUrlHelper;
use Zend\Expressive\Helper\UrlHelper;
use Zend\Expressive\Router\FastRouteRouter;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\Expressive\Twig\TwigRendererFactory;

$container = new Container();

$container['config'] = [
    'debug' => true,
    'templates' => [
        'extension' => 'html.twig',
        'paths' => [
            TwigTemplates::getPath()
        ]
    ],
    'twig' => [
        'extensions' => [
        ]
    ],
    'routes' => [
        [
            'name' => 'list_meetups',
            'path' => '/',
            'middleware' => ListMeetupsController::class,
            'allowed_methods' => ['GET']
        ],
        [
            'name' => 'schedule_meetup',
            'path' => '/schedule-meetup',
            'middleware' => ScheduleMeetupController::class,
            'allowed_methods' => ['GET', 'POST']
        ]
    ]
];

/*
 * Application
 */
$container['Zend\Expressive\FinalHandler'] = function () {
    return function () {
        throw func_get_args()[2];
    };
};
$container[RouterInterface::class] = function () {
    return new FastRouteRouter();
};
$container[Application::class] = new ApplicationFactory();

/*
 * Templating
 */
$container[TemplateRendererInterface::class] = new TwigRendererFactory();
$container[ServerUrlHelper::class] = function () {
    return new ServerUrlHelper();
};
$container[UrlHelper::class] = function (ContainerInterface $container) {
    return new UrlHelper($container[RouterInterface::class]);
};

/*
 * Persistence
 */
$container[MeetupRepository::class] = function () {
    return new FileBasedMeetupRepository(__DIR__ . '/../var/meetups.txt');
};

/*
 * Controllers
 */
$container[ScheduleMeetupController::class] = function (ContainerInterface $container) {
    return new ScheduleMeetupController(
        $container->get(TemplateRendererInterface::class),
        $container->get(RouterInterface::class),
        $container->get(MeetupRepository::class)
    );
};
$container[ListMeetupsController::class] = function (ContainerInterface $container) {
    return new ListMeetupsController(
        $container->get(MeetupRepository::class),
        $container->get(TemplateRendererInterface::class),
        $container->get(RouterInterface::class)
    );
};

/**
 * CLI
 */
$container[\Meetup\Infrastructure\Cli\ScheduleMeetupConsoleHandler::class] = function (ContainerInterface $container) {
    return new \Meetup\Infrastructure\Cli\ScheduleMeetupConsoleHandler(
        $container->get(MeetupRepository::class)
    );
};

return $container;
