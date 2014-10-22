<?php

namespace Application\Entity\Base\User;

use Application\Entity\BaseInterface;
use BjyAuthorize\Acl\HierarchicalRoleInterface;

/**
 * An example entity that represents a role.
 *
 * @ORM\Entity
 * @ORM\Table(name="role")
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
interface RoleInterface extends  HierarchicalRoleInterface, BaseInterface
{
    /**
     * Get the id.
     *
     * @return int
     */
    public function getId();

    /**
     * Set the id.
     *
     * @param int $id
     *
     * @return $this
     */
    public function setId($id);

    /**
     * Get the role id.
     *
     * @return string
     */
    public function getRoleId();

    /**
     * Set the role id.
     *
     * @param string $roleId
     *
     * @return $this
     */
    public function setRoleId($roleId);

    /**
     * Get the parent role
     *
     * @return RoleInterface|null
     */
    public function getParent();

    /**
     * Set the parent role.
     *
     * @param Role $parent
     *
     * @return $this
     */
    public function setParent(RoleInterface $parent);

    /**
     * @param string $displayName
     */
    public function setDisplayName($displayName);

    /**
     * @return string
     */
    public function getDisplayName();
}
