<?php
namespace FzyAuth\Listener;

use Zend\Mvc\MvcEvent;

class Route extends Base
{

	public function latch( MvcEvent $e )
	{
		$this->latchTo(MvcEvent::EVENT_ROUTE, array($this, 'checkAcl'));
	}

	protected function checkAcl(MvcEvent $e)
	{
		$route = $e->getRouteMatch()->getMatchedRouteName();
		$acl = $this->getServiceLocator()->get('acl');
		$e->getViewModel()->setVariable('acl', $acl);
		// check if acl has resoucr "route"

		// get auth service

		// get whether user is logged in

		//
	}
}