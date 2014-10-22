<?php
namespace Application\Service\Search;

use Application\Exception\Search\InvalidResultOffset;
use Application\Exception\Search\NoResultsToGet;
use Application\Exception\Search\NotFound;
use Application\Service\Base as BaseService;
use Application\Util\Page;
use Application\Util\Param;

abstract class Base extends BaseService implements ResultProviderInterface
{
    /**
     * @var int
     */
    protected $total;

    /**
     * @var int
     */
    protected $limit;

    /**
     * @var int
     */
    protected $offset;

    /**
     * @var array
     */
    protected $results;

    /**
     * @var bool
     */
    protected $canCreate = false;

    /**
     * Resets any params or results currently in this object
     *
     * @return $this
     */
    public function reset()
    {
        unset($this->results);

        return $this;
    }

    /**
     * Main lifecycle of this service;
     * Using the passed in $params value
     * @param  Param $params
     * @param  bool  $asEntity - keep result as entity
     * @return $this
     */
    public function search(Param $params, $asEntity = false)
    {
        // hook to prepare for search
        $this->prepareSearch($params);

        // set limit/offset
        $this->setMetaData($params);

        if ($this->isSingular($params)) {
            try {
                $result = array($this->identitySearch($params));
                $this->setTotal(1);
            } catch (NotFound $e) {
                // nothing found
                $result = array();
                $this->setTotal(0);
            }
        } else {
            $result = $this->querySearch($params);
        }

        $this->preProcess($params, $result);
        $processed = array();
        foreach ($result as $entity) {
            // This can be revisited, as of 10/20/2014 reports are returning some irrelevant records
            // without an associated claim, so we need a way of filtering out that data
            if (($data = $this->process($entity, $params, $result, $asEntity)) && !empty($data)) {
                $processed[] = $this->process($entity, $params, $result, $asEntity);
            }
        }
        $this->postProcess($params, $result, $processed);
        $this->results = $processed;

        return $this;
    }

    /**
     * @param  Param                                  $params
     * @return \Application\Entity\BaseInterface
     * @throws \Application\Exception\Search\NotFound
     */
    public function identitySearch(Param $params)
    {
        if ($params->get($this->getIdParam()) == null) {
            return $this->createNewEntity($params);
        }

        return $this->find($params->get($this->getIdParam()));
    }

    protected function createNewEntity(Param $params)
    {
        if ($this->getCanCreate()) {
            $class = '\\' . $this->getRepository();

            return new $class();
        } else {
            throw new NotFound('Unable to locate this entity');
        }
    }

    /**
     * This function is a hook which allows any inheriting class
     * to perform necessary setup functions before the singular/query
     * search is performed
     * @param Param $params
     */
    protected function prepareSearch(Param $params)
    {

    }

    /**
     * Returns whether, based on the Param values
     * the
     * @param  Param $params
     * @return bool
     */
    protected function isSingular(Param $params)
    {
        return $params->has($this->getIdParam()) || $this->getCanCreate();
    }

    /**
     * This function should return the value of
     * the param name used to identify this search class' repository
     *
     * Eg: if this is a User subclass, $this->getIdParam() ought to return 'userId'
     * so the param array can check if 'userId' was set and therefore
     * indicate a lookup rather than a general search
     *
     * @return mixed
     */
    abstract protected function getIdParam();

    /**
     * Performs a query based on the params for a collection
     * of objects to be returned. This function ought to
     * set the $total value
     * @param  Param              $params
     * @return array|\Traversable
     */
    abstract protected function querySearch(Param $params);

    /**
     * Sets the limit/offset metadata
     * @param  Param $params
     * @return $this
     */
    protected function setMetaData(Param $params)
    {
        $this->setLimit(Page::limit($params));
        $this->setOffset(Page::offset($params));

        return $this;
    }

    /**
     * This function is a hook which allows any inheriting class
     * to perform necessary setup functions after the singular/query
     * search is performed, and before the results are processed
     * individually
     * @param Param              $params
     * @param array|\Traversable $result
     */
    protected function preProcess(Param $params, $result)
    {

    }

    /**
     * This is invoked on every result of the singular/query
     * search for uniformly transform the result set
     * @param $entity
     * @param  Param              $params
     * @param  array|\Traversable $results
     * @param  bool               $asEntity - Keep entity as entity
     * @return $entity
     */
    protected function process($entity, Param $params, $results, $asEntity = false)
    {
        return $asEntity ? $entity : (string) $entity;
    }

    /**
     * This function is a hook which allows any inheriting class
     * to perform necessary teardown/cleanup functions after the
     * singular/query search results have been processed
     * @param Param              $params
     * @param array|\Traversable $result
     * @param array              $processed
     */
    protected function postProcess(Param $params, $result, array $processed)
    {

    }

    /**
     * Finds an element in this domain which has the specified ID
     *
     * @param $id
     * @return mixed
     */
    abstract public function find($id);


    /**
     * Get the resulting set that matches the search
     * @return array|\Traversable
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * Convenience method for retrieving a single result (defaults to return the first)
     * @param  int                                               $offset
     * @return mixed
     * @throws \Application\Exception\Search\NoResultsToGet
     * @throws \Application\Exception\Search\InvalidResultOffset
     */
    public function getResult($offset = 0)
    {
        if (!is_array($this->results)) {
            throw new NoResultsToGet('No results have been generated');
        }
        if (!isset($this->results[$offset])) {
            throw new InvalidResultOffset("Unable to get result at offset '$offset'");
        }

        return $this->results[$offset];
    }

    /**
     * Get the current page's limit
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param $limit
     * @return $this
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * Get the current page's offset
     * @return int
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @param $offset
     * @return $this
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;

        return $this;
    }

    /**
     * Returns the reported total number of results available
     * @return int
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param $total
     * @return $this
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Returns an identifying name for this type of search
     * (so pages with multiple paginated data sets can generate events
     * about this data set being updated/modified)
     * @return string
     */
    abstract public function getResultTag();

    /**
     * @param boolean $canCreate
     */
    public function setCanCreate($canCreate)
    {
        $this->canCreate = $canCreate;
    }

    /**
     * @return boolean
     */
    public function getCanCreate()
    {
        return $this->canCreate;
    }
}
