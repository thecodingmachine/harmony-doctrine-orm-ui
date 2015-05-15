<?php
namespace Harmony\Doctrine\Controllers;

use Harmony\Proxy\CodeProxy;
use Harmony\Services\ContainerExplorerService;
use Mouf\Html\HtmlElement\HtmlBlock;
use Mouf\Html\HtmlElement\HtmlElementInterface;
use Mouf\Html\Template\TemplateInterface;
use Mouf\Mvc\Splash\HtmlResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * A controller in charge of listing all entities.
 */
class EntitiesListController {

    /**
     * The template used by the main page for mouf.
     *
     * @var TemplateInterface
     */
    private $template;

    /**
     * The content block the template will be writting into.
     *
     * @var HtmlBlock
     */
    private $contentBlock;

    /**
     * The content block the template will be writting into.
     *
     * @var HtmlBlock
     */
    private $leftBlock;

    public function __construct(TemplateInterface $template, HtmlBlock $contentBlock, HtmlBlock $leftBlock)
    {
        $this->template = $template;
        $this->contentBlock = $contentBlock;
        $this->leftBlock = $leftBlock;
    }

    public function index(Request $request) {
        $instanceName = $request->get('name');

        $codeProxy = new CodeProxy();
        $entityClasses = $codeProxy->execute(function() use ($instanceName) {
            $containerExplorerService = ContainerExplorerService::create();
            $container = $containerExplorerService->getCompositeContainer();
            $entityManager = $container->get($instanceName);
            /* @var $entityManager \Doctrine\ORM\EntityManager */
            $metadata = $entityManager->getMetadataFactory()->getAllMetadata();

            $entityClasses = [];

            foreach ($metadata as $classMetaData) {
                /* @var $classMetaData \Doctrine\ORM\Mapping\ClassMetadata */
                $entityClasses[] = $classMetaData->getReflectionClass()->getName();
            }
            return $entityClasses;
        });


        $this->leftBlock->addText('Left block!');
        $this->contentBlock->addText('Hello world!<br/>'.var_export($entityClasses, true));
        return new HtmlResponse($this->template);
    }


}