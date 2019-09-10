<?php

use Doctrine\DBAL\DriverManager;
use Interop\Container\ContainerInterface;
use MeetupOrganizing\Command\ScheduleMeetupConsoleHandler;
use MeetupOrganizing\Controller\MeetupDetailsController;
use MeetupOrganizing\Controller\SwitchUserController;
use MeetupOrganizing\Entity\MeetupRepository;
use MeetupOrganizing\Controller\ListMeetupsController;
use MeetupOrganizing\Controller\ScheduleMeetupController;
use MeetupOrganizing\Entity\UserRepository;
use MeetupOrganizing\Resources\Views\TwigTemplates;
use MeetupOrganizing\Resources\Views\UserExtension;
use MeetupOrganizing\Session;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Debug\Debug;
use Symfony\Component\Debug\ErrorHandler;
use Xtreamwayz\Pimple\Container;
use Zend\Expressive\Application;
use Zend\Expressive\Container\ApplicationFactory;
use Zend\Expressive\Helper\ServerUrlHelper;
use Zend\Expressive\Helper\UrlHelper;
use Zend\Expressive\Router\FastRouteRouter;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\Expressive\Twig\TwigRendererFactory;

Debug::enable();
ErrorHandler::register();

$container = new Container();

$container['config'] = function () use ($container) {
    return [
        'debug' => true,
        'templates' => [
            'extension' => 'html.twig',
            'paths' => [
                TwigTemplates::getPath()
            ]
        ],
        'twig' => [
            'extensions' => [
                $container[UserExtension::class]
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
                'name' => 'meetup_details',
                'path' => '/meetup/{id}',
                'middleware' => MeetupDetailsController::class,
                'allowed_methods' => ['GET']
            ],
            [
                'name' => 'schedule_meetup',
                'path' => '/schedule-meetup',
                'middleware' => ScheduleMeetupController::class,
                'allowed_methods' => ['GET', 'POST']
            ],
            [
                'name' => 'switch_user',
                'path' => '/switch-user',
                'middleware' => SwitchUserController::class,
                'allowed_methods' => ['POST']
            ]
        ]
    ];
};

/*
 * Zend Expressive Application
 */
$container['Zend\Expressive\FinalHandler'] = function () {
    return function (RequestInterface $request, ResponseInterface $response, $err = null) {
        if ($err instanceof Throwable) {
            throw $err;
        }
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
$container[UserExtension::class] = function (ContainerInterface $container) {
    return new UserExtension(
        $container[Session::class],
        $container[UserRepository::class]
    );
};

/*
 * Persistence
 */
$container[MeetupRepository::class] = function () {
    return new MeetupRepository(
        DriverManager::getConnection(
            [
                'driver' => 'pdo_sqlite',
                'path' => __DIR__ . '/../var/app.sqlite'
            ]
        )
    );
};
$container[UserRepository::class] = function () {
    return new UserRepository();
};

/*
 * Controllers
 */
$container[Session::class] = function (ContainerInterface $container) {
    return new Session(
        $container[UserRepository::class]
    );
};

$container[ScheduleMeetupController::class] = function (ContainerInterface $container) {
    return new ScheduleMeetupController(
        $container->get(Session::class),
        $container->get(TemplateRendererInterface::class),
        $container->get(RouterInterface::class),
        $container->get(MeetupRepository::class)
    );
};
$container[ListMeetupsController::class] = function (ContainerInterface $container) {
    return new ListMeetupsController(
        $container->get(MeetupRepository::class),
        $container->get(UserRepository::class),
        $container->get(TemplateRendererInterface::class)
    );
};
$container[MeetupDetailsController::class] = function (ContainerInterface $container) {
    return new MeetupDetailsController(
        $container->get(MeetupRepository::class),
        $container->get(UserRepository::class),
        $container->get(TemplateRendererInterface::class)
    );
};
$container[SwitchUserController::class] = function (ContainerInterface $container) {
    return new SwitchUserController(
        $container[UserRepository::class],
        $container[Session::class]
    );
};

/*
 * CLI
 */
$container[ScheduleMeetupConsoleHandler::class] = function (ContainerInterface $container) {
    return new ScheduleMeetupConsoleHandler(
        $container->get(MeetupRepository::class)
    );
};

return $container;
