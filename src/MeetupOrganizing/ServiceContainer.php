<?php
declare(strict_types=1);

namespace MeetupOrganizing;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Interop\Container\ContainerInterface;
use MeetupOrganizing\Command\ScheduleMeetupConsoleHandler;
use MeetupOrganizing\Controller\ListMeetupsController;
use MeetupOrganizing\Controller\MeetupDetailsController;
use MeetupOrganizing\Controller\RsvpForMeetupController;
use MeetupOrganizing\Controller\ScheduleMeetupController;
use MeetupOrganizing\Controller\SwitchUserController;
use MeetupOrganizing\Entity\MeetupRepository;
use MeetupOrganizing\Entity\RsvpRepository;
use MeetupOrganizing\Entity\UserRepository;
use MeetupOrganizing\Resources\Views\FlashExtension;
use MeetupOrganizing\Resources\Views\TwigTemplates;
use MeetupOrganizing\Resources\Views\UserExtension;
use Symfony\Component\Debug\Debug;
use Symfony\Component\Debug\ErrorHandler;
use Xtreamwayz\Pimple\Container;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;
use Zend\Expressive\Application;
use Zend\Expressive\Container\ApplicationFactory;
use Zend\Expressive\Helper\ServerUrlHelper;
use Zend\Expressive\Helper\UrlHelper;
use Zend\Expressive\Router\FastRouteRouter;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\Expressive\Twig\TwigRendererFactory;

final class ServiceContainer extends Container
{
    public function __construct(string $projectRootDir)
    {
        parent::__construct(
            [
                'project_root_dir' => $projectRootDir
            ]
        );

        /*
         * Not a best practice for containers, but hey.
         */
        Debug::enable();
        ErrorHandler::register();

        $this['config'] = function () {
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
                        $this[UserExtension::class],
                        $this[FlashExtension::class]
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
                    ],
                    [
                        'name' => 'rsvp_for_meetup',
                        'path' => '/rsvp-for-meetup',
                        'middleware' => RsvpForMeetupController::class,
                        'allowed_methods' => ['POST']
                    ]
                ]
            ];
        };

        /*
         * Zend Expressive Application
         */
        $this['Zend\Expressive\FinalHandler'] = function () {
            return function (RequestInterface $request, ResponseInterface $response, $err = null) {
                if ($err instanceof Throwable) {
                    throw $err;
                }
            };
        };
        $this[RouterInterface::class] = function () {
            return new FastRouteRouter();
        };
        $this[Application::class] = new ApplicationFactory();

        /*
         * Templating
         */
        $this[TemplateRendererInterface::class] = new TwigRendererFactory();
        $this[ServerUrlHelper::class] = function () {
            return new ServerUrlHelper();
        };
        $this[UrlHelper::class] = function (ContainerInterface $container) {
            return new UrlHelper($this[RouterInterface::class]);
        };
        $this[UserExtension::class] = function (ContainerInterface $container) {
            return new UserExtension(
                $this[Session::class],
                $this[UserRepository::class]
            );
        };
        $this[FlashExtension::class] = function (ContainerInterface $container) {
            return new FlashExtension(
                $this[Session::class]
            );
        };
        /*
         * Persistence
         */
        $this[Connection::class] = function () {
            return DriverManager::getConnection(
                [
                    'driver' => 'pdo_sqlite',
                    'path' => $this['project_root_dir'] . '/var/app.sqlite'
                ]
            );
        };
        $this[SchemaManager::class] = function (ContainerInterface $container) {
            return new SchemaManager($this[Connection::class]);
        };

        $this[UserRepository::class] = function () {
            return new UserRepository();
        };
        $this[RsvpRepository::class] = function (ContainerInterface $container) {
            return new RsvpRepository(
                $this[Connection::class]
            );
        };
        $this[MeetupRepository::class] = function (ContainerInterface $container) {
            return new MeetupRepository(
                $this[Connection::class]
            );
        };

        /*
         * Controllers
         */
        $this[Session::class] = function (ContainerInterface $container) {
            return new Session(
                $this[UserRepository::class]
            );
        };

        $this[ScheduleMeetupController::class] = function (ContainerInterface $container) {
            return new ScheduleMeetupController(
                $container->get(Session::class),
                $container->get(TemplateRendererInterface::class),
                $container->get(RouterInterface::class),
                $container->get(MeetupService::class)
            );
        };
        $this[ListMeetupsController::class] = function (ContainerInterface $container) {
            return new ListMeetupsController(
                $container->get(Connection::class),
                $container->get(TemplateRendererInterface::class)
            );
        };
        $this[MeetupDetailsController::class] = function (ContainerInterface $container) {
            return new MeetupDetailsController(
                $container->get(Connection::class),
                $container->get(UserRepository::class),
                $container->get(RsvpRepository::class),
                $container->get(TemplateRendererInterface::class)
            );
        };
        $this[SwitchUserController::class] = function (ContainerInterface $container) {
            return new SwitchUserController(
                $this[UserRepository::class],
                $this[Session::class]
            );
        };
        $this[RsvpForMeetupController::class] = function (ContainerInterface $container) {
            return new RsvpForMeetupController(
                $this[Connection::class],
                $this[Session::class],
                $this[RsvpRepository::class],
                $this[RouterInterface::class]
            );
        };

        /*
         * CLI
         */
        $this[ScheduleMeetupConsoleHandler::class] = function (ContainerInterface $container) {
            return new ScheduleMeetupConsoleHandler(
                $this[MeetupService::class]
            );
        };

        /*
         * Application services
         */
        $this[MeetupService::class] = function () {
            return new MeetupService(
                $this[UserRepository::class],
                $this[MeetupRepository::class]
            );
        };
    }
}
