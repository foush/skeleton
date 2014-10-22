<?php
namespace Application\Service;

class Acl extends Base
{
    /**
     * Not a user role, used to signal not logged in to system
     */
    const ROLE_GUEST = 'guest';
    /**
     * Basic user role
     */
    const ROLE_USER = 'user';
}
