<?php
namespace Application\Service\Search\Base\DQL;

use Application\Service\Search\Base\DQL as SearchService;
use Application\Util\Param;
use Doctrine\ORM\QueryBuilder;

class User extends SearchService
{
    /**
     * If there is some ordering that needs to be applied, do it here
     * @param Param        $params
     * @param QueryBuilder $qb
     */
    protected function addOrdering(Param $params, QueryBuilder $qb)
    {
        $this->orderByName($params, $qb);
    }

    /**
     * If there is some ordering that needs to be applied, do it here
     * @param Param        $params
     * @param QueryBuilder $qb
     */
    protected function orderByName(Param $params, QueryBuilder $qb)
    {
        if ($params->has('orderby')) {
            // TODO: Implement orderby
        } else {
            $qb->addOrderBy($this->alias('lastName'), 'ASC');
            $qb->addOrderBy($this->alias('firstName'), 'ASC');
        }

        return $this;
    }

    /**
     * @param  Param        $params
     * @param  QueryBuilder $qb
     * @return $this
     */
    protected function filterByStatus(Param $params, QueryBuilder $qb)
    {
        if ($params->has('state')) {
            $qb->andWhere($qb->expr()->like($this->alias('state'), ':state'))->setParameter('state', $params->has('state'));
        }

        return $this;
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
    protected function getIdParam()
    {
        return 'user';
    }

    /**
     * This function is used by the class to get
     * the entity's repository to be returned
     * @return mixed
     */
    protected function getRepository()
    {
        return 'Application\Entity\Base\User';
    }

    /**
     * Returns an identifying name for this type of search
     * (so pages with multiple paginated data sets can generate events
     * about this data set being updated/modified)
     * @return string
     */
    public function getResultTag()
    {
        return 'user';
    }

    /**
     * Alias for the primary repository in the DQL statement
     * @return string
     */
    public function getRepositoryAlias()
    {
        return 'u';
    }
}
