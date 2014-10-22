<?php

namespace Application\Service\Session;

use Application\Service\Base;
use Zend\Session\Container;

/**
 * Class EntityToForm
 * @package Application\Service
 *
 * Service Key: entity_to_form
 *
 */
class Session extends Base
{
    /**
     * @var \Zend\Session\Container
     */
    protected $container;

    /**
     * @var string
     */
    protected $sessionKey;

    /**
     * @var string
     */
    protected $entityClass;

    /**
     * @param mixed $entity
     */
    public function setentityClass($entityClass)
    {
        $this->entityClass = $entityClass;
    }

    /**
     * @return mixed
     */
    public function getentityClass()
    {
        return $this->entityClass;
    }

    /**
     * @param string $sessionKey
     */
    public function setSessionKey($sessionKey)
    {
        $this->sessionKey = $sessionKey;
    }

    /**
     * @return string
     */
    public function getSessionKey()
    {
        return $this->sessionKey;
    }

    /**
     * @param $container
     */
    public function setContainer($container)
    {
        $this->container = $container;
    }

    /**
     * @return mixed
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param $id
     */
    public function set($id)
    {
        // Get the user entity (should exist now since we're only looking at successful logins
        $em     = $this->getServiceLocator()->get('em');
        $entity = $em->getRepository($this->getentityClass())->find($id);
        if (isset($entity) && isset($this->container)) {
            $this->container->{$this->sessionKey} = $entity->flatten();
        }
    }

    /**
     * @param $id
     */
    public function clear()
    {
        unset($this->container->{$this->sessionKey});
    }

    /**
     * @return mixed
     */
    public function get()
    {
        if (isset($this->container) && isset($this->container->{$this->sessionKey})) {
            return $this->container->{$this->sessionKey};
        }
    }

    /**
     * @return Application\Entity\Base
     */
    public function getEntity()
    {
        $em = $this->getServiceLocator()->get('em');

        $data = $this->get();

        if (isset($data['id'])) {
            return $em->getRepository($this->getentityClass())->find($data['id']);
        } else {
            return null;
        }
    }
}
