<?php
declare(strict_types=1);

namespace Meetup\Infrastructure\Common;

use Interop\Container\ContainerInterface;
use Meetup\Application\ScheduleMeetupHandler;
use Meetup\Domain\Model\MeetupRepository;
use Meetup\Infrastructure\UI\Web\MeetupDetailsController;
use Meetup\Infrastructure\Persistence\FileBased\FileBasedMeetupRepository;
use Meetup\Infrastructure\UI\Web\Resources\Views\TwigTemplates;
use Meetup\Infrastructure\UI\CLI\ScheduleMeetupConsoleHandler;
use Meetup\Infrastructure\UI\Web\ScheduleMeetupController;
use Meetup\Infrastructure\UI\CLI\MeetupApplicationConfig;
use Meetup\Infrastructure\UI\Web\ListMeetupsController;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Debug\Debug;
use Symfony\Component\Debug\ErrorHandler;
use Webmozart\Console\ConsoleApplication;
use Xtreamwayz\Pimple\Container;
use Zend\Expressive\Application;
use Zend\Expressive\Container\ApplicationFactory;
use Zend\Expressive\Helper\ServerUrlHelper;
use Zend\Expressive\Helper\UrlHelper;
use Zend\Expressive\Router\FastRouteRouter;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\Expressive\Twig\TwigRendererFactory;

final class MeetupApplicationContainer extends Container
{
    public function __construct(string $rootDir)
    {
        parent::__construct([
            'root_dir' => $rootDir
        ]);

        Debug::enable();
        ErrorHandler::register();

        $this['config'] = [
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
                    'name' => 'meetup_details',
                    'path' => '/meetup/{id:.+}',
                    'middleware' => MeetupDetailsController::class,
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
        $this['Zend\Expressive\FinalHandler'] = function () {
            return function (RequestInterface $request, ResponseInterface $response, $err = null) {
                if ($err instanceof \Throwable) {
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
            return new UrlHelper($container[RouterInterface::class]);
        };

        /*
         * Persistence
         */
        $this[MeetupRepository::class] = function (ContainerInterface $container) {
            return new FileBasedMeetupRepository($container['root_dir'] . '/var/meetups.txt');
        };

        /*
         * Use cases
         */
        $this[ScheduleMeetupHandler::class] = function(ContainerInterface $container) {
            return new ScheduleMeetupHandler(
                $container[MeetupRepository::class]
            );
        };

        /*
         * Controllers
         */
        $this[ScheduleMeetupController::class] = function (ContainerInterface $container) {
            return new ScheduleMeetupController(
                $container->get(TemplateRendererInterface::class),
                $container->get(RouterInterface::class),
                $container->get(ScheduleMeetupHandler::class),
                $container->get(MeetupRepository::class)
            );
        };
        $this[ListMeetupsController::class] = function (ContainerInterface $container) {
            return new ListMeetupsController(
                $container->get(MeetupRepository::class),
                $container->get(TemplateRendererInterface::class)
            );
        };
        $this[MeetupDetailsController::class] = function (ContainerInterface $container) {
            return new MeetupDetailsController(
                $container->get(MeetupRepository::class),
                $container->get(TemplateRendererInterface::class)
            );
        };

        /*
         * CLI
         */
        $this[ConsoleApplication::class] = function (ContainerInterface $container) {
            return new ConsoleApplication(new MeetupApplicationConfig($container));
        };

        $this[ScheduleMeetupConsoleHandler::class] = function (ContainerInterface $container) {
            return new ScheduleMeetupConsoleHandler(
                $container->get(ScheduleMeetupHandler::class),
                $container->get(MeetupRepository::class)
            );
        };
    }

    public function getConsoleApplication(): ConsoleApplication
    {
        return $this[ConsoleApplication::class];
    }

    public function getWebApplication(): Application
    {
        return $this[Application::class];
    }
}
