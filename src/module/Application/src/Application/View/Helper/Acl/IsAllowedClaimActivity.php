<?php

namespace Application\View\Helper\Acl;

use Application\Entity\Base\UserInterface as User;
use Application\Entity\BaseInterface as Entity;
use Application\Entity\Base\ClaimInterface as Claim;
use Application\View\Helper\Base;
use BjyAuthorize\View\Helper\IsAllowed;

/**
 * Class IsAllowedClaim
 * @package Application\View\Helper\Acl
 */
class IsAllowedClaimActivity extends IsAllowedEntity
{
    /**
     *
     */
    const DEFAULT_RESOURCE = 'claimActivity';

    /**
     * @param Entity $entity
     * @param User   $user
     * @param string $mode
     *
     * @return bool
     */
    public function __invoke(Entity $entity, User $user, $mode = self::MODE_VIEW)
    {
        // This applies to claims only
        if (!($entity instanceof ClaimActivity || $entity instanceof Claim)) {
            return false;
        }

        // Mode is not supported, return false
        if (!$this->isAllowedMode($mode)) {
            return false;
        }

        // Can this user {mode} all claims?
        if ($this->allowAll($entity, $user, $mode)) {
            return true;
        }

        // Not creator, not super, can the user {mode} claims for his/her office?
        if ($this->allowForOffice($entity, $user, $mode)) {
            return true;
        }

        // Is this user the adjuster linked to this entity?
        if ($this->isAllowed($entity, $user, $mode) && $this->allowForAuthorizedAdjuster($entity, $user, $mode)) {
            return true;
        }

        // If you get here, you lose
        return false;
    }

    /**
     * @param $mode
     * @return bool
     */
    public function isAllowedMode($mode)
    {
        return parent::isAllowedMode($mode) || $mode == 'add';
    }

    /**
     * @param  Entity $entity
     * @param  User   $user
     * @param $mode
     * @return bool
     */
    protected function allowForAuthorizedAdjuster(Entity $entity, User $user, $mode)
    {
        if ($entity instanceof Claim) {
            return $user->getAuthorizedAdjuster()->id() == $entity->getAdjuster()->id();
        } else {
            return true;
        }

    }
}
