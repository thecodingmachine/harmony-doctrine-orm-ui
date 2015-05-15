<?php

namespace Harmony\Doctrine;

use Harmony\Doctrine\Controllers\EntitiesListController;
use Harmony\Services\MenuService;
use Interop\Container\ContainerInterface;
use Interop\Framework\ModuleInterface;
use Interop\Framework\Silex\AbstractSilexModule;
use Silex\Application;
use Silex\Provider\ServiceControllerServiceProvider;
use Symfony\Component\HttpFoundation\Response;

/**
 * This module provides a hook for adding/modifying instances.
 */
class DoctrineOrmUiModule extends AbstractSilexModule implements ModuleInterface
{
    private $rootContainer;

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
        // The MenuService class is a helper class to edit the menu.
        $menuService = new MenuService($this->rootContainer);
        // Let's get an instance of the main menu.
        $mainMenu = $menuService->getMainMenu();
        // Let's register a "Doctrine" menu in this main menu.
        $doctrineMainMenu = $menuService->registerMenuItem("Doctrine", null, $mainMenu);
        // Finally, let's register a "Main doctrine page" menu in the Doctrine menu.
        $menuService->registerChooseInstanceMenuItem("Entities list", "doctrine", "Doctrine\\ORM\\EntityManager",
            $doctrineMainMenu);

        // Let's get the silex application
        $app = $this->getSilexApp();

        $app->register(new ServiceControllerServiceProvider());

        $app['doctrineEntitiesListController'] = $app->share(function($c) {
            return new EntitiesListController($c['moufTemplate'], $c['block.content'], $c['block.left']);
        });

        // Let's add a route

        $app->get('/doctrine', 'doctrineEntitiesListController:index');
    }

}
