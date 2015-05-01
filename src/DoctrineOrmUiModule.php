<?php

namespace Harmony\Doctrine;

use Interop\Framework\HttpModuleInterface;
use Interop\Container\ContainerInterface;
use Mouf\Mvc\Splash\Routers\SplashDefaultRouter;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * This module provides a hook for adding/modifying instances.
 */
class DoctrineOrmUiModule extends AbstractSilexModule implements HttpModuleInterface
{
    private $silexFrameworkModule;
    private $rootContainer;

    public function __construct(SilexFrameworkModule $silexFrameworkModule) {
        $this->silexFrameworkModule = $silexFrameworkModule;
    }

    public function getName()
    {
        return 'doctrine-orm-ui';
    }

    public function getContainer(ContainerInterface $rootContainer)
    {
        $this->rootContainer = $rootContainer;
        return null;
    }

    /* (non-PHPdoc)
     * @see \Interop\Framework\ModuleInterface::init()
     */
    public function init()
    {
        // Let's get the silex application
        $app = $this->getSilexApp();

        $app['doctrineEntitiesListController']->share(function($c) {
            return new DoctrineOrmUiModule($c->get('moufTemplate'));
        });

        // Let's add a route

        // TODO: define a controller here!
        $app->get('/dpctrine', function (Application $app) {
            return new Response('Hello world!');
        });
    }

    public function getHttpMiddleware(HttpKernelInterface $app) {
        return null;
    }
}
