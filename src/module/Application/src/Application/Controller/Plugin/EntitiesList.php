<?php

namespace Application\Controller\Plugin;

/**
 * Class EntitiesList
 * @package Application\Controller\Plugin
 */
class EntitiesList extends Base
{
    /**
     *
     */
    const DEFAULT_KEY = 'id';
    /**
     *
     */
    const DEFAULT_ENTITY_MANAGER_SERVICE_KEY = 'em';

    /**
     * @var string
     */
    protected $serviceManagerKey = self::DEFAULT_ENTITY_MANAGER_SERVICE_KEY;

    /**
     * Simple functionality for generating a list(array) of
     * a given entity.
     *
     * @param $classPath Entity Class Path (\Application\Entity\Base\Company)
     * @param  array  $keys           id values (array of entity ids)
     * @param  string $key            (key/column/field) to use in DQL
     * @param  bool   $resultCallback callable function to manipulate results
     * @return array
     */
    public function __invoke($classPath, $keys = array(), $key = self::DEFAULT_KEY, $resultCallback = false)
    {
        $entities = $this->getEntities($classPath, $keys, $key);

        return $this->process($entities, $resultCallback);
    }

    /**
     * Get the entity manager, presumed to be em
     * @return mixed
     */
    protected function getEntityManager()
    {
        return $this->getController()->getServiceLocator()->get($this->getServiceManagerKey());
    }

    /**
     * Run DQL, get matching entities.
     *
     * @param $classPath Entity Class Path (\Application\Entity\Base\Company)
     * @param  array  $keys id values (array of entity ids)
     * @param  string $key  (key/column/field) to use in DQL
     * @return mixed
     */
    protected function getEntities($classPath, $keys = array(), $key = self::DEFAULT_KEY)
    {
        return
            $this
                ->getEntityManager()
                ->createQuery($this->generateDQLQueryString($classPath, $keys, $key))
                ->setParameter('ids', $keys)
                ->getResult();
    }

    /**
     * Generate DQL query
     *
     * @param $classPath Entity Class Path (\Application\Entity\Base\Company)
     * @param  array  $keys id values (array of entity ids)
     * @param  string $key  (key/column/field) to use in DQL
     * @return string
     */
    protected function generateDQLQueryString($classPath, $keys = array(), $key = self::DEFAULT_KEY)
    {
        return 'SELECT e FROM ' . $classPath . ' e WHERE e.' . $key .' IN (:ids)';
    }

    /**
     * Process entities into a result
     *
     * @param  array         $entities
     * @param  bool|callable $resultCallback
     * @return array
     */
    protected function process($entities = array(), $resultCallback = false)
    {
        $results = array();

        foreach ($entities as $entity) {
            if (is_callable($resultCallback)) {
                if (($result = $resultCallback($entity)) && !empty($result))
                    $results[] = $result;
            } else {
                $results[] = $entity;
            }
        }

        return $results;
    }

    /**
     * @param string $serviceManagerKey
     */
    public function setServiceManagerKey($serviceManagerKey = self::DEFAULT_ENTITY_MANAGER_SERVICE_KEY)
    {
        $this->serviceManagerKey = $serviceManagerKey;
    }

    /**
     * @return string
     */
    public function getServiceManagerKey()
    {
        return $this->serviceManagerKey;
    }
}
