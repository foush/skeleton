<?php
/**
 * Created by PhpStorm.
 * User: brians
 * Date: 8/13/14
 * Time: 4:55 PM
 */

namespace Application\Factory\Session;

use Application\Factory\BaseFactory;
use Zend\Session\Container;
use Application\Service\Session\Session;

/**
 * Class SessionFactory
 * @package Application\Factory\Session
 */
class SessionFactory extends BaseFactory
{
    /**
     *
     */
    const DEFAULT_CONTAINER_KEY = 'votr';

    /**
     * @var string
     */
    protected $containerKey;

    /**
     * @var string
     */
    protected $sessionKey;

    /**
     * @var string
     */
    protected $entityClass;

    /**
     * @param string $containerKey
     */
    public function setContainerKey($containerKey)
    {
        $this->containerKey = $containerKey;

        return $this;
    }

    /**
     * @return string
     */
    public function getContainerKey()
    {
        return
            isset($this->containerKey) ?
                $this->containerKey :
                self::DEFAULT_CONTAINER_KEY;
    }

    /**
     * @param $entityClass
     * @return $this
     */
    public function setentityClass($entityClass)
    {
        $this->entityClass = $entityClass;

        return $this;
    }

    /**
     * @return string
     */
    public function getentityClass()
    {
        return $this->entityClass;
    }

    /**
     * @param $sessionKey
     * @return $this
     */
    public function setSessionKey($sessionKey)
    {
        $this->sessionKey = $sessionKey;

        return $this;
    }

    /**
     * @return string
     */
    public function getSessionKey()
    {
        return $this->sessionKey;
    }

    /**
     * @return \Application\Service\Update\User
     */
    public function getService()
    {
        /* @var $service \Application\Service\Update\User */
        $service = new \Application\Service\Session\Session();
        $service->setServiceLocator($this->getServiceLocator());

        $container  = new Container($this->getContainerKey());

        $entityClass  = $this->getentityClass();//'Application\Entity\Base\Office';
        $sessionKey = $this->getSessionKey();//'office';

        $service->setContainer($container);
        $service->setentityClass($entityClass);
        $service->setSessionKey($sessionKey);

        return $service;

    }
}
