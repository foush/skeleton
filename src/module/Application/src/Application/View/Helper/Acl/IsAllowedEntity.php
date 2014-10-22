<?php

namespace Application\View\Helper\Acl;

use Application\Entity\Base\UserInterface as User;
use Application\Entity\BaseInterface as Entity;
use Application\View\Helper\Base;
use BjyAuthorize\View\Helper\IsAllowed;
use BjyAuthorize\Service\Authorize;

/**
 * Class IsAllowedEntity
 * @package Application\View\Helper\Acl
 */
class IsAllowedEntity extends Base
{
    /**
     *
     */
    const DEFAULT_RESOURCE = 'base';

    /**
     *
     */
    const MODE_VIEW        = 'view';

    /**
     *
     */
    const MODE_EDIT        = 'edit';

    /**
     * @var string
     */
    protected $resource;

    /**
     * @var Authorize
     */
    protected $authorizeService;

    /**
     * @param Entity $entity
     * @param User   $user
     * @param string $mode
     *
     * @return bool
     */
    public function __invoke(Entity $entity, User $user, $mode = self::MODE_VIEW)
    {
        // Mode is not supported, return false
        if (!$this->isAllowedMode($mode)) {
            return false;
        }

        // Is this the creator?
        if ($this->allowForCreator($entity, $user, $mode)) {
            return true;
        }

        // Can this user {mode} all claims?
        if ($this->allowAll($entity, $user, $mode)) {
            return true;
        }

        // Not creator, not super, can the user {mode} claims for his/her office?
        if ($this->allowForOffice($entity, $user, $mode)) {
           return true;
        }

        // If you get here, you lose
        return false;
    }

    /**
     * @param  Entity $entity
     * @param  User   $user
     * @param $mode
     * @return bool
     */
    protected function allowAll(Entity $entity, User $user, $mode)
    {
        return $this->getAuthorizeService()->isAllowed($this->getResource(), $mode .'All');
    }

    /**
     * @param  Entity $entity
     * @param  User   $user
     * @param $mode
     * @return bool
     */
    protected function allowForOffice(Entity $entity, User $user, $mode)
    {
        return
            $this->getAuthorizeService()->isAllowed($this->getResource(), $mode .'AllForOffice') &&
            $user->getOfficeLocation()->id() == $entity->getOffice()->id();
    }

    /**
     * @param  Entity $entity
     * @param  User   $user
     * @param $mode
     * @return bool
     */
    protected function isAllowed(Entity $entity, User $user, $mode)
    {
        return $this->getAuthorizeService()->isAllowed($this->getResource(), $mode);
    }

    /**
     * @param  Entity $entity
     * @param  User   $user
     * @param $mode
     * @return bool
     */
    protected function allowForCreator(Entity $entity, User $user, $mode)
    {
        return $user->id() == $entity->getCreatedBy()->id();
    }

    /**
     * @return array
     */
    public function getAllowedModes()
    {
        return array(self::MODE_VIEW, self::MODE_EDIT);
    }

    /**
     * @param $mode
     * @return bool
     */
    public function isAllowedMode($mode)
    {
        return in_array($mode, $this->getAllowedModes());
    }

    /**
     * @param \BjyAuthorize\Service\Authorize $authorizeService
     */
    public function setAuthorizeService($authorizeService)
    {
        $this->authorizeService = $authorizeService;
    }

    /**
     * @return \BjyAuthorize\Service\Authorize
     */
    public function getAuthorizeService()
    {
        return
            $this->hasService('BjyAuthorize\Service\Authorize') && empty($this->authorizeService) ?
                $this->getService('BjyAuthorize\Service\Authorize') :
                $this->authorizeService;
    }

    /**
     * @param string $resource
     */
    public function setResource($resource)
    {
        $this->resource = $resource;
    }

    /**
     * @return string
     */
    public function getResource()
    {
        return
            empty($this->resource) ?
                static::DEFAULT_RESOURCE :
                $this->resource;
    }
}
