<?php

namespace Application\Entity\Base\User;

use Application\Entity\Base as Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * An example entity that represents a role.
 *
 * @ORM\Entity
 * @ORM\Table(name="role")
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class Role extends Entity implements RoleInterface
{

    /**
     * @var string
     * @ORM\Column(type="string", length=50, unique=true, nullable=true, name="role_id")
     */
    protected $roleId;

    /**
     * @var Role
     * @ORM\ManyToOne(targetEntity="Application\Entity\Base\User\Role")
     *
     */
    protected $parent;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, unique=true, nullable=true, name="display_name")
     */
    protected $displayName;

    /**
     * Get the id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id();
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
        $this->id = $id;

        return $this;
    }

    /**
     * Get the role id.
     *
     * @return string
     */
    public function getRoleId()
    {
        return $this->roleId;
    }

    /**
     * Set the role id.
     *
     * @param string $roleId
     *
     * @return $this
     */
    public function setRoleId($roleId)
    {
        $this->roleId = (string) $roleId;

        return $this;
    }

    /**
     * Get the parent role
     *
     * @return Role
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set the parent role.
     *
     * @param Role $parent
     *
     * @return $this
     */
    public function setParent(RoleInterface $parent)
    {
        $this->parent = $parent->asDoctrineProperty();

        return $this;
    }

    /**
     * @param string $displayName
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
    }

    /**
     * @return string
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * @return array
     */
    public function flatten()
    {
        return
            array_merge(
                parent::flatten(),
                array(
                    'roleId'      => $this->getRoleId(),
                    'displayName' => $this->getDisplayName(),
                    'parent'      =>
                        $this->getParent() ?
                            $this->getParent()->flatten() :
                            null,
                )
            );
    }
}
