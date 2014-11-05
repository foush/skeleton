<?php

namespace Application\Controller\Api;

use FzyCommon\Util\Params;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

/**
 * Class AbstractController
 * @package Application\Controller\Api
 */
abstract class AbstractController extends AbstractActionController
{
	/**
	 * @return mixed
	 */
	abstract protected function getSearchServiceKey();

	/**
	 * @return mixed
	 */
	abstract protected function getUpdateServiceKey();


	/**
	 * @param Params $params
	 *
	 * @return \FzyCommon\Service\Search\ResultProviderInterface
	 */
	protected function getSearchService(Params $params)
	{
		return $this->getServiceLocator()->get($this->getSearchServiceKey());
	}

	/**
	 * @param Params $params
	 *
	 * @return \FzyCommon\Service\Update\Base
	 */
	protected function getUpdateService(Params $params)
	{
		/* @var $updateService \FzyCommon\Service\Update\Base */
		$updateService = $this->getServiceLocator()->get($this->getUpdateServiceKey());

		$searchService = $this->getServiceLocator()->get($this->getSearchServiceKey());

		$updateService->setMainEntityFromParam($params, $searchService);
		return $updateService;
	}

	/**
	 * @return JsonModel
	 */
	public function indexAction()
	{
		$params = Params::create($this->params(), $this->getRequest());
		/* @var $searchService \Application\Service\Search\Base */
		$searchService = $this->getSearchService($params);

		return new JsonModel($this->fzySearchResult($searchService->search($params)));
	}

	/**
	 * @return mixed
	 */
	public function updateAction()
	{
		/* @var $params \FzyCommon\Util\Params */
		$params = Params::create($this->params(), $this->getRequest());

		$updateService = $this->getUpdateService($params);
		$updateService->update($params);

		return new JsonModel($this->fzyUpdateResult($updateService));
	}

}
