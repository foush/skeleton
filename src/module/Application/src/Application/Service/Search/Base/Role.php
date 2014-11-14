<?php
namespace Application\Service\Search\Base;

use Application\Exception\Search\NotFound;
use FzyCommon\Service\Search\Base;
use FzyCommon\Util\Params;

class Role extends Base
{
    public static $list = array(
        'user' => 'User',
        'admin' => 'Administrator',
    );

    public static function get()
    {
        return self::$list;
    }

    public static function transform($roleId, $displayName)
    {
        return array(
            'roleId' => $roleId,
            'displayName' => $displayName,
        );
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
        return 'roleId';
    }

    /**
     * Performs a query based on the params for a collection
     * of objects to be returned. This function ought to
     * set the $total value
     * @param  Params             $params
     * @return array|\Traversable
     */
    protected function querySearch(Params $params)
    {
        $results = array();
        $query = $params->has('query') ? $this->sanitize($params->get('query')) : '';
        $key   = $params->has('key') ? $params->get('key') : false;

        foreach (self::$list as $roleId => $displayName) {
            if (empty($query) || strpos($this->sanitize($roleId), $query) !== false || strpos($this->sanitize($displayName), $query) !== false) {
                $results[] = self::transform($roleId, $displayName);
            }
        }
        $this->setTotal(count($results));

        return array_slice($results, $this->getOffset(), $this->getLimit());
    }

    /**
     * Removes all spaces and non-word characters, sets everything to lower case
     * @param $str
     * @return string
     */
    protected function sanitize($str)
    {
        return strtolower(preg_replace('/\W+/is', '', $str));
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
    protected function process($entity, Params $params, $results, $asEntity = false)
    {
        return $entity;
    }

    /**
     * Finds an element in this domain which has the specified ID
     *
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        if (!isset(self::$list[$id])) {
            throw new NotFound("No role found for ".$id);
        }

        return self::$list[$id];
    }

    /**
     * Returns an identifying name for this type of search
     * (so pages with multiple paginated data sets can generate events
     * about this data set being updated/modified)
     * @return string
     */
    public function getResultTag()
    {
        return 'role';
    }
}
