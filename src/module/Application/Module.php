<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\EventManager\StaticEventManager;
use Zend\Authentication\Result as AuthenticationResult;
use Application\Entity\Base\UserInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Session\Config\SessionConfig;
use Zend\Session\Container;
use Zend\Session\SessionManager;
use Zend\View\Model\JsonModel;

/**
 * Class Module
 * @package Application
 */
class Module
{
    /**
     * @param MvcEvent $e
     */
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $serviceManager      = $e->getApplication()->getServiceManager();

	    $sessionSetup = $serviceManager->get('session_setup')->init();

//        $eventManager->attach('route', array($this,'checkAuthenticated'));

	    /**
	     * In the event of an exception display a JSON response for any API requests
	     */
	    $eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, function(MvcEvent $e) {
		    if ($e->getRouteMatch() && ($route = $e->getRouteMatch()->getMatchedRouteName()) && ($route == 'api' || strpos($route, 'api/') === 0)) {
			    /* @var $exception \Exception */
			    $exception = $e->getParam('exception', new \Exception('Unknown Error', 500));
			    $view = new JsonModel(array(
				    'exception' => array(
					    'message' => $exception->getMessage(),
					    'code' => $exception->getCode(),
					    'trace' => $exception->getTrace(),
				    ),
			    ));

			    $e->setViewModel($view);
		    }
		    return $e;
	    });

    }


    /**
     * @return mixed
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}
