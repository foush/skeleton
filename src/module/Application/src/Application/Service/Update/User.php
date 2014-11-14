<?php
namespace Application\Service\Update;

class User extends Base
{
    const MAIN_ENTITY_CLASS = 'Application\Entity\Base\User';

    const MAIN_ENTITY_ID_PARAM = 'userId';

    /**
     * Returns the route name to redirect to on successful update
     * @return string
     */
    public function getSuccessRedirectRouteName()
    {
        return 'home/system/user';
    }
}
