<?php
namespace Application\Entity\Base;

use Application\Entity\BaseInterface;
use ZfcUser\Entity\UserInterface as ZfcUserInterface;
use BjyAuthorize\Provider\Role\ProviderInterface;

interface UserInterface extends BaseInterface, ZfcUserInterface, ProviderInterface
{
    const STATE_ACTIVE   = 'active';
    const STATE_INACTIVE = 'inactive';

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName);

    /**
     * @return string
     */
    public function getFirstName();

    /**
     * @param string $lastName
     */
    public function setLastName($lastName);

    /**
     * @return string
     */
    public function getLastName();

    /**
     * @param string $userName
     */
    public function setUserName($userName);

    /**
     * @return string
     */
    public function getUserName();

    /**
     * @return string
     */
    public function getEmail();

    /**
     * @param $email
     * @return $this
     */
    public function setEmail($email);

    /**
     * @return string
     */
    public function getState();

    /**
     * @param $state
     * @return $this
     */
    public function setState($state);

    /**
     * @return string
     */
    public function getCreatedTs();

    /**
     * @param $ts
     * @return $this
     */
    public function setCreatedTs($ts);

    /**
     * @param  RoleInterface $role
     * @return bool
     */
    public function hasRole(RoleInterface $role);

    /**
     * @param  RoleInterface $role
     * @return $this
     */
    public function setRole(RoleInterface $role);

    /**
     * @param string $passwordToken
     */
    public function setPasswordToken($passwordToken);

    /**
     * @return string
     */
    public function getPasswordToken();

    /**
     * @return \DateTime|null
     */
    public function getUpdatedTs();

    /**
     * @param $createdTs
     * @return $this
     */
    public function setUpdatedTs($updatedTs);

}
