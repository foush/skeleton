<?php

namespace Application\Controller\Api;

use Application\Util\Param;
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
     * @var
     */
    protected $searchService;

    /**
     * @return \Application\Service\Search\Base
     */
    protected function getSearchService()
    {
        if (empty($this->searchService)) {
            $this->searchService = $this->setupSearchService();
        }

        return $this->searchService;
    }

    /**
     * @return \Application\Service\Search\Base
     */
    protected function setupSearchService()
    {
        return $this->getServiceLocator()->get($this->getSearchServiceKey());
    }

    /**
     * @return JsonModel
     */
    public function indexAction()
    {
        /* @var $searchService \Application\Service\Search\Base */
        $searchService = $this->getSearchService();

        return new JsonModel($this->searchResult($searchService->search(Param::create($this->params(), $this->getRequest()))));
    }

    /**
     * @return mixed
     */
    public function updateAction()
    {
        /* @var $updater \Application\Service\Update */
        $updater = $this->getServiceLocator()->get($this->getUpdateServiceKey());
        /* @var $params \Application\Util\Param */
        $params = Param::create($this->params(), $this->getRequest());
        $updater->setMainEntityFromParam($params);
        $updater->update($params);

        return $this->updateResult($updater);
    }

}
