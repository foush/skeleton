<?php

namespace Application\Entity\Base\User;

use Application\Entity\Base as Entity;
use BjyAuthorize\Acl\HierarchicalRoleInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * An example entity that represents a role.
 *
 * @ORM\Entity
 * @ORM\Table(name="role")
 *
 */
class RoleNull extends Entity implements HierarchicalRoleInterface, RoleInterface
{
    /**
     * Get the id.
     *
     * @return int
     */
    public function getId()
    {
        return null;
    }

    /**
     * Set the id.
     *
     * @param int $id
     *
     * @return void
     */
    public function setId($id)
    {
        return $this;
    }

    /**
     * Get the role id.
     *
     * @return string
     */
    public function getRoleId()
    {
        return null;
    }

    /**
     * Set the role id.
     *
     * @param string $roleId
     *
     * @return void
     */
    public function setRoleId($roleId)
    {
        return $this;
    }

    /**
     * Get the parent role
     *
     * @return Role
     */
    public function getParent()
    {
        return null;
    }

    /**
     * Set the parent role.
     *
     * @param Role $parent
     *
     * @return void
     */
    public function setParent(RoleInterface $parent)
    {
        return $this;
    }

    /**
     * @param string $displayName
     */
    public function setDisplayName($displayName)
    {
        return $this;
    }

    /**
     * @return string
     */
    public function getDisplayName()
    {
        return null;
    }
}
