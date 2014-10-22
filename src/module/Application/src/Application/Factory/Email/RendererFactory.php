<?php

namespace Application\Factory\Email;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Renderer\PhpRenderer;

/**
 * Class PasswordResetFactory
 * @package Application\Factory\Email
 */
class RendererFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $renderer = new PhpRenderer();

        $helperManager = $serviceLocator->get('ViewHelperManager');
        $resolver      = $serviceLocator->get('ViewResolver');

        $renderer->setHelperPluginManager($helperManager);
        $renderer->setResolver($resolver);

        $application = $serviceLocator->get('Application');
        $event       = $application->getMvcEvent();

        if ($event !== null) {
            $model = $serviceLocator->get('Application')->getMvcEvent()->getViewModel();

            $modelHelper = $renderer->plugin('view_model');
            $modelHelper->setRoot($model);
        }

        return $renderer;
    }
}
