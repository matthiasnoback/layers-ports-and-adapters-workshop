<?php
declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure;

use Assert\Assert;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use MeetupOrganizing\Infrastructure\DevelopmentContainer;
use MeetupOrganizing\Application\MeetupOrganizingInterface;
use MeetupOrganizing\Domain\UserRepository;
use MeetupOrganizing\Infrastructure\Resources\Views\FlashExtension;
use MeetupOrganizing\Infrastructure\Resources\Views\TwigTemplates;
use MeetupOrganizing\Infrastructure\Resources\Views\UserExtension;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\ErrorHandler\Debug;
use Throwable;
use Xtreamwayz\Pimple\Container;
use Zend\Expressive\Application;
use Zend\Expressive\Container\ApplicationFactory;
use Zend\Expressive\Helper\ServerUrlHelper;
use Zend\Expressive\Helper\UrlHelper;
use Zend\Expressive\Router\FastRouteRouter;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\Expressive\TemplatedErrorHandler;
use Zend\Expressive\Twig\TwigRendererFactory;

final class ServiceContainer extends Container
{
    public function __construct(string $projectRootDir)
    {
        Assert::that($projectRootDir)->directory();

        parent::__construct(
            [
                'project_root_dir' => $projectRootDir
            ]
        );

        /*
         * Not a best practice for containers, but hey.
         */
        Debug::enable();

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
                        'name' => 'cancel_meetup',
                        'path' => '/cancel-meetup',
                        'middleware' => CancelMeetupController::class,
                        'allowed_methods' => ['POST']
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
            return function (
                RequestInterface $request,
                ResponseInterface $response,
                $err = null
            ) {
                if ($err instanceof Throwable) {
                    throw $err;
                }

                return $this[TemplatedErrorHandler::class]($request, $response, $err);
            };
        };
        $this[TemplatedErrorHandler::class] = function () {
            return new TemplatedErrorHandler(
                $this[TemplateRendererInterface::class],
                'error404.html.twig',
                'error.html.twig'
            );
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
        $this[UrlHelper::class] = function () {
            return new UrlHelper($this[RouterInterface::class]);
        };
        $this[UserExtension::class] = function () {
            return new UserExtension(
                $this[Session::class],
                $this[UserRepository::class]
            );
        };
        $this[FlashExtension::class] = function () {
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
        $this[SchemaManager::class] = function () {
            return new SchemaManager($this[Connection::class]);
        };

        $this[UserRepository::class] = function () {
            return new UserRepository();
        };

        /*
         * Controllers
         */
        $this[Session::class] = function () {
            return new Session(
                $this[UserRepository::class]
            );
        };

        $this[ScheduleMeetupController::class] = function () {
            return new ScheduleMeetupController(
                $this[Session::class],
                $this[TemplateRendererInterface::class],
                $this[RouterInterface::class],
                $this[MeetupOrganizingInterface::class]
            );
        };
        $this[CancelMeetupController::class] = function () {
            return new CancelMeetupController(
                $this[Session::class],
                $this[RouterInterface::class],
                $this[MeetupOrganizingInterface::class]
            );
        };
        $this[ListMeetupsController::class] = function () {
            return new ListMeetupsController(
                $this[MeetupOrganizingInterface::class],
                $this[TemplateRendererInterface::class]
            );
        };
        $this[MeetupDetailsController::class] = function () {
            return new MeetupDetailsController(
                $this[Connection::class],
                $this[UserRepository::class],
                $this[TemplateRendererInterface::class]
            );
        };
        $this[SwitchUserController::class] = function () {
            return new SwitchUserController(
                $this[UserRepository::class],
                $this[Session::class]
            );
        };
        $this[RsvpForMeetupController::class] = function () {
            return new RsvpForMeetupController(
                $this[Session::class],
                $this[RouterInterface::class],
                $this[MeetupOrganizingInterface::class]
            );
        };

        /*
         * CLI
         */
        $this[ConsoleApplication::class] = function () {
            return new ConsoleApplication($this);
        };

        $this[ScheduleMeetupCommand::class] = function () {
            return new ScheduleMeetupCommand($this[MeetupOrganizingInterface::class]);
        };

        /*
         * Application services
         */
        $this[MeetupOrganizingInterface::class] = function () {
            return (new DevelopmentContainer($this[Connection::class], $this[Session::class]))->meetupOrganizing();
        };

        $this->bootstrap();
    }

    private function bootstrap(): void
    {
        $this[SchemaManager::class]->updateSchema();
    }
}
