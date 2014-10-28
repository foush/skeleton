<?php
namespace FzyAuth\Listener;

use Zend\Mvc\MvcEvent;
use Zend\View\Model\JsonModel;

class DispatchError extends Base
{

	/**
	 * @param MvcEvent $e
	 */
	public function latch( MvcEvent $e )
	{
		if ($this->getModuleConfig()->get('intercept_api_errors', true)) {
			$debug = $this->getModuleConfig()->get( 'debug', false );
			$this->latchTo( MvcEvent::EVENT_DISPATCH_ERROR, function ( MvcEvent $e ) use ( $debug ) {
				if ( $e->getRouteMatch() && ( $route = $e->getRouteMatch()->getMatchedRouteName() ) && ( $route == 'api' || strpos( $route,
							'api/' ) === 0 )
				) {
					/* @var $exception \Exception */
					$exception = $e->getParam( 'exception', new \Exception( 'Unknown Error', 500 ) );
					$viewData  = array(
						'exception' => array(
							'message' => $exception->getMessage(),
							'code'    => $exception->getCode(),
						),
					);
					if ( $debug ) {
						$viewData['exception']['trace'] = $exception->getTrace();
					}
					$view = new JsonModel( $viewData );
					$e->setViewModel( $view );
				}
			} );
		}
		return $this;
	}

}